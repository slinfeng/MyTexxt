<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class AssetRentalLog
 *
 * @property int $id
 * @property int $asset_info_id
 * @property int $status
 * @property Carbon $loan_or_return_date
 * @property string $user
 * @property string $responsible_person
 * @property string|null $remark
 * @property string $created_user
 * @property string|null $updated_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class AssetRentalLog extends Model
{
    use LogsActivity;
	use SoftDeletes;
	protected $table = 'asset_rental_logs';

	protected $casts = [
		'asset_info_id' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'loan_or_return_date'
	];

	protected $fillable = [
		'asset_info_id',
		'status',
		'loan_or_return_date',
		'user',
		'responsible_person',
		'remark',
		'created_user',
		'updated_user'
	];
    public function AssetInfo()
    {
        return $this->belongsTo(AssetInfo::class,'asset_info_id','id');
    }
    public function user_responsible(){
        return $this->belongsTo(User::class,'responsible_person','id');
    }
}
