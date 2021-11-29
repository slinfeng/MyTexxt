<?php

namespace App\Services;

class SharingService {

    private $shared = [];

    public function share($name, $value)
    {
        $this->shared[$name] = $value;
    }

    public function get($name, $default = null)
    {
        if (isset($this->shared[$name])) {
            return $this->shared[$name];
        }

        return $default;
    }
    public function set($name, $value = null)
    {
        if (isset($this->shared[$name])) {
            $this->shared[$name]=$value;
            return $this->shared[$name];
        }

        return $value;
    }
}
