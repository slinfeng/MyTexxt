<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ScheduleMemberSetting
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $order_num
 * @property string $name
 * @property string $display_name
 * @property int $reserve_type
 * @property int $reserve_name_type
 * @property int $constraint_type
 * @property int $scale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ScheduleMemberSetting extends Model
{
	use SoftDeletes;
	protected $table = 'schedule_member_setting';

	protected $casts = [
		'user_id' => 'int',
		'order_num' => 'int',
		'reserve_type' => 'int',
		'reserve_name_type' => 'int',
		'constraint_type' => 'int',
		'scale' => 'int'
	];

	protected $fillable = [
		'user_id',
		'order_num',
		'name',
		'display_name',
		'reserve_type',
		'reserve_name_type',
		'constraint_type',
		'scale'
	];
    public function User(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function ScheduleGroup()
    {
        return $this->belongsToMany(ScheduleGroup::class);
    }
}
