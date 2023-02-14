<?php

// config for Wepa/Blog
return [
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
