<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Exceptions;

use Exception;

class UrlRewriteAlreadyExistsException extends Exception
{
    public static function requestPath(string $requestPath): self
    {
        return new static("Request path `{$requestPath}` already exists.");
    }
}
