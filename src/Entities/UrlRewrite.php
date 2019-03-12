<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Entities;

use Illuminate\Database\Eloquent\Model;

class UrlRewrite extends Model
{
    /** @var int */
    public const FORWARD = 0;

    /** @var int */
    public const PERMANENT = 1;

    /** @var int */
    public const TEMPORARY = 2;

    /** @var array */
    protected $fillable = [
        'type',
        'type_attributes',
        'request_path',
        'target_path',
        'redirect_type',
        'description',
    ];

    /** @var array */
    protected $casts = [
        'type_attributes' => 'array',
    ];

    public function __construct(?array $attributes = [])
    {
        if (! isset($this->table)) {
            $this->setTable(config('url-rewrite.table-name'));
        }

        parent::__construct($attributes);
    }

    public function isForward(): bool
    {
        return $this->redirect_type === static::FORWARD;
    }

    public function isRedirect(): bool
    {
        return $this->redirect_type !== static::FORWARD;
    }

    public function getRedirectType(): int
    {
        return $this->redirect_type === static::PERMANENT ? 301 : 302;
    }

    public function getByTypeAndAttributes(string $type, array $attributes)
    {
        $query = $this->where('type', $type);

        foreach ($attributes as $key => $attribute) {
            $query = $query->where("type_attributes->$key", (string) $attribute);
        }

        return $query;
    }

    public static function getRedirectTypeOptionsArray(): array
    {
        return [
            static::FORWARD => trans('urlrewrites::translations.forward'),
            static::PERMANENT => trans('urlrewrites::translations.permanent'),
            static::TEMPORARY => trans('urlrewrites::translations.temporary'),
        ];
    }

    public static function getPossibleTypesArray(): array
    {
        $array = [];

        foreach (array_keys(config('url-rewrite.types')) as $type) {
            $array[$type] = $type;
        }

        return $array;
    }
}
