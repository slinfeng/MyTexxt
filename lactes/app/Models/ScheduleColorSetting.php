<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ScheduleColorSetting
 *
 * @property int $id
 * @property string $name
 * @property int $color_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class ScheduleColorSetting extends Model
{
	use SoftDeletes;
	protected $table = 'schedule_color_setting';

	protected $casts = [
		'color_id' => 'int',
		'order_num' => 'int'
	];

	protected $fillable = [
		'name',
		'color_id',
		'order_num'
	];
    public function ScheduleColorType(){
        return $this->belongsTo(ScheduleColorType::class,'color_id','id');
    }
}
