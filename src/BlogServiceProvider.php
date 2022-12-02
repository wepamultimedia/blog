<?php

namespace Wepa\Blog;


use Database\Seeders\DatabaseSeeder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wepa\Blog\Database\seeders\MenuSeeder;
use Wepa\Core\Database\seeders\CoreSeeder;
use Wepa\Blog\Commands\BlogCommand;


class BlogServiceProvider extends PackageServiceProvider
{
	public function bootingPackage()
	{
		$this->hasSeeders([]);
		
		$this->publishes([
			__DIR__ . '/../../resources/js'       => resource_path('js/Blog'),
			__DIR__ . '/../../resources/js/Pages' => resource_path('js/Pages/Blog'),
		], ['blog-js']);
		
		$this->publishes([
			__DIR__ . '/../../tests'       => base_path('tests/blog'),
		], ['blog-tests']);
	}
	
	/**
	 * @param array $seeders
	 *
	 * @return void
	 */
	protected function hasSeeders(array $seeders): void
	{
		$this->callAfterResolving(DatabaseSeeder::class,
			function($cb) use ($seeders) {
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
			->hasRoutes(['web', 'admin'])
			->hasMigrations(['create_blog_categories_table', 'create_blog_posts_table'])
			->runsMigrations()
			->hasCommand(BlogCommand::class);
		
		$this->hasSeeders([MenuSeeder::class]);
	}
}
