<?php

namespace RuthgerIdema\UrlRewrite\Facades;

use Illuminate\Support\Facades\Facade;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class UrlRewrite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UrlRewriteInterface::class;
    }
}
