<?php

namespace Tests\Unit\Blog;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Wepa\Blog\Database\seeders\CategoryDemoSeeder;
use Wepa\Blog\Database\seeders\PostDemoSeeder;
use Wepa\Blog\Http\Controllers\Backend\PostController;
use Wepa\Blog\Http\Requests\PostRequest;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected PostController $controller;

    public function __construct(?string $name = null,
                                array $data = [],
                                        $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->controller = new PostController();
    }

    public function test_position()
    {
        $post_params = $this->post_params();

        $post1 = Post::create(array_merge($post_params, [
            'title' => 'Post 1',
            'position' => 1,
        ]));

        $post2 = Post::create(array_merge($post_params, [
            'title' => 'Post 2',
            'position' => 2,
        ]));

        $post3 = Post::create(array_merge($post_params, [
            'title' => 'Post 3',
            'position' => 3,
        ]));

        $this->assertDatabaseHas('blog_posts',
            ['id' => $post1->id, 'position' => 1]);

        $this->assertDatabaseHas('blog_posts',
            ['id' => $post2->id, 'position' => 2]);

        $this->assertDatabaseHas('blog_posts',
            ['id' => $post3->id, 'position' => 3]);

        $this->controller->position($post1, 3);

        $this->assertDatabaseHas('blog_posts',
            ['id' => $post1->id, 'position' => 3]);

        $this->assertDatabaseHas('blog_posts',
            ['id' => $post2->id, 'position' => 1]);

        $this->assertDatabaseHas('blog_posts',
            ['id' => $post3->id, 'position' => 2]);
    }

    private function create_catetory(): Model|Category
    {
        return Category::create([
            'name' => 'Test category',
            'position' => 1,
        ]);
    }

    private function post_params(array $params = []): array
    {
        $user = $this->create_user();
        $category = $this->create_catetory();

        $defaultParams = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'start_at' => Carbon::now(),
            'visits' => 250,
            'likes' => 250,
            'position' => 1,
            'publish' => true,
            'title' => 'Title Stored',
            'summary' => 'Summary...',
            'body' => 'Summary...',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        return array_merge($defaultParams, $params);
    }

    private function create_user(): mixed
    {
        return User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
    }

    public function test_demo_seeder()
    {
        $this->seed(CategoryDemoSeeder::class);
        $this->seed(PostDemoSeeder::class);
        $this->assertDatabaseCount('blog_posts', 5);
    }

    public function test_destroy()
    {
        $this->controller->store(new PostRequest($this->post_params([
            'title' => 'Post to delete',
        ])));

        $this->assertDatabaseHas('blog_posts_translations',
            ['title' => 'Post to delete']);

        $post = Post::get()->last();

        $this->controller->destroy($post);

        $this->assertDatabaseMissing('blog_posts_translations',
            ['title' => 'Post to delete']);
    }

    public function test_store()
    {
        $post_params = $this->post_params([
            'title' => 'Title Stored',
        ]);

        $this->controller->store(new PostRequest($post_params));

        $this->assertDatabaseHas('blog_posts_translations',
            ['title' => 'Title Stored']);
    }

    public function test_update()
    {
        $this->controller->store(new PostRequest($this->post_params([
            'title' => 'Post to update',
        ])));

        $this->assertDatabaseHas('blog_posts_translations',
            ['title' => 'Post to update']);

        $post = Post::get()->last();

        $this->controller->update(new PostRequest([
            'title' => 'Post updated',
        ]),
            $post);

        $this->assertDatabaseHas('blog_posts_translations',
            ['title' => 'Post updated']);
    }
}
