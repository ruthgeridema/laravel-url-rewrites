<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use RuthgerIdema\UrlRewrite\Entities\UrlRewrite;

class RedirectTypeFilter extends Filter
{
    /** @var string */
    public $component = 'select-filter';

    /** @var string */
    public $name = 'URL rewrite redirect type';

    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('redirect_type', $value);
    }

    public function options(Request $request): array
    {
        return UrlRewrite::getRedirectTypeOptionsArray();
    }
}
