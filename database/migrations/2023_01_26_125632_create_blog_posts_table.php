<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('user_id');
            $table->foreignId('seo_id');
            $table->date('start_at');
            $table->string('cover');
            $table->integer('visits')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('position');
            $table->boolean('draft')->default(false);

            $table->foreign('category_id')
                ->references('id')
                ->on('blog_categories')
                ->cascadeOnDelete();

            $table->foreign('seo_id')
                ->references('id')
                ->on('core_seo')
                ->cascadeOnDelete();

            $table->timestamps();
        });
        Schema::create('blog_posts_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id');
            $table->string('locale');
            $table->unique(['locale', 'post_id']);

            $table->foreign('post_id')
                ->references('id')
                ->on('blog_posts')
                ->onDelete('cascade');

            $table->string('title');
            $table->string('summary');
            $table->longText('body');
            $table->string('cover_title');
            $table->string('cover_alt');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_posts_translations');
    }
};
