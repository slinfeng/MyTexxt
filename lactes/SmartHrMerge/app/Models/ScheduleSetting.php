<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ScheduleSetting
 *
 * @property int $id
 * @property int $reservation_restrictions_type
 * @property int $anonymous_type
 * @property int $display_reservation_type
 * @property int $duplicate_reservation_type
 * @property string $display_time
 * @property int $drag_accuracy_type
 * @property int $palette_type
 * @property int $color_id
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class ScheduleSetting extends Model
{
	protected $table = 'schedule_setting';
	public $timestamps = false;

	protected $casts = [
		'reservation_restrictions_type' => 'int',
		'anonymous_type' => 'int',
		'display_reservation_type' => 'int',
		'duplicate_reservation_type' => 'int',
		'drag_accuracy_type' => 'int',
		'palette_type' => 'int'
	];

	protected $fillable = [
		'reservation_restrictions_type',
		'anonymous_type',
		'display_reservation_type',
		'duplicate_reservation_type',
		'display_time',
		'drag_accuracy_type',
		'palette_type',
        'color_id'
	];
}
