<?php

namespace Wepa\Blog\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Wepa\Blog\Http\Resources\V1\PostResource;
use Wepa\Blog\Models\Post;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;

class PostController extends Controller
{
    use SeoControllerTrait;

    public string $packageName = 'blog';

    public function dates(Request $request, int $limit = null): array
    {
        $queryDates = Post::select(DB::raw('DATE_FORMAT(start_at, "%Y-%m") AS mdate, count(*) AS totalMonth'))
            ->where('start_at', '<=', date('Y-m-d'))
            ->groupBy('mdate')
            ->orderBy('mdate', 'desc');

        if ($limit) {
            $queryDates->limit($limit);
        }

        $dates = [];

        $postDates = $queryDates->get();

        if ($postDates) {
            $selectedDate = false;

            if (isset($request['start_at'])) {
                $selectedDate = true;
            }

            $i = 0;

            foreach ($postDates as $postDate) {
                [$year, $month] = explode('-', $postDate->mdate);
                $date = $postDate->mdate.'-01';
                $active = false;

                if ($selectedDate) {
                    if (isset($request['start_at']) and $request['start_at'] == $date) {
                        $active = true;
                    }
                }

                $dates[] = [
                    'active' => $active ? 'active' : '',
                    'date' => $date,
                    'label' => ucfirst(Carbon::create($date)
                        ->translatedFormat('M - Y')),
                    'month' => $month,
                    'year' => $year,
                    'total' => $postDate->totalMonth,
                ];
                $i++;
            }
        }

        return $dates;
    }

    public function destroy(Post $post): void
    {
        $post->delete();
    }

    public function draft(Post $post, $draft)
    {
    }

    public function show(Post $post): PostResource
    {
        return PostResource::make($post);
    }

    public function index(Request $request): mixed
    {
        $posts = Post::when($request->search,
            function ($query, $search) {
                $query->whereTranlation('title', 'LIKE', '%'.$search.'%');
            })
            ->when($request->categoryId,
                function ($query, $categoryId) {
                    $query->where(['category_id' => $categoryId]);
                })
            ->when($request->start_at, function ($query, $start_at) {
                $query->where([
                    ['start_at', '>=', $start_at],
                    ['start_at', '<', Carbon::create($start_at)->addMonth(1)->format('Y-m-d')],
                ]);
            })
            ->orderBy('position', 'desc')
            ->paginate();

        return PostResource::collection($posts);
    }

    public function latest(int $number = 6): mixed
    {
        $posts = Post::with('category')->orderBy('position', 'desc')
            ->limit($number)
            ->get();

        return $posts->map(function ($post) {
            return $post->only(['id', 'title', 'summary', 'cover', 'cover_alt', 'url', 'start_at', 'category_name']);
        });
    }

    public function popular(string $timeFrame = 'thisWeek', int $limit = 5): mixed
    {
        $timeFrames = ['today', 'thisWeek', 'lastWeek', 'thisMonth', 'lastMonth'];
        $popular = null;

        switch($timeFrame) {
            case 'today':
                $popular = Post::popularToday()->limit($limit)->get();
                break;
            case 'thisWeek':
                $popular = Post::popularThisWeek()->limit($limit)->get();
                break;
            case 'lastWeek':
                $popular = Post::popularLastWeek()->limit($limit)->get();
                break;
            case 'thisMonth':
                $popular = Post::popularThisMonth()->limit($limit)->get();
                break;
            case 'lastMonth':
                $popular = Post::popularLastMonth()->limit($limit)->get();
                break;
        }

        return $popular->map(function ($post) {
            $post['slug'] = $post->seo->slug;

            return $post->only(['title', 'cover', 'cover_alt', 'slug']);
        });
    }
}
