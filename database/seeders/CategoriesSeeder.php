<?php

namespace Wepa\Blog\Database\seeders;

use Wepa\Blog\Models\Category;

class CategoriesSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $position = 1;
        Category::factory()->count(5)->create([
            'position' => $position++,
        ]);
    }
}
