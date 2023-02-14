<?php

namespace Wepa\Blog\Commands;

use Illuminate\Console\Command;
use Wepa\Blog\Database\seeders\CategoryDemoSeeder;
use Wepa\Blog\Database\seeders\PostDemoSeeder;

class BlogDemoCommand extends Command
{
    public $signature = 'blog:demo';

    public $description = 'Install demo data';

    public function handle(): int
    {
        $this->call('db:seed', ['class' => CategoryDemoSeeder::class]);
        $this->call('db:seed', ['class' => PostDemoSeeder::class]);
        $this->comment('All done');

        return self::SUCCESS;
    }
}
