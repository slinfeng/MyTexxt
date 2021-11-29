<?php

namespace App\Traits;

trait AuthNeedsTrait{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

}
