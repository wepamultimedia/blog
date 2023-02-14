<?php

namespace Wepa\Blog\Commands;

use Illuminate\Console\Command;
use Wepa\Core\Models\Menu;

class BlogUninstallCommand extends Command
{
    public $signature = 'blog:uninstall';

    public $description = 'Uninstall blog module';

    public function handle(): int
    {
        Menu::removePackageItems('blog');

        return self::SUCCESS;
    }
}
