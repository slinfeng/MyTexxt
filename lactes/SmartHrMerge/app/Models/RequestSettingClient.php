<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestSettingClient
 *
 * @property int $id
 * @property int $client_id
 * @property string|null $bank_name
 * @property string|null $branch_name
 * @property string|null $branch_code
 * @property int $account_type
 * @property string|null $account_name
 * @property string|null $account_num
 * @property int|null $use_init_val
 * @property string|null $company_info
 * @property string|null $remark_start
 * @property string|null $remark_end
 * @property int $use_seal
 * @property string|null $seal_file
 * @property string|null $project_name
 * @property int $contract_type
 * @property string|null $contract_type_other_remark
 * @property int $create_month
 * @property int|null $create_day
 * @property int $period
 * @property string|null $work_place
 * @property string|null $payment_contract
 * @property int $request_pay_month
 * @property int|null $request_pay_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class RequestSettingClient extends Model
{
	use SoftDeletes;
    use Encryptable;
	protected $table = 'request_setting_clients';

    protected $encryptable = [
        'bank_name',
        'branch_name',
        'branch_code',
        'account_name',
        'account_num',
    ];

	protected $casts = [
		'client_id' => 'int',
		'account_type' => 'int',
		'use_init_val' => 'int',
		'use_seal' => 'int',
		'contract_type' => 'int',
		'create_month' => 'int',
		'create_day' => 'int',
		'period' => 'int',
		'request_pay_month' => 'int',
		'request_pay_date' => 'int'
	];

	protected $fillable = [
		'client_id',
		'bank_name',
		'branch_name',
		'branch_code',
		'account_type',
		'account_name',
		'account_num',
		'use_init_val',
		'company_info',
		'remark_start',
		'remark_end',
		'use_seal',
		'seal_file',
		'project_name',
		'contract_type',
		'contract_type_other_remark',
		'create_month',
		'create_day',
		'period',
		'work_place',
		'payment_contract',
		'request_pay_month',
		'request_pay_date'
	];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function BankAccountType(){
        return $this->belongsTo(BankAccountType::class,'account_type','id');
    }
}
