<?php

namespace App\Models;;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

//use Spatie\Activitylog\Traits\LogsActivityInterface;
use Spatie\Activitylog\Traits\LogsActivity;

class EmailTemplate extends Model
{
    use LogsActivity;
    public $table = 'email_templates';

    protected $fillable = [
        'subject',
        'body'
    ];

    /**
     * @param $emailId
     * @return mixed
     */

    public static function getEmailTemplate($emailId)
    {
        return EmailTemplate::where('email_id', $emailId)->first();
    }

    /**
     * @param $emailId
     * @return mixed
     */
    public static function emailVariables($emailId)
    {
        $variables = EmailTemplate::where('email_id', $emailId)->first()->variables;
        return $variables;

    }

    public static function prepareAndSendEmail($emailId,$emailInfoArr,$fieldValues, $throw = false)
    {

        $template  = EmailTemplate::getEmailTemplate($emailId);
        $emailText = $template->body;

        foreach ($fieldValues as $key => $value) {
            $emailText = str_replace('##' . $key . '##', $value, $emailText);
        }

        $setting = AdminSetting::first()->toArray();
        self::setSmtpSettings();

        try {
            Mail::send('admin.email.layout', [
                'body'     => nl2br($emailText),
//                'setting' => $setting,
            ], function ($message) use ($emailInfoArr, $throw, $setting, $template) {
                $message->from($setting['mail_from_address'], $setting['mail_from_name']);
                $message->to($emailInfoArr[0], $emailInfoArr[1])->subject($template['subject']);
            });
        } catch (\Exception $e) {
//            if (env('APP_ENV') !== 'production') {
//                die('Error sending mail: ' . $e->getMessage() . '\nData: ' . json_encode($emailInfo));
//            }
            if ($throw) {
                throw new \Exception('メールサーバーへの接続が失敗しました。担当者とご連絡ください。',403);
            }
        }

    }

    public static function setSmtpSettings()
    {
        $smtpSetting = AdminSetting::first();

        Config::set('mail.driver', $smtpSetting->mail_driver);
        Config::set('mail.host', $smtpSetting->mail_host);
        Config::set('mail.port', $smtpSetting->mail_port);
        Config::set('mail.username', $smtpSetting->mail_username);
        Config::set('mail.password', $smtpSetting->mail_password);
        Config::set('mail.encryption', $smtpSetting->mail_encryption);
        (new MailServiceProvider(app()))->register();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        $user_name="";
        if (Auth::check()){
            $user_name = Auth::user()->name;

        }else{
            $user_name = 'Seeder';
        }

        if ($eventName == 'updated')
        {
            return $user_name.' '.__('updated').' '.__('email template').'<strong>'.$this->email_id.'</strong>'.' '.__('successfully');
        }

        return '';
    }

}
