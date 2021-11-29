<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(User $model)
    {
        return view('users.index');
    }
}
