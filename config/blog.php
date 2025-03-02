<?php

// config for Wepa/Blog
use Wepa\Core\Models\Seo;

return [
    'routes' => [
        'category_slug_prefix' => ['es' => '', 'en' => ''],
        'post_slug_prefix' => [],
    ],
    'cache_tag' => 'blog',
    'seo' => [
        'post' => [
            'change_freq' => Seo::CHANGE_FREQUENCY_WEEKLY,
            'priority' => 0.7,
        ],
        'category' => [
            /* https://www.contentpowered.com/blog/xml-sitemap-priority-changefreq/ */
            'change_freq' => Seo::CHANGE_FREQUENCY_WEEKLY,
            'priority' => 0.7,
        ],
    ],
    'backend_menu' => [
        [
            'label' => 'en:Blog|es:Blog',
            'icon' => 'newspaper',
            'route' => '#',
            'active' => 'admin.blog*',
            'can' => 'admin.auth',
            'children' => [
                [
                    'label' => 'en:Categories|es:Categorías',
                    'route' => 'admin.blog.categories.index',
                    'active' => 'admin.blog.categories*',
                    'can' => 'admin.auth',
                ],
                [
                    'label' => 'en:Posts|es:Publicaciones',
                    'route' => 'admin.blog.posts.index',
                    'active' => 'admin.blog.posts*',
                    'can' => 'admin.auth',
                ],
            ],
        ],
    ],
];
