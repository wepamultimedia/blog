<?php

namespace Wepa\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wepa\Blog\Models\Category;


class CategoryFactory extends Factory
{
    protected $model = Category::class;
	

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'publish' => true
        ];
    }
}
