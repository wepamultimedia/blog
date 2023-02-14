<?php


use Illuminate\Support\Facades\Route;
use Wepa\Blog\Http\Controllers\Api\CategoryController;
use Wepa\Blog\Http\Controllers\Api\PostController;


Route::prefix('api/blog')->middleware(['api'])->group(function() {
	
	Route::prefix('posts')->group(function() {
		Route::get('/dates', [PostController::class, 'dates'])->name('api.blog.posts.dates');
		Route::get('/latest/{number}', [PostController::class, 'latest'])->name('api.blog.posts.latest');
	});
	
	Route::prefix('categories')->group(function() {
		Route::get('index', [CategoryController::class, 'index'])->name('api.blog.categories.index');
	});
});

Route::prefix('api/blog')->middleware(['api', 'auth:sanctum'])->group(function() {
});