<?php

namespace Wepa\Blog;

use Database\Seeders\DatabaseSeeder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wepa\Blog\Commands\BlogDemoCommand;
use Wepa\Blog\Commands\BlogInstallCommand;
use Wepa\Blog\Commands\BlogSymLinkCommand;
use Wepa\Blog\Commands\BlogUninstallCommand;
use Wepa\Blog\Commands\BlogUpdateCommand;
use Wepa\Blog\Database\seeders\DefaultSeeder;

class BlogServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        $this->hasSeeders([DefaultSeeder::class]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'blog-migrations');

        // Pages
        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages/Vendor/Blog'),
        ], ['blog', 'blog-pages']);

        // Components
        $this->publishes([
            __DIR__.'/../resources/js/Components' => resource_path('js/Vendor/Blog'),
        ], ['blog', 'blog-components']);

        $this->publishes([
            __DIR__.'/../tests/Unit' => base_path('tests/Unit/blog'),
            __DIR__.'/../tests/Feature' => base_path('tests/Feature/blog'),
        ], ['blog-tests']);
    }

    protected function hasSeeders(array $seeders): void
    {
        $this->callAfterResolving(DatabaseSeeder::class,
            function ($cb) use ($seeders) {
                $cb->call($seeders);
            });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('blog')
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasRoutes(['web', 'admin', 'api'])
            ->hasCommands([
                BlogDemoCommand::class,
                BlogInstallCommand::class,
                BlogUpdateCommand::class,
                BlogUninstallCommand::class,
                BlogSymLinkCommand::class,
            ]);
    }
}
