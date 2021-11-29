<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ScheduleDetail
 *
 * @property int $id
 * @property int $user_id
 * @property int $member_id
 * @property string|null $title
 * @property string|null $remark
 * @property int $color_id
 * @property Carbon $schedule_date
 * @property string $schedule_time
 * @property int $private_type
 * @property int $anonymous_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ScheduleDetail extends Model
{
	use SoftDeletes;
	protected $table = 'schedule_detail';

	protected $casts = [
		'user_id' => 'int',
		'member_id' => 'int',
		'color_id' => 'int',
		'private_type' => 'int',
		'anonymous_type' => 'int'
	];

	protected $dates = [
//		'schedule_date'
	];

	protected $fillable = [
		'user_id',
		'member_id',
		'title',
		'remark',
		'color_id',
		'schedule_date',
		'schedule_time',
		'private_type',
		'anonymous_type'
	];
    public function ScheduleColorType(){
        return $this->belongsTo(ScheduleColorType::class,'color_id','id');
    }
    public function ScheduleMemberSetting(){
        return $this->belongsTo(ScheduleMemberSetting::class,'member_id','id');
    }
    public function User(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
