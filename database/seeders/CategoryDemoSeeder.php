<?php

namespace Wepa\Blog\Database\seeders;

use Wepa\Blog\Models\Category;

class CategoryDemoSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        Category::factory()->demo()->count(20)->create();
    }
}
