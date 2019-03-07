<?php

namespace RuthgerIdema\UrlRewrite\Test;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $table = 'test_models';
    protected $guarded = [];
    public $timestamps = false;
}
