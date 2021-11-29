<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class EmployeeBase
 *
 * @property int $id
 * @property string $employee_code
 * @property int $user_id
 * @property string|null $icon
 * @property string $name_phonetic
 * @property string $name_roman
 * @property string|null $nationality
 * @property string $sex
 * @property string|null $birthday
 * @property int|null $hire_type_id
 * @property int|null $department_type_id
 * @property int|null $position_type_id
 * @property int $retire_type_id
 * @property string $date_hire
 * @property string|null $date_retire
 * @property int $annual_leave_type
 * @property string|null $family_num
 * @property string|null $remark
 * @property string|null $data_history
 * @property int $modified_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeBase extends Model
{
    const PC_VIEW = 'employee_view';
    const PC_MODIFY = 'employee_modify';
    const MOBILE_AUDIT = 'mobile_audit';
    const MOBILE_MODIFY = 'mobile_modify';
	use SoftDeletes;
    use Encryptable;
    use LogsActivity;

    protected $encryptable = [
        'icon',
        'name',
        'name_phonetic',
        'name_roman',
        'nationality',
        'birthday',
        'date_hire',
        'date_retire',
        'remark',
        'data_history',
    ];
	protected $table = 'employee_base';

	protected $casts = [
		'user_id' => 'int',
		'hire_type_id' => 'int',
		'department_type_id' => 'int',
		'position_type_id' => 'int',
		'retire_type_id' => 'int',
		'annual_leave_type' => 'int',
        'data_history' => 'array'
	];

	protected $fillable = [
		'employee_code',
		'user_id',
		'icon',
		'name_phonetic',
		'name_roman',
		'nationality',
		'sex',
		'birthday',
		'hire_type_id',
		'department_type_id',
		'position_type_id',
		'retire_type_id',
		'date_hire',
		'date_retire',
		'annual_leave_type',
        'family_num',
		'remark',
	];
    protected $mobileModify = [
        'icon',
        'name_phonetic',
        'name_roman',
        'nationality',
        'sex',
        'birthday'
    ];
    public function getFillable(){
        return $this->fillable;
    }
    public function getMobileModify(){
        return $this->mobileModify;
    }
    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function employeeStay()
    {
        return $this->hasOne('App\Models\EmployeeStay','employee_id','id');
    }

    public function employeeEmergencyContact()
    {
        return $this->hasOne('App\Models\EmployeeEmergencyContact','employee_id','id');
    }

    public function employeeContacts()
    {
        return $this->hasOne('App\Models\EmployeeContact','employee_id','id');
    }
    public function employeeBank()
    {
        return $this->hasOne('App\Models\EmployeeBank','employee_id','id');
    }
    public function employeeInsurance()
    {
        return $this->hasOne('App\Models\EmployeeInsurance','employee_id','id');
    }
    public function employeeDependentRelation()
    {
        return $this->hasMany('App\Models\EmployeeDependentRelation','employee_id','id');
    }
    public function departmentType()
    {
        return $this->belongsTo('App\Models\Department','department_type_id','id');
    }
    public function hireType()
    {
        return $this->belongsTo('App\Models\HireType','hire_type_id','id');
    }
    public function positionType()
    {
        return $this->belongsTo('App\Models\PositionType','position_type_id','id');
    }
    public function retireType()
    {
        return $this->belongsTo('App\Models\RetireType','retire_type_id','id');
    }
    public function Leave()
    {
        return $this->hasMany(Leave::class,'employee_base_id','id');
    }
    public function attendance(){
        return $this->hasMany(Attendance::class,'employee_id','id');
    }
    public function employeeAnnualLeave()
    {
        return $this->hasMany('App\Models\EmployeeAnnualLeave','employee_base_id','id');
    }


}
