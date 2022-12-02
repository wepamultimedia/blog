<?php

namespace Wepa\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wepa\Blog\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'publish' => true,
        ];
    }
}
