<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 *
 * @property int $id
 * @property int $user_id
 * @property string $notify_msg
 * @property string $notify_status
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notifications';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'notify_msg',
		'notify_status'
	];

	public function user(){
	    return $this->belongsTo(User::class,'user_id','id');
    }
}
