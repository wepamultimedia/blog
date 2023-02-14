<?php

namespace Wepa\Blog\Database\seeders;

use Wepa\Blog\Models\Post;

class PostDemoSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        Post::factory()->demo()->count(60)->create();
    }
}
