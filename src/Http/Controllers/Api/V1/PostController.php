<?php

namespace Wepa\Blog\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
		$posts = Post::with('category')->orderBy('position', 'desc')
			->limit($number)
			->get();
		
		return $posts->map(function($post) {
			$post['url'] = request()->root() . '/' . $post->seo->slug;
			$post['category'] = $post->category->name ?? '';
			$post['start_at'] = Carbon::createFromDate($post->start_at)->locale(config('app.locale'))->format('d M Y');
			
			return $post->only(['id', 'title', 'summary', 'cover', 'cover_alt', 'url', 'start_at', 'category']);
		});
	}
	
	public function popular(string $timeFrame = 'thisWeek', int $limit = 5)
	{
		$timeFrames = ['today', 'thisWeek', 'lastWeek', 'thisMonth', 'lastMonth'];
		if(in_array($timeFrame, $timeFrames)) {
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
			
			if($popular){
				return $popular->map(function($post) {
					$post['slug'] = $post->seo->slug;
					
					return $post->only(['title', 'cover', 'slug']);
				});
			}
			
			return response()->json(['error' => 'Something went wrong'], 500);
		}
		
		return response()->json(['error' => 'Invalid timeframe'], 400);
	}
}
