<?php

namespace Wepa\Blog\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Blog\Http\Requests\Backend\PostRequest;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;
use Wepa\Core\Http\Controllers\Backend\InertiaController;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;

class PostController extends InertiaController
{
    use SeoControllerTrait;

    public string $packageName = 'blog';

    public function destroy(Post $post): Redirector|RedirectResponse|Application
    {
        $post->delete();

        return redirect(route('admin.blog.posts.index'));
    }

    public function draft(Post $post, $draft)
    {
        $post->update(['draft' => $draft]);
    }

    public function update(PostRequest $request, Post $post): Redirector|RedirectResponse|Application
    {
        $data = collect($request->all())
            ->merge([
                'start_at' => Carbon::create($request->start_at)->format('Y-m-d h:m:s'),
                'updated_at' => Carbon::now(),
            ])
            ->merge($request->translations)
            ->except(['translations'])
            ->toArray();

        $post->update($data);

        return redirect(route('admin.blog.posts.index'));
    }

    public function create(): Response
    {
        $categories = Category::where(['parent_id' => null])->get()->toArray();
        $post = (new Post())->load(['seo']);

        return $this->render('Vendor/Blog/Backend/Post/Create',
            ['core::seo', 'posts'],
            compact(['post', 'categories']));
    }

    public function edit(Post $post): Response
    {
        $categories = Category::get();
        $post = Post::whereId($post->id)->with('seo')->first()->attrsToArray('translations');

        return $this->render('Vendor/Blog/Backend/Post/Edit',
            ['core::seo', 'posts'],
            compact(['post', 'categories']));
    }

    public function index(Request $request): Response
    {
        $categories = Category::where(['published' => true])->get();

        $posts = Post::when($request->search,
            function ($query, $search) {
                $query->whereTranslationLike('title', '%'.$search.'%');
            })
            ->when($request->categoryId,
                function ($query, $categoryId) {
                    $query->where(['category_id' => $categoryId]);
                })
            ->orderBy('position', 'desc')
            ->paginate();

        return $this->render('Vendor/Blog/Backend/Post/Index',
            'posts',
            compact(['posts', 'categories']));
    }

    public function position(Post $post, int $position): Application|RedirectResponse|Redirector
    {
        $post->updatePosition($position);

        return redirect()->back();
    }

    public function store(PostRequest $request): Redirector|RedirectResponse|Application
    {
        $data = collect($request->all())
            ->merge($request->translations)
            ->merge([
                'start_at' => Carbon::create($request->start_at)->format('Y-m-d h:m:s'),
                'user_id' => auth()->user()->id,
                'position' => Post::nextPosition(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ])
            ->except(['translations'])
            ->toArray();

        Post::create($data);

        return redirect(route('admin.blog.posts.index'));
    }
}
