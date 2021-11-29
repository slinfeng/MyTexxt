<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class Amount implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return '¥'.number_format($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return preg_replace('/[¥,]/', '', $value);
    }
}
