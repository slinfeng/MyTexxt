<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Casts\Amount;
use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AccountsInvoiceDetail
 *
 * @property int $id
 * @property int $accounts_invoice_id
 * @property int $sort_index
 * @property string $employee_name
 * @property string|null $period
 * @property string $detail_content
 * @property string|null $unit_price
 * @property bool|null $is_outlay
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property AccountsInvoice $accounts_invoice
 *
 * @package App\Models
 */
class AccountsInvoiceDetail extends Model
{
	use SoftDeletes;
    use Encryptable;

    protected $encryptable = [
        'employee_name',
        'detail_content',
        'unit_price',
    ];
	protected $table = 'accounts_invoice_details';

	protected $casts = [
		'accounts_invoice_id' => 'int',
		'sort_index' => 'int',
//		'unit_price' => Amount::class,
		'is_outlay' => 'bool'
	];

	protected $fillable = [
		'accounts_invoice_id',
		'sort_index',
		'employee_name',
		'period',
		'detail_content',
		'unit_price',
		'is_outlay'
	];

	public function accounts_invoice()
	{
		return $this->belongsTo(AccountsInvoice::class,'accounts_invoice_id','id');
	}
}
