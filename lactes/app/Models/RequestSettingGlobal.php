<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class RequestSettingGlobal
 *
 * @property int $id
 * @property int $calendar_search_unit
 * @property string|null $symbol
 * @property int $position_type
 * @property int $create_month
 * @property int|null $create_day
 * @property int $use_init_val
 * @property int $use_seal
 * @property string|null $remark_start
 * @property string|null $remark_end
 * @property string|null $company_info
 * @property string|null $seal_file
 * @property string|null $project_name
 * @property int $tax_type
 * @property string|null $payment_contract
 * @property string|null $work_place
 * @property int $work_place_val
 * @property int $period
 * @property String $acceptance_place
 * @property int $acceptance_place_val
 * @property string|null $custom_title
 * @property string|null $custom_content
 * @property string $print_num
 * @property int|null $bank_account_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class RequestSettingGlobal extends Model
{

	protected $table = 'request_setting_global';
    const VIEW = 'requestsetting_view';
    const MODIFY = 'requestsetting_modify';
    use LogsActivity;

	protected $casts = [
		'calendar_search_unit' => 'int',
		'position_type' => 'int',
		'create_month' => 'int',
		'create_day' => 'int',
		'use_init_val' => 'int',
		'use_seal' => 'int',
		'tax_type' => 'int',
		'work_place_val' => 'int',
		'period' => 'int',
		'acceptance_place_val' => 'int',
		'bank_account_id' => 'int',
		'payment_contract' => 'array',
	];

	protected $fillable = [
		'calendar_search_unit',
		'symbol',
		'position_type',
		'create_month',
		'create_day',
		'use_init_val',
		'use_seal',
		'remark_start',
		'remark_end',
		'company_info',
		'seal_file',
		'project_name',
		'tax_type',
		'payment_contract',
		'work_place',
		'work_place_val',
		'period',
		'acceptance_place',
		'acceptance_place_val',
		'print_num',
		'bank_account_id'
	];
}
