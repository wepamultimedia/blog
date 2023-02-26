<?php

use Illuminate\Support\Facades\Route;
use Wepa\Blog\Http\Controllers\Api\V1\CategoryController;
use Wepa\Blog\Http\Controllers\Api\V1\PostController;

Route::prefix('api/v1/blog')->middleware(['api'])->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/dates', [PostController::class, 'dates'])->name('api.v1.blog.posts.dates');
        Route::get('/popular/{timeframe?}/{limit?}', [PostController::class, 'popular'])->name('api.v1.blog.posts.popular');
        Route::get('/latest/{number}', [PostController::class, 'latest'])->name('api.v1.blog.posts.latest');
    });

    Route::prefix('categories')->group(function () {
        Route::get('index', [CategoryController::class, 'index'])->name('api.v1.blog.categories.index');
    });
});

Route::prefix('api/v1/blog')->middleware(['api', 'auth:sanctum'])->group(function () {
});
