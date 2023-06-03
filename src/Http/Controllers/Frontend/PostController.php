<?php

namespace Wepa\Blog\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Inertia\Response;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect;
use Wepa\Blog\Http\Resources\V1\PostResource;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;
use Wepa\Core\Http\Controllers\Frontend\InertiaController;
use Wepa\Blog\Http\Helpers\ClientHelper;
use Wepa\Core\Http\Traits\Frontend\SeoControllerTrait;

class PostController extends InertiaController
{
    use SeoControllerTrait;

    public string $packageName = 'blog';

    private \Wepa\Blog\Http\Controllers\Api\V1\PostController $api;

    public function __construct()
    {
        $this->api = new \Wepa\Blog\Http\Controllers\Api\V1\PostController();
    }

    /**
     * @return Response
     */
    public function index(Request $request)
    {
        $this->addSeo('blog');

        $categories = Category::where(['published' => true])
            ->orderBy('position', 'desc')
            ->with('seo')
            ->get();

        $category = Category::find($request->categoryId);
        $dates = $this->api->dates($request, 5);

        $posts = $this->api->index($request);

        $popular = $this->api->popular();

        return $this->render('Vendor/Blog/Frontend/Post/Index',
            ['posts', 'categories'],
            compact(['posts', 'categories', 'category', 'dates', 'popular']));
    }

    public function dates(Request $request): array
    {
        return $this->api->dates($request);
    }

    public function show(Post $post): Response
    {
        if($post->draft){
            abort(404);
        }
        
        $post = PostResource::make($post);

        $categories = Category::where(['published' => true])
            ->orderBy('position', 'desc')
            ->with('seo')
            ->get();

        $dates = $this->api->dates(request());
        $popular = $this->api->popular();

        return $this->render('Vendor/Blog/Frontend/Post/Show', ['posts', 'categories'], compact(['post', 'categories', 'dates', 'popular']));
    }
}
