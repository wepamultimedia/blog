<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Wepa\Blog\Models\Category;
use Wepa\Blog\Models\Post;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\Site;


return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $seo = Seo::find($category->seo_id);
            $seo->update([
               'model_type' => Category::class,
               'model_id' => $category->id
            ]);
        }
        
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropForeign('blog_categories_seo_id_foreign');
            $table->dropColumn('seo_id');
        });
    
        $posts = Post::all();
        foreach ($posts as $post) {
            $seo = Seo::find($post->seo_id);
            $seo->update([
                'model_type' => Post::class,
                'model_id' => $post->id
            ]);
        }
    
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign('blog_posts_seo_id_foreign');
            $table->dropColumn('seo_id');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_site', function (Blueprint $table) {
            $table->foreignId('seo_id')->nullable();
        });
    }
};
