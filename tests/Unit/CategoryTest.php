<?php

namespace Wepa\Blog\Tests\Unit;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Wepa\Blog\Database\seeders\CategoryDemoSeeder;
use Wepa\Blog\Database\seeders\DefaultSeeder;
use Wepa\Blog\Http\Controllers\Backend\CategoryController;
use Wepa\Blog\Http\Requests\CategoryRequest;
use Wepa\Blog\Models\Category;


class CategoryTest extends TestCase
{
    use LazilyRefreshDatabase;
    
    
    protected CategoryController $controller;
    
    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);
        
        $this->controller = new CategoryController();
    }
    
    public function test_demo_seeder()
    {
        $this->seed(CategoryDemoSeeder::class);
        
        $this->assertDatabaseCount('blog_categories', 5);
    }
    
    public function test_destroy()
    {
        $this->controller->store(new CategoryRequest([
            'name' => 'Category to delete',
            'position' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]));
        
        $this->assertDatabaseHas('blog_categories_translations',
            ['name' => 'Category to delete']);
        
        $category = Category::get()->last();
        
        $this->controller->destroy($category);
        
        $this->assertDatabaseMissing('blog_categories_translations',
            ['name' => 'Category to delete']);
    }
    
    public function test_install_seeder()
    {
        $this->seed(DefaultSeeder::class);
        
        $this->assertDatabaseHas('blog_categories_translations', [
            'name' => 'General',
        ]);
    }
    
    public function test_position()
    {
        $category1 = Category::create([
            'name' => 'Category 1',
            'position' => 1,
        ]);
        
        $category2 = Category::create([
            'name' => 'Category 2',
            'position' => 2,
        ]);
        
        $category3 = Category::create([
            'name' => 'Category 3',
            'position' => 3,
        ]);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category1->id, 'position' => 1]);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category2->id, 'position' => 2]);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category3->id, 'position' => 3]);
        
        $this->controller->position($category1, 3);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category1->id, 'position' => 3]);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category2->id, 'position' => 1]);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category3->id, 'position' => 2]);
    }
    
    public function test_publish()
    {
        $category = Category::create([
            'name' => 'Category',
            'position' => 1,
            'publish' => false,
        ]);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category->id, 'publish' => false]);
        
        $this->controller->publish($category, true);
        
        $this->assertDatabaseHas('blog_categories',
            ['id' => $category->id, 'publish' => true]);
    }
    
    public function test_store()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        
        $this->controller->store(new CategoryRequest([
            'name' => 'New category',
            'position' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]));
        
        $this->assertDatabaseHas('blog_categories_translations',
            ['name' => 'New category']);
    }
    
    public function test_update()
    {
        $this->controller->store(new CategoryRequest([
            'name' => 'Category to update',
            'position' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]));
        
        $this->assertDatabaseHas('blog_categories_translations',
            ['name' => 'Category to update']);
        
        $category = Category::get()->last();
        
        $this->controller->update(new CategoryRequest(['name' => 'Category updated']),
            $category);
        
        $this->assertDatabaseHas('blog_categories_translations',
            ['name' => 'Category updated']);
    }
}
