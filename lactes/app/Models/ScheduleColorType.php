<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScheduleColorType
 * 
 * @property int $id
 * @property string $css_name
 *
 * @package App\Models
 */
class ScheduleColorType extends Model
{
	protected $table = 'schedule_color_type';
	public $timestamps = false;

	protected $fillable = [
		'css_name'
	];
}
