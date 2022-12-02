<?php


use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['web', 'auth:sanctum', 'core.backend'])->group(function() {
	Route::get('/blog/categories', function() {
	
	})->name('admin.blog.categories.index');
	
	Route::get('/blog/posts', function() {
	
	})->name('admin.blog.posts.index');
});