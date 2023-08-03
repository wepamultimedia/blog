<?php

namespace Wepa\Blog\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Wepa\Blog\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request): Collection
    {
        return Category::when($request->search, function ($query, $search) {
            $query->whereTranslation('name', 'LIKE', '%'.$search.'%');
        })
            ->with('seo')
            ->orderBy('position', 'desc')->get();
    }
}
