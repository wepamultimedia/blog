<?php

namespace Wepa\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    protected $table = 'blog_categories_translations';
}
