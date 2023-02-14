<?php

namespace Wepa\Blog\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
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

    /**
     * @param  Post  $post
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Post $post): Redirector|RedirectResponse|Application
    {
        if ($post->seo_id) {
            $this->seoDestroy($post->seo_id);
        }

        $post->delete();

        return redirect(route('admin.blog.posts.index'));
    }

    public function draft(Post $post, $draft)
    {
        $post->update(['draft' => $draft]);
    }

    /**
     * @param  PostRequest  $request
     * @param  Post  $post
     * @return Redirector|RedirectResponse|Application
     *
     * @throws ValidationException
     */
    public function update(PostRequest $request,
                           Post $post): Redirector|RedirectResponse|Application
    {
        $seoId = $this->seoUpdateOrCreate([
            'route_params' => ['post' => $post->id],
        ]);

        $post->update(array_merge($request->all(), $request->translations, [
            'seo_id' => $seoId,
            'start_at' => Carbon::create($request->start_at)
                ->format('Y-m-d h:m:s'),
        ]));

        return redirect(route('admin.blog.posts.index'));
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $categories = Category::where(['parent_id' => null])->get()->toArray();
        $post = (new Post())->attrsToArray(['seo']);

        return $this->render('Blog/Backend/Post/Create',
            ['core::seo', 'posts'],
            compact(['post', 'categories']));
    }

    /**
     * @param  Post  $post
     * @return Response
     */
    public function edit(Post $post): Response
    {
        $categories = Category::get();
        $post->attrsToArray(['seo', 'translations']);

        return $this->render('Blog/Backend/Post/Edit',
            ['core::seo', 'posts'],
            compact(['post', 'categories']));
    }

    /**
     * @param  Request  $request
     * @param  Category|null  $category
     * @return Response
     */
    public function index(Request $request): Response
    {
        $categories = Category::where(['published' => true])->get();

        $posts = Post::when($request->search,
            function ($query, $search) {
                $query->whereTranlation('title', 'LIKE', '%'.$search.'%');
            })
            ->when($request->categoryId,
                function ($query, $categoryId) {
                    $query->where(['category_id' => $categoryId]);
                })
            ->orderBy('position', 'desc')
            ->paginate();

        return $this->render('Blog/Backend/Post/Index',
            'posts',
            compact(['posts', 'categories']));
    }

    /**
     * @param  Post  $post
     * @param  int  $position
     * @return Application|Redirector|RedirectResponse
     */
    public function position(Post $post,
                             int $position): Application|RedirectResponse|Redirector
    {
        $post->updatePosition($position);

        return redirect()->back();
    }

    /**
     * @param  PostRequest  $request
     * @return Redirector|RedirectResponse|Application
     *
     * @throws BindingResolutionException
     */
    public function store(PostRequest $request): Redirector|RedirectResponse|Application
    {
        $seo = $this->seoStore();

        $data = collect($request->all())
            ->merge($request->translations)
            ->merge([
                'seo_id' => $seo->id,
                'start_at' => Carbon::create($request->start_at)
                    ->format('Y-m-d h:m:s'),
                'user_id' => auth()->user()->id,
                'position' => Post::nextPosition(),
            ])
            ->except(['translations'])
            ->toArray();

        $post = Post::create($data);

        $this->seoUpdate($seo->id, [
            'route_params' => ['post' => $post->id],
        ]);

        return redirect(route('admin.blog.posts.index'));
    }
}
