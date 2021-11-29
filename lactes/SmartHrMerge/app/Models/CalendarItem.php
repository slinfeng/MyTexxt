<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalendarItem
 *
 * @property int $id
 * @property string $cal_item_name
 * @property int $cal_item_user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class CalendarItem extends Model
{
	use SoftDeletes;
	protected $table = 'calendar_items';

	protected $casts = [
		'cal_item_user_id' => 'int'
	];

	protected $fillable = [
		'cal_item_name',
		'cal_item_user_id'
	];
}
