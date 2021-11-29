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
 * Class AccountsEstimateDetail
 *
 * @property int $id
 * @property int $accounts_estimate_id
 * @property int $sort_index
 * @property string $employee_name
 * @property string $project_name
 * @property string|null $unit_price
 * @property string|null $period
 * @property string|null $remark
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property AccountsEstimate $accounts_estimate
 *
 * @package App\Models
 */
class AccountsEstimateDetail extends Model
{
	use SoftDeletes;
    use Encryptable;

    protected $encryptable = [
        'employee_name',
        'unit_price',
        'total',
        'project_name',
    ];

	protected $table = 'accounts_estimate_details';

	protected $casts = [
		'accounts_estimate_id' => 'int',
		'sort_index' => 'int',
	];

	protected $fillable = [
		'accounts_estimate_id',
		'sort_index',
		'employee_name',
		'project_name',
		'unit_price',
		'period',
		'total',
		'remark'
	];

	public function accounts_estimate()
	{
		return $this->belongsTo(AccountsEstimate::class,'accounts_estimate_id','id');
	}
}
