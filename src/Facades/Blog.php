<?php

namespace Wepa\Blog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wepa\Blog\Blog
 */
class Blog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wepa\Blog\Blog::class;
    }
}
