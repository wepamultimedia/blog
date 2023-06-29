<?php

namespace Wepa\Blog\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;
use Wepa\Core\Models\Seo;

class PostFactory extends Factory
{
    protected $model = Post::class;

    protected static int $position = 1;

    public function configure()
    {
        self::$position = Post::nextPosition();
        
        return $this->afterMaking(function (Post $post) {
            $post->position = self::$position++;
            $post->seoAddParams([
                'image' => $post->cover,
                'facebook_image' => $post->cover,
                'twitter_image' => $post->cover,
                'image_alt' => $post->title,
                'title' => $post->title,
                'description' => $post->summary,
                'facebook_title' => $post->title,
                'facebook_description' => $post->summary,
                'twitter_title' => $post->title,
                'twitter_description' => $post->summary,
                'slug' => Str::slug($post->title)
            ]);
        });
    }

    public function definition()
    {
        $categoryId = rand(1, Category::count());
        $date = Carbon::create(rand(2000, 2023), rand(1, 12), rand(1, 30))->format('Y-m-d');

        return [
            'title' => $this->faker->text(40),
            'summary' => $this->faker->sentence(),
            'body' => $this->faker->realText(8000),
            'category_id' => $categoryId,
            'user_id' => 1,
            'cover' => $this->faker->imageUrl(),
            'cover_title' => $this->faker->sentence(),
            'cover_alt' => $this->faker->sentence(),
            'likes' => rand(20, 850),
            'start_at' => $date,
            'created_at' => $date,
            'updated_at' => $date,
            'draft' => false,
        ];
    }

    public function demo()
    {
        return $this->state(function (array $attributes) {
            return [];
        });
    }
}
