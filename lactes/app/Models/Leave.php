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
 * Class Leave
 *
 * @property int $id
 * @property int $employee_base_id
 * @property Carbon $leave_from
 * @property Carbon|null $leave_to
 * @property float $days_of_leave
 * @property string|null $reason
 * @property int|null $status
 * @property int|null $approved_by_user_id
 * @property string|null $memo
 * @property float $annual_leave_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Leave extends Model
{
    const PC_VIEW = 'leave_view';
    const PC_MODIFY = 'leave_modify';
    const MOBILE_AUDIT = 'mobile_audit';
    const MOBILE_MODIFY = 'mobile_modify';
	use SoftDeletes;
    use LogsActivity;
	protected $table = 'leaves';

	protected $casts = [
		'employee_base_id' => 'int',
		'days_of_leave' => 'float',
        'annual_leave_on' => 'float',
		'status' => 'int',
		'approved_by_user_id' => 'int'
	];


	protected $fillable = [
		'employee_base_id',
		'leave_from',
		'leave_to',
		'days_of_leave',
		'reason',
		'status',
		'approved_by_user_id',
        'annual_leave_on',
		'memo'
	];

    public function User()
    {
        return $this->hasone('App\Models\User', 'id', 'approved_by_user_id');
    }

    public function EmployeeBase()
    {
        return $this->belongsTo(EmployeeBase::class, 'employee_base_id', 'id');
    }
}
