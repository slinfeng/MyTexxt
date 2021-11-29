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
 * Class EmployeeBank
 *
 * @property int $id
 * @property int $employee_id
 * @property string|null $bank_name
 * @property string|null $branch_name
 * @property string|null $branch_code
 * @property int|null $account_type
 * @property string|null $account_name
 * @property string|null $account_num
 * @property string|null $data_history
 * @property string $created_user
 * @property string|null $updated_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeBank extends Model
{
    use SoftDeletes;
    use Encryptable;use LogsActivity;

    protected $encryptable = [
        'bank_name',
        'branch_name',
        'branch_code',
        'account_name',
        'account_num',
        'data_history',

    ];
    protected $table = 'employee_bank';

    protected $casts = [
        'employee_id' => 'int',
        'data_history' => 'array'
    ];

    protected $fillable = [
        'bank_name',
        'branch_name',
        'branch_code',
        'account_type',
        'account_name',
        'account_num'
    ];
    protected $mobileModify = [
        'bank_name',
        'branch_name',
        'branch_code',
        'account_type',
        'account_name',
        'account_num'
    ];
    public function getFillable(){
        return $this->fillable;
    }
    public function getMobileModify(){
        return $this->mobileModify;
    }
    public function employee_base(){
        return $this -> belongsTo('employee_base','employee_id','id');
    }
}
