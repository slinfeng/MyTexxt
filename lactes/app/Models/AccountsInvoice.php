<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Casts\Amount;
use App\Traits\CastFormatTrait;
use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class AccountsInvoice
 *
 * @property int $id
 * @property int $client_id
 * @property int|null $bank_account_id
 * @property Carbon|null $created_date
 * @property string $invoice_manage_code
 * @property string $project_name_or_file_name
 * @property int|null $our_position_type
 * @property int|null $file_format_type
 * @property int|null $file_id
 * @property string|null $invoice_total
 * @property string|null $period
 * @property string $work_place
 * @property string $payment_contract
 * @property Carbon|null $request_pay_date
 * @property Carbon|null $paid_date
 * @property string|null $paid_total
 * @property int|null $status
 * @property int|null $approved_by_user_id
 * @property string|null $remark
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property File|null $file
 * @property Collection|AccountsInvoiceDetail[] $accounts_invoice_details
 *
 * @package App\Models
 */
class AccountsInvoice extends Model
{
    const SETTING_ID = 5;
    const VIEW = 'invoice_view';
    const MODIFY = 'invoice_modify';
    const SELF_MODIFY = 'invoice_self_modify';
    const CODE_FIELD = 'invoice_manage_code';
    const POSITION_IN = 2;
    const POSITION_OUT = 1;
    use SoftDeletes, CastFormatTrait, SearchableTrait;
    use Encryptable;
    use LogsActivity;

    protected $encryptable = [
        'project_name_or_file_name',
        'invoice_total',
        'paid_total',
        'cname',
    ];
    protected $table = 'accounts_invoices';

    protected $casts = [
        'client_id' => 'int',
        'bank_account_id' => 'int',
        'request_setting_id' => 'int',
        'our_position_type' => 'int',
        'file_format_type' => 'int',
        'file_id' => 'int',
        'contract_type' => 'int',
        'calc_type' => 'int',
        'status' => 'int',
        'approved_by_user_id' => 'int',
        'new_notice' => 'int'
    ];

    protected $fillable = [
        'client_id',
        'cname',
        'official_name',
        'bank_account_id',
        'request_setting_id',
        'created_date',
        'invoice_manage_code',
        'project_name_or_file_name',
        'our_position_type',
        'file_format_type',
        'file_id',
        'invoice_total',
        'contract_type',
        'contract_type_other_remark',
        'period',
        'work_place',
        'payment_contract',
        'pay_deadline',
        'paid_total',
        'calc_type',
        'status',
        'approved_by_user_id',
        'remark',
        'new_notice',
        'created_by_client_id',
    ];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id', 'id');
    }

    public function our_position_types(){
        return $this->belongsTo(OurPositionType::class,'our_position_type','id');
    }

    public function accounts_invoice_details()
    {
        return $this->hasMany(AccountsInvoiceDetail::class, 'accounts_invoice_id', 'id');
    }

    public function request_setting()
    {
        return $this->hasOne(RequestSetting::class, 'id', 'request_setting_id');
    }
}
