<?php

namespace App\Traits;

trait CastFormatTrait{
    public function clearMoneyToIntStr($money){
        return preg_replace('/[￥,]/', '',$money);
    }
}
