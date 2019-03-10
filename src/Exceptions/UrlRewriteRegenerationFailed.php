<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Exceptions;

use Exception;

class UrlRewriteRegenerationFailed extends Exception
{
    public static function noConfiguration(): self
    {
        return new static('No types are set in the configuration.');
    }

    public static function invalidType($type): self
    {
        return new static("Type `{$type}` does not exist.");
    }

    public static function columnNotSet($urlRewrite, $column): self
    {
        return new static("Url rewrite with id `{$urlRewrite->id}` has no `{$column}`");
    }
}
