<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class AdminSetting extends Model
{
    const VIEW = 'setting_view';
    const MODIFY = 'setting_modify';
    use LogsActivity;
	protected $table = 'admin_settings';

	protected $casts = [
		'notification' => 'int',
		'time_slot_length' => 'int',
		'verification' => 'int',
		'offline_payment' => 'int',
		'stipe_status' => 'int',
		'paypal_status' => 'int',
		'razor_status' => 'int',
        'receiver_arr' => 'array'
	];

	protected $fillable = [
        'company_short_name',
		'company_name',
        'post_code',
		'company_detail_info',
		'phone_no',
		'email',
		'address',
        'representative_name',
        'fax',
        'closing_month',
        'url',
        'site_name',
        'name',
        'logo',
		'pp',
		'notification',
		'currency_symbol',
		'currency',
        'facebook_client_id',
        'facebook_client_secret',
        'google_client_id',
        'google_client_secret',
        'twitter_client_id',
        'twitter_client_secret',
        'email_notification',
        'recaptcha',
        'remember_me',
        'forget_password',
        'allow_register',
        'email_confirmation',
        'custom_field_on_register',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_name',
        'mail_from_address',
        'receiver_arr',
        'recaptcha_public_key',
        'recaptcha_private_key'
	];
}
