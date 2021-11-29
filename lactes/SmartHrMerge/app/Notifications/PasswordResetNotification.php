<?php


namespace App\Notifications;


use App\Classes\Reply;
use App\Models\EmailTemplate;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetNotification extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return string
     * @throws \Exception
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if (static::$createUrlCallback) {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        }
        $emailInfo = [$notifiable->getEmailForPasswordReset(),$notifiable->name];
        $fieldValues = ['URL'=>$url];
        return EmailTemplate::prepareAndSendEmail('FORGET_PASSWORD',$emailInfo,$fieldValues,true);
    }
}
