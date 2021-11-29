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
 * Class RequestSettingExtra
 *
 * @property int $id
 * @property int $font_family_type_id
 * @property string|null $tax_rate
 * @property string $currency
 * @property int $client_sort_type
 * @property int $our_position_type
 * @property string|null $estimate_remark
 * @property string|null $project_content
 * @property string|null $expense_delivery_files
 * @property int $expense_traffic_expence_paid_by_val
 * @property string|null $expense_traffic_expence_paid_by
 * @property string|null $expense_outlay
 * @property string|null $expense_remark
 * @property int $contract_type
 * @property string|null $contract_type_other_remark
 * @property int $request_pay_month
 * @property int|null $request_pay_date
 * @property string|null $vertical_distance
 * @property string|null $horizontal_distance
 * @property string|null $local_ip_addr
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class RequestSettingExtra extends Model
{
    use Encryptable;
    use LogsActivity;
	protected $table = 'request_setting_extra';

	protected $casts = [
		'font_family_type_id' => 'int',
        'cloud_request_period' => 'int',
		'client_sort_type' => 'int',
		'expense_traffic_expence_paid_by_val' => 'int',
		'contract_type' => 'int',
		'request_pay_month' => 'int',
		'request_pay_date' => 'int'
	];

    protected $encryptable = [
//        'local_ip_addr',
        'estimate_remark',
        'expense_remark',
    ];

    protected $fillable = [
        'font_family_type_id',
        'cloud_request_period',
		'tax_rate',
		'currency',
		'estimate_remark',
		'project_content',
		'expense_delivery_files',
		'expense_traffic_expence_paid_by_val',
		'expense_traffic_expence_paid_by',
		'expense_outlay',
		'expense_remark',
		'contract_type',
		'contract_type_other_remark',
		'request_pay_month',
		'request_pay_date',
		'vertical_distance',
		'horizontal_distance',
        'local_ip_addr'
	];

	public function font_family_type(){
	    return $this->belongsTo(FontFamilyType::class,'font_family_type_id','id');
    }
}
