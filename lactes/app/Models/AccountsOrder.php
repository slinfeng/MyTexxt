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
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class AccountsOrder
 *
 * @property int $id
 * @property int $client_id
 * @property Carbon|null $created_date
 * @property string|null $period
 * @property string|null $order_manage_code
 * @property string $project_manage_code
 * @property string $project_name_or_file_name
 * @property string|null $our_position_type
 * @property int|null $file_id
 * @property string|null $estimate_subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class AccountsOrder extends Model
{
    const SETTING_ID = 3;
    const VIEW = 'expense_view';
    const MODIFY = 'expense_modify';
    const CODE_FIELD = 'project_manage_code';
    const DETAIL_TABLE = 'accounts_order_detail';
    use SoftDeletes,SearchableTrait;
    use Encryptable;
    use LogsActivity;

    protected $encryptable = [
        'estimate_subtotal',
        'cname',
    ];
	protected $table = 'accounts_orders';

	protected $casts = [
		'client_id' => 'int',
		'request_setting_id' => 'int',
		'file_id' => 'int',
		'file_format_type' => 'int',
        'our_position_type' => 'int',
        'new_notice' => 'int',
        'expense_status' => 'int',
	];

	protected $fillable = [
		'client_id',
		'cname',
        'official_name',
		'request_setting_id',
		'created_date',
        'period',
		'project_manage_code',
		'project_name_or_file_name',
		'our_position_type',
		'file_format_type',
		'file_id',
		'estimate_subtotal',
        'expense_status',
        'new_notice',
	];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function accounts_order_detail()
    {
        return $this->hasOne(AccountsOrderDetail::class,'accounts_order_id','id');
    }
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function OurPositionType()
    {
        return $this->hasOne('App\Models\OurPositionType', 'id', 'our_position_type');
    }
    public function request_setting()
    {
        return $this->hasOne(RequestSetting::class, 'id', 'request_setting_id');
    }
}
