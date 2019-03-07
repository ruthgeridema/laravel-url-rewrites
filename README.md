# Easily add URL rewrites to a Laravel app

Very easy to use URL rewrite package. Follow the instructions and you're good to go!
I've used the [Spatie guidelines](https://guidelines.spatie.be/code-style/laravel-php) for better code.

## Requirements

This package requires Laravel 5.8 or higher and PHP 7.2 or higher. 


## Installation

You can install the package via composer:

``` bash
composer require ruthgeridema/laravel-url-rewrite
```

The package will automatically register itself.

Register the routes the feeds will be displayed on using the `rewrites`-macro.

```php
// In routes/web.php
Route::rewrites();
```

You can publish the migration with:

```bash
php artisan vendor:publish --provider="RuthgerIdema\UrlRewrite\ServiceProvider" --tag="migrations"
```

After the migration has been published you can create the `url_rewrites` table by running the migration:

```bash
php artisan migrate
```

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="RuthgerIdema\UrlRewrite\ServiceProvider" --tag="config"
```

This is the contents of the published config file:

```
<?php

return [
    'table-name' => 'url_rewrites',
    'repository' => \RuthgerIdema\UrlRewrite\Repositories\UrlRewriteRepository::class,
    'model' => \RuthgerIdema\UrlRewrite\Entities\UrlRewrite::class,
    'cache' => false,
    'cache-decorator' => \RuthgerIdema\UrlRewrite\Repositories\Decorators\CachingUrlRewriteRepository::class,
    'types' => [
        'product' => [
            'route' => 'product'
        ],
        'category' => [
            'route' => 'category'
        ]
    ]
];
```

## Testing

1. Copy `.env.example` to `.env` and fill in your database credentials.
2. Run `composer test`.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email ruthger.idema@gmail.com instead of using the issue tracker.


## Credits

- [Ruthger Idema](https://github.com/ruthgeridema)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


