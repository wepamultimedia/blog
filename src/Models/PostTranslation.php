<?php

namespace Wepa\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'summary',
        'body',
        'cover_title',
        'cover_alt',
    ];

    protected $table = 'blog_posts_translations';
}
