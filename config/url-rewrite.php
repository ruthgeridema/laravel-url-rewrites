<?php

return [
    'table-name' => 'url_rewrites',
    'repository' => \RuthgerIdema\UrlRewrite\Repositories\UrlRewriteRepository::class,
    'model' => \RuthgerIdema\UrlRewrite\Entities\UrlRewrite::class,
    'cache' => false,
    'cache-tag' => 'url_rewrites',
    'cache-ttl' => 86400,
    'cache-decorator' => \RuthgerIdema\UrlRewrite\Repositories\Decorators\CachingUrlRewriteRepository::class,
    'types' => [
        'product' => [
            'route' => 'product',
            'attributes' => ['id'],
        ],
        'category' => [
            'route' => 'category',
            'attributes' => ['id'],
        ],
    ],
];
