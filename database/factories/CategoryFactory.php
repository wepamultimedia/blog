<?php

namespace Wepa\Blog\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Wepa\Blog\Models\Category;

class CategoryFactory extends Factory
{
    protected static int $position = 1;

    protected $model = Category::class;

    public function configure()
    {
        self::$position = Category::nextPosition();

        return $this->afterMaking(function (Category $category) {
            $category->position = self::$position++;

            $category->seoAddParams([
                'title' => $category->name,
                'description' => $category->description,
                'facebook_title' => $category->name,
                'facebook_description' => $category->description,
                'twitter_title' => $category->name,
                'twitter_description' => $category->description,
                'slug' => Str::slug($category->name),
            ]);
        });
    }

    public function default()
    {
        return $this->state(function () {
            return [
                'name' => 'General',
                'description' => '...',
            ];
        });
    }

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name,
            'description' => $this->faker->text(191),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'published' => true,
        ];
    }

    public function demo()
    {
        return $this->state(function () {
            return [];
        });
    }
}
