<?php

namespace Wepa\Blog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Wepa\Blog\Database\seeders\CategoryDemoSeeder;
use Wepa\Blog\Database\seeders\PostDemoSeeder;
use Wepa\Core\Models\Seo;


class BlogDemoCommand extends Command
{
    public $signature = 'blog:demo';

    public $description = 'Install demo data';

    public function handle(): int
    {
	    Seo::where('package', 'blog-demo')->delete();
		
	    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call('db:seed', ['class' => CategoryDemoSeeder::class]);
        $this->call('db:seed', ['class' => PostDemoSeeder::class]);
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return self::SUCCESS;
    }
}
