<?php

namespace RuthgerIdema\UrlRewrite\Traits;

use RuthgerIdema\UrlRewrite\Facades\UrlRewrite;

trait HasUrlRewrite {

    public function getUrlAttribute(): string
    {
        if (! $urlRewrite = UrlRewrite::getByTypeAndAttributes(
            config("url-rewrite.types.$this->urlRewriteType.route"), $this->getAttributesArray())
        ) {
            return '';
        }

        return route('url.rewrite', $urlRewrite->request_path, false);
    }

    public function getUrlRewriteAttributesArray(): array
    {
        $mapped = [];

        foreach (config("url-rewrite.types.$this->urlRewriteType.attributes") as $attribute) {
            $mapped[$attribute] = $this->getAttribute($attribute);
        }

        return $mapped;
    }

}