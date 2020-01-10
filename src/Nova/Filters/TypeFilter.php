<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use RuthgerIdema\UrlRewrite\Entities\UrlRewrite;

class TypeFilter extends Filter
{
    /** @var string */
    public $component = 'select-filter';

    /** @var string */
    public $name = 'URL rewrite type';

    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('type', $value);
    }

    public function options(Request $request): array
    {
        return array_flip(UrlRewrite::getPossibleTypesArray());
    }
}
