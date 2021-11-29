<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
/**
 * Class AssetInfo
 *
 * @property int $id
 * @property string|null $manage_code
 * @property string $type
 * @property string|null $maker
 * @property string|null $model
 * @property string|null $serial_number
 * @property Carbon|null $delivery_date
 * @property int $amount
 * @property Carbon|null $bought_date
 * @property string|null $infos
 * @property string|null $storage
 * @property string|null $remark
 * @property string $created_user
 * @property string|null $updated_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class AssetInfo extends Model
{
    const VIEW = 'asset_view';
    const MODIFY = 'asset_modify';
	use LogsActivity;
	use SoftDeletes,SearchableTrait;
	protected $table = 'asset_info';

	protected $dates = [
		'delivery_date',
		'bought_date'
	];

    protected $casts = [
        'amount' => 'int',
    ];

	protected $fillable = [
		'manage_code',
		'type',
        'asset_type_id',
		'maker',
		'model',
		'serial_number',
		'delivery_date',
		'amount',
		'bought_date',
		'infos',
		'storage',
		'remark',
		'created_user',
		'updated_user'
	];

    protected $searchable = [
        'columns' => [
            'asset_info.manage_code' => 10,
            'asset_info.type' => 10,
            'asset_info.maker' => 10,
            'asset_info.model' => 10,
            'asset_info.serial_number' => 10,
            'asset_info.delivery_date' => 10,
            'asset_info.infos' => 10,
            'asset_info.storage' => 10,
            'asset_info.remark' => 10,
            'asset_types.asset_type_name' => 10,
        ],
        'joins' => [
            'asset_types' => ['asset_info.asset_type_id','asset_types.id'],
        ]
    ];

    public function AssetRentalLog()
    {
        return $this->hasMany(AssetRentalLog::class,'asset_info_id','id');
    }

    public function asset_types(){
        return $this->belongsTo(AssetType::class,'asset_type_id','id');
    }
}
