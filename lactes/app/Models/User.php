<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Notifications\PasswordResetNotification;
use App\Traits\Encryptable;
use App\Traits\HasInvoices;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class User
 *
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $user_view
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    const VIEW = 'user_view';
    const MODIFY = 'user_modify';
    use HasFactory, Notifiable,HasInvoices;
    use Encryptable;
    use LogsActivity;
	protected $table = 'users';
    protected $encryptable = [
        'name',
//        'email',
    ];
	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
        'client_id',
		'name',
		'email',
		'password',
        'user_view',
        'user_view_attendance',
        'home_chart_type',
	];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_view_attendance' => 'int',
        'home_chart_type' => 'array',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function activity_log(){
        return $this->hasMany(ActivityLog::class,'causer_id', 'id');
    }
    public function setPasswordAttribute($value)
    {
        if (strlen($value) != 60) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    public function Employee()
    {
        return $this->hasOne('App\Models\EmployeeBase', 'user_id', 'id');
    }

    public function schedule_member_setting()
    {
        return $this->hasOne('App\Models\ScheduleMemberSetting', 'user_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class,'user_id','id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}
