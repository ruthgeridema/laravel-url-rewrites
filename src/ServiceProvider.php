<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite;

use RuthgerIdema\UrlRewrite\Facades\UrlRewrite;
use RuthgerIdema\UrlRewrite\Http\UrlRewriteController;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateUrlRewriteTable')) {
                $timestamp = date('Y_m_d_His');
                $this->publishes([
                    __DIR__.'/../database/migrations/create_url_rewrites_table.php.stub' => database_path('migrations/'.$timestamp.'_create_url_rewrites_table.php'),
                ], 'migrations');
            }
            $this->publishes([
                __DIR__.'/../config/url-rewrite.php' => config_path('url-rewrite.php'),
            ], 'config');
            $this->publishes([
                __DIR__.'/../nova/UrlRewrite.php.stub' => app_path('Nova/UrlRewrite.php'),
            ], 'nova');
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'urlrewrites');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/url-rewrite.php',
            'url-rewrite'
        );

        $this->registerRepository();
        $this->registerFacade();
        $this->registerRouteMacro();
    }

    protected function registerRouteMacro(): void
    {
        $queryParam = '.*';

        if (class_exists('Laravel\\Nova\\Nova')) {
            $novaPath = ltrim($this->app['config']['nova']['path'], '/');
            $queryParam = "^(?!$novaPath).*";
        }

        $router = $this->app['router'];
        $router->macro('rewrites', function () use ($router, $queryParam) {
            $router->get('{url}', '\\'.UrlRewriteController::class)->where('url', $queryParam)->name('url.rewrite');
        });
    }

    protected function registerRepository(): void
    {
        $this->app->singleton(UrlRewriteInterface::class, function () {
            $urlRewriteConfig = $this->app['config']['url-rewrite'];
            $repositoryClass = $urlRewriteConfig['repository'];
            $modelClass = $urlRewriteConfig['model'];

            $repository = new $repositoryClass(new $modelClass);

            if (! $urlRewriteConfig['cache']) {
                return $repository;
            }

            $cacheClass = $urlRewriteConfig['cache-decorator'];

            return new $cacheClass($repository, $this->app['cache.store']);
        });
    }

    protected function registerFacade(): void
    {
        $this->app->bind(UrlRewrite::class, function () {
            return $this->app->make(UrlRewriteInterface::class);
        });
    }
}
