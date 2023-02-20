<?php

namespace Wepa\Blog\Database\seeders;

use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Blog\Models\Category;
use Wepa\Core\Models\Menu;
use Wepa\Core\Models\Seo;

class DefaultSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        Menu::loadPackageItems('blog');

        if (! Category::whereTranslation('name', 'Main')->exists()) {
            Category::factory()->default()->create();
        }
    }
}
