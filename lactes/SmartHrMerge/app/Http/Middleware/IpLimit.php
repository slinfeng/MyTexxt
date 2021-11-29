<?php

namespace App\Http\Middleware;

use App\Constants\RedisKey;
use App\Models\UserIpAddress;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;

class IpLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()) return redirect(route('login'));
        $roles = Auth::user()->roles;
        if($roles->isEmpty()) {
            Auth::logout();
            return redirect(route('login'))->with('loginMessage','ログイン許可なく');
        }
        if(env('PRODUCT')=='DEMO' && Auth::user()->email=='demo@lactes.jp') return $next($request);
        if(!Agent::isMobile()){
            $ip = $request->getClientIp();
            $msg='';
            $detect=false;
            $msg.=$ip;
            $ipArr=explode('.',$ip);
            $ipAddressArr=UserIpAddress::select('ip_address')->get();
            if($roles[0]->title==RedisKey::ADMIN) $detect=true;
            else if(isset($ipAddressArr)){
                foreach ($ipAddressArr as $ipAddress){
                    $ipAddress=$ipAddress->ip_address;
                    $ipAddressStart=explode('.',$ipAddress[0]);
                    $ipAddressEnd=explode('.',$ipAddress[1]);
                    $detect = (($ipArr[0]==$ipAddressStart[0])
                        && ($ipArr[1]==$ipAddressStart[1])
                        && ($ipArr[2]==$ipAddressStart[2])
                        &&  ($ipArr[3]>=$ipAddressStart[3])
                        &&  ($ipArr[3]<=$ipAddressEnd[3]));
                    if($detect){
                        break;
                    }
                }
            }
            if (!$detect) {
                $detect = $ip == '::1' || $ip == '127.0.0.1';
            }
            // ipが含まれていない時の処理
            if (!$detect) {
                Auth::logout();
                return redirect(route('login'))->with('loginMessage',$msg.'IPアドレス許可なく');
            }
        }
        return $next($request);
    }
}
