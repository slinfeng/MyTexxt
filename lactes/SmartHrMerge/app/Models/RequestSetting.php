<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Casts\CheckBoxToInt;
use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestSetting
 *
 * @property int $id
 * @property int $use_init_val
 * @property int $use_seal
 * @property string $tax_rate
 * @property string $remark_start
 * @property string $remark_end
 * @property string $company_info
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class RequestSetting extends Model
{
	protected $table = 'request_setting';
	protected $casts = [
		'use_init_val' => CheckBoxToInt::class,
		'use_seal' => CheckBoxToInt::class
	];

	protected $fillable = [
		'use_init_val',
		'use_seal',
		'tax_rate',
		'remark_start',
		'remark_end',
		'company_info',
	];
}
