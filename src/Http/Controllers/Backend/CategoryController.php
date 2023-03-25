<?php

namespace Wepa\Blog\Http\Controllers\Backend;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Wepa\Blog\Http\Requests\Backend\CategoryRequest;
use Wepa\Blog\Models\Category;
use Wepa\Core\Http\Controllers\Backend\InertiaController;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;

class CategoryController extends InertiaController
{
    use SeoControllerTrait;

    public string $packageName = 'blog';

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): Redirector|RedirectResponse|Application
    {
        if ($category->seo_id) {
            $this->seoDestroy($category->seo_id);
        }

        $category->delete();

        return redirect(route('admin.blog.categories.index'));
    }

    public function edit(Category $category): \Inertia\Response
    {
        return $this->render('Vendor/Blog/Backend/Category/Edit',
            ['core::seo', 'categories'],
            ['category' => $category->attrsToArray(['seo', 'translations'])]);
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

    /**
     * @throws BindingResolutionException
     */
    public function update(CategoryRequest $request,
                           Category $category): Application|RedirectResponse|Redirector
    {
        $seoId = $this->seoUpdateOrCreate([
            'request_params' => ['categoryId' => $category->id],
        ]);

        $params = collect($request->all())
            ->merge($request->translations)
            ->merge(['seo_id' => $seoId])
            ->except(['translations'])
            ->toArray();

        $category->update($params);

        return redirect(route('admin.blog.categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * @throws BindingResolutionException
     */
    public function store(CategoryRequest $request): Redirector|RedirectResponse|Application
    {
        $seoId = $this->seoUpdateOrCreate();

        $category = Category::create(array_merge($request->all(),
            ['position' => Category::nextPosition(), 'seo_id' => $seoId],
            $request->translations));

        $this->seoUpdate($seoId, [
            'request_params' => ['categoryId' => $category->id],
        ]);

        return redirect(route('admin.blog.categories.index'));
    }

    public function create(): \Inertia\Response
    {
        return $this->render('Vendor/Blog/Backend/Category/Create', ['core::seo', 'categories'], [
            'category' => (new Category())->attrsToArray(['seo']),
        ]);
    }
}
