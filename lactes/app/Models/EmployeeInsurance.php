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
 * Class EmployeeInsurance
 *
 * @property int $id
 * @property int $employee_id
 * @property int $social_insurance
 * @property string|null $basic_pension_number
 * @property string|null $sign
 * @property string|null $organize_number
 * @property string|null $social_start_date
 * @property string|null $social_end_date
 * @property string|null $base_amount
 * @property int $employment_insurance
// * @property string|null $office_number
 * @property string|null $Insured_number
 * @property string|null $employment_start_date
 * @property string|null $employment_end_date
 * @property int $national_health_insurance
 * @property int $national_pension_insurance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeInsurance extends Model
{
	use SoftDeletes;
    use Encryptable;
    use LogsActivity;

    protected $primaryKey='id';

    protected $encryptable = [
        'basic_pension_number',
        'sign',
        'organize_number',
        'social_start_date',
        'social_end_date',
        'base_amount',
        'insured_number',
        'employment_start_date',
        'employment_end_date',
    ];
	protected $table = 'employee_insurance';

	protected $casts = [
		'employee_id' => 'int',
		'social_insurance' => 'int',
		'employment_insurance' => 'int',
		'national_health_insurance' => 'int',
		'national_pension_insurance' => 'int',
	];

	protected $fillable = [
		'social_insurance',
		'basic_pension_number',
		'sign',
		'organize_number',
		'social_start_date',
		'social_end_date',
		'base_amount',
		'employment_insurance',
		'insured_number',
		'employment_start_date',
		'employment_end_date',
		'national_health_insurance',
		'national_pension_insurance',
	];
    public function getFillable(){
        return $this->fillable;
    }
    public function employee_base(){
        return $this -> belongsTo('employee_base','employee_id','id');
    }
}
