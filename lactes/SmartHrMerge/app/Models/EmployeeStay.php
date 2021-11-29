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
 * Class EmployeeStay
 *
 * @property int $id
 * @property int $employee_id
 * @property string|null $residence_card_num
 * @property int|null $residence_type
 * @property string|null $residence_deadline
 * @property string|null $residence_card_front
 * @property string|null $residence_card_back
 * @property string|null $personal_num
 * @property string|null $data_history
 * @property string $created_user
 * @property string|null $updated_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeStay extends Model
{
	use SoftDeletes;
    use Encryptable;
    use LogsActivity;
    protected $primaryKey='id';

    protected $encryptable = [
        'residence_card_num',
        'residence_deadline',
        'residence_card_front',
        'residence_card_back',
        'personal_num',
        'data_history',
    ];
	protected $table = 'employee_stay';

	protected $casts = [
		'employee_id' => 'int',
		'residence_type' => 'int',
        'data_history' => 'array'
	];

	protected $fillable = [
		'residence_card_num',
		'residence_type',
		'residence_deadline',
		'residence_card_front',
		'residence_card_back',
		'personal_num'
	];

	protected $mobileModify = [
        'residence_card_num',
        'residence_type',
        'residence_deadline',
        'residence_card_front',
        'residence_card_back',
        'personal_num'
    ];
    public function getFillable(){
        return $this->fillable;
    }
    public function getMobileModify(){
        return $this->mobileModify;
    }

    public function employeeBase(){
        return $this->belongsTo('App\Models\employeeBase','employee_id','id');
    }
    public function residenceType()
    {
        return $this->belongsTo('App\Models\residenceType','residence_type','id');
    }
}
