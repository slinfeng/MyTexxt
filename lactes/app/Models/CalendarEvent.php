<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalendarEvent
 *
 * @property int $id
 * @property string $cal_event_subject
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property string $cal_event_remark
 * @property string $cal_event_color
 * @property int $cal_event_user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class CalendarEvent extends Model
{
	use SoftDeletes;
	protected $table = 'calendar_events';

	protected $casts = [
		'cal_event_user_id' => 'int'
	];

	protected $dates = [
		'start_at',
		'end_at'
	];

	protected $fillable = [
		'cal_event_subject',
		'start_at',
		'end_at',
		'cal_event_remark',
		'cal_event_color',
		'cal_event_user_id'
	];
}
