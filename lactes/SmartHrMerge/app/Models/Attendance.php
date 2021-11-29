<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

//use App\Models\User;
/**
 * Class Attendance
 *
 * @property int $id
 * @property int $employee_id
 * @property string $year_and_month
 * @property int $file_id
 * @property int|null $working_time
 * @property int|null $transportation_expense
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @package App\Models
 */
class Attendance extends Model
{
    const PC_VIEW = 'attendance_view';
    const PC_MODIFY = 'attendance_modify';
    const MOBILE_AUDIT = 'mobile_audit';
    const MOBILE_MODIFY = 'mobile_modify';
    use LogsActivity;
	use SoftDeletes;
	protected $table = 'attendance';

	protected $casts = [
		'employee_id' => 'int',
		'file_id' => 'int',
        'working_time' => 'float',
	];

	protected $fillable = [
		'year_and_month',
        'employee_id',
        'file_id',
		'working_time',
        'transportation_expense',
		'status',
	];

	public function employee(){
	    return $this->belongsTo(EmployeeBase::class,'employee_id','id');
    }

    public function file(){
	    return $this->hasOne(File::class,'id','file_id');
    }
}
