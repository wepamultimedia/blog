<?php

namespace Wepa\Blog\Database\seeders;

use Wepa\Blog\Models\Category;
use Wepa\Core\Models\Menu;

class DefaultSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        Menu::loadPackageItems('blog');

        if (! Category::whereTranslation('name', 'General')->exists()) {
            Category::factory()->default()->create();
        }
    }
}
