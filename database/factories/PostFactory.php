<?php

namespace Wepa\Blog\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Str;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;
use Wepa\Core\Models\Seo;

class PostFactory extends Factory
{
    protected $model = Post::class;

    protected Seo $seo;

    protected static int $_position;

    public function __construct($count = null,
                                ?Collection $states = null,
                                ?Collection $has = null,
                                ?Collection $for = null,
                                ?Collection $afterMaking = null,
                                ?Collection $afterCreating = null,
        $connection = null,
                                ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
        self::$_position = Post::nextPosition();
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            $seo = Seo::find($post->seo_id);
            $seo->route_params = ['post' => $post->id];
            $seo->save();
        });
    }

    public function definition()
    {
        $this->seo = Seo::create([
            'controller' => PostController::class,
            'action' => 'show',
        ]);

        $categoryId = rand(1, Category::count());
        $date = Carbon::create(rand(2000, 2023), rand(1, 12), rand(1, 30))->format('Y-m-d');

        return [
            'title' => $this->faker->text(40),
            'summary' => $this->faker->sentence(),
            'body' => $this->faker->realText(8000),
            'category_id' => $categoryId,
            'user_id' => 1,
            'seo_id' => $this->seo->id,
            'cover' => $this->faker->imageUrl(),
            'cover_title' => $this->faker->sentence(),
            'cover_alt' => $this->faker->sentence(),
            'visits' => rand(100, 1000),
            'likes' => rand(20, 850),
            'start_at' => $date,
            'created_at' => $date,
            'updated_at' => $date,
            'position' => self::$_position++,
            'draft' => false,
        ];
    }

    public function demo()
    {
        return $this->state(function (array $attributes) {
            $image = $this->faker->imageUrl();
            $seoParams = [
                'image' => $image,
                'facebook_image' => $image,
                'twitter_image' => $image,
                'package' => 'blog',
                'page_type' => 'article',
                'title' => $attributes['title'],
                'description' => $attributes['summary'],
                'facebook_title' => $attributes['title'],
                'facebook_description' => $attributes['summary'],
                'twitter_title' => $attributes['title'],
                'twitter_description' => $attributes['summary'],
                'slug' => Str::slug($attributes['title']),
            ];

            $this->seo->update($seoParams);

            return [];
        });
    }
}
