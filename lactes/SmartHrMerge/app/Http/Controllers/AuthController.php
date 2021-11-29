<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Jenssegers\Agent\Facades\Agent;

class AuthController extends Controller
{
    public function showLoginForm(){

        if(User::count()==0){
            return view('auth.register');
        }
        if (Agent::isMobile()){
            $id = Cookie::get('id');
            return view('mobile.auth.login',compact('id'));
        } else
            return view('auth.login');
    }

    public function showResetForm(){
        if (Agent::isMobile())
            return view('mobile.auth.reset');
        else
            return view('auth.passwords.reset');
    }

    public function showEmailForm(){
        if (Agent::isMobile())
            return view('mobile.auth.email');
        else
            return view('auth.passwords.email');
    }

    public function showConfirmForm(){
        if (Agent::isMobile())
            return view('mobile.auth.email');
        else
            return view('auth.passwords.email');
    }
}
