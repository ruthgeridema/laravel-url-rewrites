<?php

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

    public function __construct(array $attributes = [])
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

    public function getByTypeAndAttributes($type, array $attributes)
    {
        $query = $this->where('type', $type);

        foreach ($attributes as $key => $attribute) {
            return $query->where("type_attributes->$key", "$attribute");
        }

        return $query;
    }
}
