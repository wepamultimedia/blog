<?php

namespace Wepa\Blog\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Str;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Blog\Models\Category;
use Wepa\Core\Models\Seo;

class CategoryFactory extends Factory
{
    protected static int $position;

    protected $model = Category::class;

    protected Seo $seo;

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
        self::$position = Category::nextPosition();
    }

    public function configure()
    {
        return $this->afterCreating(function (Category $category) {
            $seo = Seo::find($category->seo_id);
            $seo->request_params = ['categoryId' => $category->id];
            $seo->save();
        });
    }

    public function default()
    {
        $data = [
            'name' => 'Principal',
            'description' => 'En esta sección podrá encontrar contenido general y actualizado de nuestra web',
            'position' => 1,
        ];

        return $this->state(function () use ($data) {
            $seoParams = [
                'controller' => PostController::class,
                'action' => 'index',
                'robots' => ['noindex'],
                'package' => 'blog',
                'page_type' => 'article',
                'title' => $data['name'],
                'description' => $data['description'],
                'facebook_title' => $data['name'],
                'facebook_description' => $data['description'],
                'twitter_title' => $data['name'],
                'twitter_description' => $data['description'],
                'slug' => Str::slug($data['name']),
            ];
            $this->seo->update($seoParams);

            return $data;
        });
    }

    public function definition()
    {
        $this->seo = Seo::create([
            'controller' => PostController::class,
            'action' => 'index',
        ]);

        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(191),
            'seo_id' => $this->seo->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'position' => self::$position++,
            'published' => true,
        ];
    }

    public function demo()
    {
        return $this->state(function (array $attributes) {
            $seoParams = [
                'controller' => PostController::class,
                'action' => 'index',
                'robots' => ['noindex'],
                'package' => 'blog-demo',
                'page_type' => 'article',
                'title' => $attributes['name'],
                'description' => $attributes['description'],
                'facebook_title' => $attributes['name'],
                'facebook_description' => $attributes['description'],
                'twitter_title' => $attributes['name'],
                'twitter_description' => $attributes['description'],
                'slug' => Str::slug($attributes['name']),
            ];

            $this->seo->update($seoParams);

            return [];
        });
    }
}
