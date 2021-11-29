<?php

namespace App\Traits;

trait ToolsTrait{
    public function showName($name){
        $strLen = 4;
        $len = 12;
        while(strlen(mb_substr($name,0,$strLen))<$len){
            $strLen += 1;
            if($strLen >= mb_strlen($name))
                break;
        }
        if(strlen($name)>strlen(mb_substr($name,0,$strLen)))
            return mb_substr($name,0,$strLen) . '···';
        return $name;
    }
    public function showValLimitLength($val,$len){
        $strLen = (int)$len/2;
        while(mb_strwidth(mb_substr($val,0,$strLen))<$len){
            $strLen += 1;
            if($strLen >= mb_strlen($val))
                break;
        }
        if(strlen($val)>strlen(mb_substr($val,0,$strLen)))
            return mb_substr($val,0,$strLen) . '···';
        return $val;
    }
}
