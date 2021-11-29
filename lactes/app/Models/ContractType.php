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
 * @property string|null $contract_type_code
 * @property string|null $contract_type_name
 *
 * @package App\Models
 */
class ContractType extends Model
{
    protected $table = 'contract_types';
    public $timestamps = false;

    protected $fillable = [
        'contract_type_name'
    ];
}
