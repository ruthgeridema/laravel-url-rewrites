<?php

return [
    'table-name' => 'url_rewrites',
    'repository' => \RuthgerIdema\UrlRewrite\Repositories\UrlRewriteRepository::class,
    'model' => \RuthgerIdema\UrlRewrite\Entities\UrlRewrite::class,
    'cache' => true,
    'cache-decorator' => \RuthgerIdema\UrlRewrite\Repositories\Decorators\CachingUrlRewriteRepository::class,
    'types' => [
        'product' => [
            'route' => 'product'
        ]/*,
        'category' => [
            'route' => 'category'
        ]*/
    ]
];
