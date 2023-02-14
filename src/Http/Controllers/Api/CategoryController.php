<?php

namespace Wepa\Blog\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Wepa\Blog\Models\Category;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;


class CategoryController extends Controller
{
	use SeoControllerTrait;
	
	
	/**
	 * @param Request $request
	 *
	 * @return Collection
	 */
	public function index(Request $request): Collection
	{
		return Category::when($request->search, function($query, $search) {
			$query->whereTranslation('name', 'LIKE', '%' . $search . '%');
		})
			->with('seo')
			->orderBy('position', 'desc')->get();
	}
}