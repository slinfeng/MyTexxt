<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScheduleGroup
 *
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class ScheduleGroup extends Model
{
	protected $table = 'schedule_group';

	protected $fillable = [
		'name'
	];
    public function ScheduleMemberSetting()
    {
        return $this->belongsToMany(ScheduleMemberSetting::class);
    }
}
