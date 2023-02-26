<?php

namespace Wepa\Blog\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BlogUpdateCommand extends Command
{
    public $description = 'Update blog module';

    public string $package = 'blog';

    public $signature = 'blog:update';

    protected array $vendor = [];

    /**
     * @return int
     */
    public function handle(): int
    {
        $this->call('vendor:publish', ['--tag' => 'blog']);
        $this->call('vendor:publish', ['--tag' => 'laravisit-migrations']);
        $this->call('migrate');

        return self::SUCCESS;
    }
}
