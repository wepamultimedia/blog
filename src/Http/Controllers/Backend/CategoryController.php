<?php

namespace Wepa\Blog\Http\Controllers\Backend;


use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Wepa\Blog\Http\Requests\Backend\CategoryRequest;
use Wepa\Blog\Models\Category;
use Wepa\Core\Events\SeoModelDestroyedEvent;
use Wepa\Core\Events\SeoModelRequestEvent;
use Wepa\Core\Events\SeoModelSavedEvent;
use Wepa\Core\Http\Controllers\Backend\InertiaController;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;


class CategoryController extends InertiaController
{
    use SeoControllerTrait;
    
    
    public string $packageName = 'blog';
    
    public function destroy(Category $category): Redirector|RedirectResponse|Application
    {
        $category->delete();
        
        return redirect(route('admin.blog.categories.index'));
    }
    
    public function edit(Category $category): \Inertia\Response
    {
        $category = Category::whereId($category->id)->with('seo')->first();
        
        return $this->render('Vendor/Blog/Backend/Category/Edit',
            ['core::seo', 'categories'],
            ['category' => $category->attrsToArray(['translations'])]);
    }
    
    public function index(Request $request): \Inertia\Response
    {
        $categories = Category::when($request->search,
            function ($query, $search) {
                $query->whereTranslationLike('name', '%'.$search.'%');
            })
            ->where(['parent_id' => null])
            ->orderBy('position', 'desc')->paginate();
        
        return $this->render('Vendor/Blog/Backend/Category/Index',
            'categories',
            ['categories' => $categories]);
    }
    
    public function position(Category $category, int $position): void
    {
        $category->updatePosition($position,
            ['parent_id' => $category->parent_id]);
    }
    
    public function publish(Category $category, bool $published): void
    {
        $category->update(['published' => $published]);
    }
    
    public function update(CategoryRequest $request, Category $category): Application|RedirectResponse|Redirector
    {
        $params = collect($request->all())
            ->merge(['updated_at' => Carbon::now()])
            ->merge($request->translations)
            ->except(['translations'])
            ->toArray();
        
        $category->update($params);
        
        return redirect(route('admin.blog.categories.index'));
    }
    
    public function show(Category $category)
    {
        //
    }
    
    public function store(CategoryRequest $request): Redirector|RedirectResponse|Application
    {
        $data = collect($request->all())
            ->merge([
                'position' => Category::nextPosition(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ])
            ->merge($request->translations)
            ->except(['translations'])
            ->toArray();
        
        Category::create($data);
        
        return redirect(route('admin.blog.categories.index'));
    }
    
    public function create(): \Inertia\Response
    {
        $category = (new Category())->load('seo');
        return $this->render('Vendor/Blog/Backend/Category/Create',
            ['core::seo', 'categories'],
            compact(['category'])
        );
    }
}
