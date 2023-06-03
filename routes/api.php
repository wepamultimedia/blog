<?php

use Illuminate\Support\Facades\Route;
use Wepa\Blog\Http\Controllers\Api\V1\CategoryController;
use Wepa\Blog\Http\Controllers\Api\V1\PostController;

Route::prefix('api/v1/blog')->middleware(['api'])->group(function () {
    Route::get('posts', [PostController::class, 'index'])->name('api.v1.blog.posts.index');
    Route::get('post/show/{post}', [PostController::class, 'show'])->name('api.v1.blog.post.show');
    Route::post('post/visit/{post}', [PostController::class, 'visit'])->name('api.v1.blog.post.visit');
    Route::get('posts/dates', [PostController::class, 'dates'])->name('api.v1.blog.posts.dates');
    Route::get('posts/popular/{timeframe?}/{limit?}', [PostController::class, 'popular'])
        ->name('api.v1.blog.posts.popular');
    Route::get('posts/latest/{number}', [PostController::class, 'latest'])->name('api.v1.blog.posts.latest');

    Route::prefix('categories')->group(function () {
        Route::get('index', [CategoryController::class, 'index'])->name('api.v1.blog.categories.index');
    });
});

Route::prefix('api/v1/blog')->middleware(['api', 'auth:sanctum'])->group(function () {
});
