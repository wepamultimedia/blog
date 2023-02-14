<?php

namespace Wepa\Blog\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Wepa\Blog\Models\Post;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;


class PostController extends Controller
{
	use SeoControllerTrait;
	
	
	public string $packageName = 'blog';
	
	/**
	 * @param Request $request
	 * @param int|null $limit
	 *
	 * @return array
	 */
	public function dates(Request $request, int $limit = null): array
	{
		$queryDates = Post::select(DB::raw('DATE_FORMAT(start_at, "%Y-%m") AS mdate, count(*) AS totalMonth'))
			->where('start_at', '<=', date('Y-m-d'))
			->groupBy('mdate')
			->orderBy('mdate', 'desc');
		
		if($limit) {
			$queryDates->limit($limit);
		}
		
		$dates = [];
		
		$postDates = $queryDates->get();
		
		if($postDates) {
			$selectedDate = false;
			
			if(isset($request['start_at'])) {
				$selectedDate = true;
			}
			
			$i = 0;
			
			foreach($postDates as $postDate) {
				[$year, $month] = explode('-', $postDate->mdate);
				$date = $postDate->mdate . '-01';
				$active = false;
				
				if($selectedDate) {
					if(isset($request['start_at']) and $request['start_at'] == $date) {
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
	
	/**
	 * @param Post $post
	 *
	 * @return Application|RedirectResponse|Redirector
	 */
	public function destroy(Post $post): Redirector|RedirectResponse|Application
	{
		$post->delete();
	}
	
	public function draft(Post $post, $draft)
	{
	}
	
	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	public function index(Request $request): array
	{
	}
	
	/**
	 * @param int $number
	 *
	 * @return mixed
	 */
	public function latest(int $number = 6)
	{
		$posts = Post::orderBy('position', 'desc')
			->limit($number)
			->get();
		
		return $posts->map(function ($post){
			$post['url'] = request()->root() . '/' . $post->seo->slug;
			return $post->only(['id', 'title', 'summary', 'cover', 'cover_alt', 'url']);
		});
	}
}
