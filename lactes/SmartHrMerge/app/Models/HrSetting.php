<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class HrSetting
 *
 * @property int $id
 * @property string|null $office_number
 * @property int $cumulative_years
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class HrSetting extends Model
{
    const VIEW = 'hrsetting_view';
    const MODIFY = 'hrsetting_modify';
	protected $table = 'hr_settings';
    use Encryptable;
    use LogsActivity;
    protected $encryptable = [
        'office_number'
    ];

	protected $fillable = [
		'office_number',
		'calculate_work_years',
		'calculate_work_months',
        'cloud_attendance_period',
		'first_year_leave',
		'cumulative_years',
        'grow_leave',
        'max_annual_leave'
	];
    protected $casts = [
        'calculate_work_years' => 'int',
        'calculate_work_months' => 'int',
        'cloud_attendance_period' => 'int',
        'first_year_leave' => 'int',
        'cumulative_years' => 'int',
        'grow_leave' => 'int',
    ];
}
