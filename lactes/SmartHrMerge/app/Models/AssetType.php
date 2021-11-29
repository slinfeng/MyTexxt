<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class AssetType
 *
 * @property int $id
 * @property string|null $asset_type_code
 * @property string|null $asset_type_name
 *
 * @package App\Models
 */
class AssetType extends Model
{
    use LogsActivity;
	protected $table = 'asset_types';
	public $timestamps = false;

	protected $fillable = [
		'asset_type_code',
		'asset_type_name'
	];

    public function asset_info()
    {
        return $this->hasMany(AssetInfo::class,'asset_type_id','id');
    }
}
