<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Casts\Amount;
use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AccountsOrderDetail
 *
 * @property int $id
 * @property int $accounts_order_id
 * @property string $project_content
 * @property string $employee_name
 * @property string|null $unit_price
 * @property string $payment_contract
 * @property string|null $work_place
 * @property int|null $work_place_val
 * @property string $delivery_files
 * @property string|null $acceptance_place
 * @property int|null $acceptance_place_val
 * @property string|null $traffic_expence_paid_by
 * @property int|null $traffic_expence_paid_by_val
 * @property string $outlay
 * @property string|null $remark
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property AccountsOrder $accounts_order
 *
 * @package App\Models
 */
class AccountsOrderDetail extends Model
{
	use SoftDeletes;
    use Encryptable;

    protected $encryptable = [
        'employee_name',
        'unit_price',
        'project_content',
        'remark',
    ];
	protected $table = 'accounts_order_details';

	protected $casts = [
		'accounts_order_id' => 'int',
		'payment_contract' => 'array',
		'traffic_expence_paid_by_val' => 'int',
		'work_place_val' => 'int',
		'acceptance_place_val' => 'int'
	];

	protected $fillable = [
		'accounts_order_id',
        'custom_title',
        'custom_content',
		'project_content',
		'employee_name',
		'unit_price',
		'payment_contract',
		'work_place',
		'work_place_val',
		'delivery_files',
		'acceptance_place',
		'acceptance_place_val',
		'traffic_expence_paid_by',
		'traffic_expence_paid_by_val',
		'outlay',
		'remark'
	];

	public function accounts_order()
	{
		return $this->belongsTo(AccountsOrder::class);
	}
}
