<?php

namespace App\Traits;

use App\Constants\RedisKey;
use App\Models\Notification;

trait NotifyTrait{
    private function createNotification($user_id,$msg,$action=RedisKey::NOTIFY_DELETED){
        $msg = str_replace(':attribute',$msg,RedisKey::MSG_NOTIFY[$action]);
        $notification = new Notification([
            'user_id'=>$user_id,
            'notify_msg'=>$msg,
        ]);
        $notification->save();
        return $notification;
    }
    private function getNotifications($user_id){
        $count = Notification::where('user_id',$user_id)->where('notify_status',0)->count();
        if($count<10){
            $notifications = Notification::where('user_id',$user_id)->take(10);
        }else{
            $notifications = Notification::where('user_id',$user_id)->where('notify_status',0);
        }
        return $notifications->orderBy('id','desc');
    }
    private function changeNotificationStatus($notifications){
        $notifications->update(['notify_status'=>1]);
    }
}
