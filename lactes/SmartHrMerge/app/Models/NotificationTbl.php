<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationTbl
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int $sender_id
 * @property string $title
 * @property string $sub_title
 * @property string $message
 * @property int|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class NotificationTbl extends Model
{
	protected $table = 'notification_tbl';

	protected $casts = [
		'user_id' => 'int',
		'sender_id' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'sender_id',
		'title',
		'sub_title',
		'message',
		'status'
	];
}
