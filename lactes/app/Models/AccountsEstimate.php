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
 * Class AccountsEstimate
 *
 * @property int $id
 * @property int $client_id
 * @property int $bank_account_id
 * @property int $request_setting_id
 * @property Carbon|null $created_date
 * @property string $est_manage_code
 * @property string $project_name_or_file_name
 * @property int|null $our_position_type
 * @property int|null $file_id
 * @property string|null $work_place
 * @property string|null $acceptance_place
 * @property string|null $payment_contract
 * @property string|null $period
 * @property string|null $estimate_subtotal
 * @property string|null $estimate_total
 * @property string|null $remark
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class AccountsEstimate extends Model
{
    const SETTING_ID = 2;
    const CODE_FIELD = 'est_manage_code';
    const DETAIL_TABLE = 'accounts_estimate_detail';
    const VIEW = 'estimate_view';
    const MODIFY = 'estimate_modify';
    use SoftDeletes;
    use Encryptable,LogsActivity;

    protected $encryptable = [
        'estimate_subtotal',
        'estimate_total',
        'cname',
        'project_name_or_file_name',
        'remark',
    ];
	protected $table = 'accounts_estimates';

	protected $casts = [
		'client_id' => 'int',
		'file_id' => 'int',
		'bank_account_id' => 'int',
		'request_setting_id ' => 'int',
		'our_position_type ' => 'int',
		'file_format_type ' => 'int',
		'calc_type ' => 'int',
	];

	protected $fillable = [
		'client_id',
		'cname',
        'official_name',
		'bank_account_id',
		'request_setting_id',
		'created_date',
		'est_manage_code',
		'project_name_or_file_name',
		'our_position_type',
		'file_format_type',
        'calc_type',
		'file_id',
        'work_place',
        'acceptance_place',
        'payment_contract',
        'period',
		'estimate_subtotal',
		'estimate_total',
		'remark',
		'title_a',
		'content_a',
	];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function accounts_estimate_detail()
    {
        return $this->hasMany(AccountsEstimateDetail::class,'accounts_estimate_id','id');
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
