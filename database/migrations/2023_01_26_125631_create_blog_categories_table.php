<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('seo_id');
            $table->integer('position');
            $table->boolean('published')->default(true);

            $table->foreign('seo_id')
                ->references('id')
                ->on('core_seo')
                ->cascadeOnDelete();

            $table->timestamps();
        });
        Schema::create('blog_categories_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('locale');
            $table->unique(['locale', 'category_id']);

            $table->foreign('category_id')
                ->references('id')
                ->on('blog_categories')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('description');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blog_categories_translations');
    }
};
