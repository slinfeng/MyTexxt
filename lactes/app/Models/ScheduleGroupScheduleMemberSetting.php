<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScheduleGroupScheduleMemberSetting
 * 
 * @property int $id
 * @property int $schedule_group_id
 * @property int $schedule_member_setting_id
 *
 * @package App\Models
 */
class ScheduleGroupScheduleMemberSetting extends Model
{
	protected $table = 'schedule_group_schedule_member_setting';
	public $timestamps = false;

	protected $casts = [
		'schedule_group_id' => 'int',
		'schedule_member_setting_id' => 'int'
	];

	protected $fillable = [
		'schedule_group_id',
		'schedule_member_setting_id'
	];
}
