<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AssetType
 *
 * @property int $id
 * @property string|null $asset_type_code
 * @property string|null $asset_type_name
 *
 * @package App\Models
 */
class AssetSetting extends Model
{
    const VIEW = 'assetsetting_view';
    const MODIFY = 'assetsetting_modify';
    protected $table = 'asset_settings';
    public $timestamps = false;
    protected $casts = [
        'id'=>'int',
        'search_mode'=>'int',
        'use_init_val'=>'int',
        'use_seal'=>'int',
    ];
    protected $fillable = [
        'search_mode',
        'document_end',
        'use_init_val',
        'use_seal',
        'company_info',
        'seal_file',
    ];
}
