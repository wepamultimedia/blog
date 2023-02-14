<?php


use Illuminate\Support\Facades\Route;
use Wepa\Blog\Http\Controllers\Backend\CategoryController;
use Wepa\Blog\Http\Controllers\Backend\PostController;


Route::prefix('admin/blog')
	->middleware(['web', 'auth:sanctum', 'core.backend'])
	->group(function() {
		
		// Categories
		Route::resource('categories', CategoryController::class)
			->names('admin.blog.categories');
		
		Route::prefix('categories')->group(function() {
			
			Route::put('position/{category}/{position}', [CategoryController::class, 'position'])
				->name('admin.blog.category.position');
			
            Route::put('categories/publish/{category}/{published}',
	            [CategoryController::class, 'publish'])
	            ->name('admin.blog.category.publish');
		});
		
		// Posts
		Route::resource('posts', PostController::class)
			->names('admin.blog.posts');
		
		Route::prefix('posts')->group(function() {
			
			Route::put('position/{post}/{position}',
				[PostController::class, 'position'])
				->name('admin.blog.posts.position');
			
			Route::put('draft/{post}/{draft}',
				[PostController::class, 'draft'])
				->name('admin.blog.posts.draft');
		});
	});
