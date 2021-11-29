<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class Localization extends Middleware
{
    public function handle($request, \Closure $next)
    {
        if (session()->has('locale')) {
            \App::setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}
