<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class PostCode implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return isset($value)?'〒'.substr($value,0,3).'-'.substr($value,3,4):'〒101-0031';
    }

    public function set($model, $key, $value, $attributes)
    {
        return preg_replace('/[〒-]/', '', $value);
    }
}
