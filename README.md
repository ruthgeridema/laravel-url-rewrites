# Easily add URL rewrites to a Laravel app
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ruthgeridema/laravel-url-rewrites.svg?style=flat-square)](https://packagist.org/packages/ruthgeridema/laravel-url-rewrites)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/ruthgeridema/laravel-url-rewrites.svg?branch=master)](https://travis-ci.org/ruthgeridema/laravel-url-rewrites)
[![Quality Score](https://img.shields.io/scrutinizer/g/ruthgeridema/laravel-url-rewrites.svg?style=flat-square)](https://scrutinizer-ci.com/g/ruthgeridema/laravel-url-rewrites)
[![StyleCI](https://styleci.io/repos/174381685/shield?branch=master)](https://styleci.io/repos/174381685)
[![Total Downloads](https://img.shields.io/packagist/dt/ruthgeridema/laravel-url-rewrites.svg?style=flat-square)](https://packagist.org/packages/ruthgeridema/laravel-url-rewrites)

Very easy to use URL rewrite package. Follow the instructions and you're good to go!

You can find an example project on my Github: [view example project](https://github.com/ruthgeridema/laravel-url-rewrites-example)  
This example project features the following:
- Eloquent observers to add URL rewrites automatically
- Usage of the trait
- Some use cases
  
## Requirements

This package requires Laravel 5.8 or higher, PHP 7.2 or higher and a database that supports json fields and functions such as MySQL 5.7 or higher.

## Installation

You can install the package via composer:

``` bash
composer require ruthgeridema/laravel-url-rewrites
```

The package will automatically register itself.

Register the routes the feeds will be displayed on using the `rewrites`-macro.  
You need to place it at the bottom of your routes file.

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

```php
<?php

return [
    'table-name' => 'url_rewrites',
    'repository' => \RuthgerIdema\UrlRewrite\Repositories\UrlRewriteRepository::class,
    'model' => \RuthgerIdema\UrlRewrite\Entities\UrlRewrite::class,
    'cache' => true,
    'cache-decorator' => \RuthgerIdema\UrlRewrite\Repositories\Decorators\CachingUrlRewriteRepository::class,
    'types' => [
        'product' => [
            'route' => 'product',
            'attributes' => ['id'],
        ],
        'category' => [
            'route' => 'category',
            'attributes' => ['id'],
        ]
    ],
];
```
#### Laravel Nova
Using Laravel Nova? You can publish the Nova class to App/Nova with the following command

```bash
php artisan vendor:publish --provider="RuthgerIdema\UrlRewrite\ServiceProvider" --tag="nova"
```
  
In the near future I will publish a Laravel Nova package with features like reindexing the URL rewrites.  
## Usage

### Forward request

Let's say you've got a controller route 'product/{id}' and you have a product 'Apple Airpods' with id=5.  
When you visit 'apple-airpods' this package will forward the request to the controller but keeps the clean url.

The following code adds this to the database:
```php
UrlRewrite::create('apple-airpods', 'product/5')
```

### Use named routes
You must specify the types in the config. 
```php
UrlRewrite::create('apple-airpods', null, 'product', ["id" => 5])
```

To regenerate the target path you can use
```php
UrlRewrite::regenerateRoute($urlRewrite)
UrlRewrite::regenerateAll()
UrlRewrite::regenerateRoutesFromType($type)
```

To automatically add the URL attribute to an Eloquent model, you have to add the HasUrlRewrite trait to an Eloquent model.  
You also need to add the urlRewriteType and optionally add 'url' to the appends array.

```php
use HasUrlRewrite;
public $urlRewriteType = 'category';
protected $appends = ['url'];
```

Once this is done you can simply call `Model::find(1)->url` to get the url of the model.

### Redirect

301 redirect
```php
UrlRewrite::create('apple-airpods', 'product/5', null, null, 1)
```
302 redirect
```php
UrlRewrite::create('apple-airpods', 'product/5', null, null, 2)
```

### Other functions
```php
UrlRewrite::all()
UrlRewrite::find($id)
UrlRewrite::delete($id)
UrlRewrite::update($data, $id)
UrlRewrite::getByRequestPath('apple-airpods')
UrlRewrite::getByTargetPath('product/5')
UrlRewrite::getByTypeAndAttributes('product', ["id" => 5])
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

Special thanks for Spatie for their guidelines and their packages as an inspiration
- [Spatie](https://spatie.be)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


