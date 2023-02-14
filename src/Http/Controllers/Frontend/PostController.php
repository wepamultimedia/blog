<?php

namespace Wepa\Blog\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Response;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;
use Wepa\Core\Http\Controllers\Frontend\InertiaController;
use Wepa\Core\Http\Traits\Frontend\SeoControllerTrait;

class PostController extends InertiaController
{
    use SeoControllerTrait;

    public string $packageName = 'blog';

    private \Wepa\Blog\Http\Controllers\Api\PostController $api;

    public function __construct()
    {
        $this->api = new \Wepa\Blog\Http\Controllers\Api\PostController();
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $categories = Category::where(['published' => true])
            ->orderBy('position', 'desc')
            ->with('seo')
            ->limit(5)
            ->get();

        $category = Category::find($request->categoryId);
        $dates = $this->api->dates($request, 5);

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
            ->with('seo')
            ->orderBy('position', 'desc')
            ->paginate();

        return $this->render('Blog/Frontend/Post/Index',
            ['posts', 'categories'],
            compact(['posts', 'categories', 'category', 'dates']));
    }

    /**
     * @param  Request  $request
     * @return array
     */
    public function dates(Request $request): array
    {
        return $this->api->dates($request);
    }

    /**
     * @param  Post  $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        $categories = Category::where(['published' => true])
            ->orderBy('position', 'desc')
            ->with('seo')
            ->limit(5)
            ->get();

        $dates = $this->api->dates(request(), 5);

        return $this->render('Blog/Frontend/Post/Show', ['posts', 'categories'], compact(['post', 'categories', 'dates']));
    }
}
