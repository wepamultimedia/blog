<?php

namespace Wepa\Blog\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BlogInstallCommand extends Command
{
    public $description = 'Install blog module';

    public string $package = 'blog';

    public $signature = 'blog:install';

    protected array $vendor = [];

    public function handle(): int
    {
        $this->call('migrate');
        $this->call('vendor:publish', ['--tag' => 'blog', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'laravisit-migrations']);
        $this->call('db:seed', ['class' => 'Wepa\Blog\Database\Seeders\DefaultSeeder']);

        $process = Process::fromShellCommandline('npm i -D @vuepic/vue-datepicker');
        $process->run();
        $this->info($process->getOutput());

        return self::SUCCESS;
    }
}
