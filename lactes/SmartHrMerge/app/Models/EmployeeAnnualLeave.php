<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class EmployeeAnnualLeave
 *
 * @property int $id
 * @property int $employee_base_id
 * @property int $year
 * @property int $days
 * @property int $has_days
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeAnnualLeave extends Model
{
	use SoftDeletes;
    use LogsActivity;

	protected $table = 'employee_annual_leave';

	protected $casts = [
		'employee_base_id' => 'int',
		'year' => 'int',
		'days' => 'int',
        'has_days' => 'int',
	];

	protected $fillable = [
        'employee_base_id',
        'year',
        'days',
        'has_days'
	];

    public function EmployeeBase()
    {
        return $this->belongsTo('App\Models\EmployeeBase', 'employee_base_id', 'id');
    }


}
