<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OurPositionType
 *
 * @property int $id
 * @property string|null $our_position_type_name
 * @property string|null $our_position_type_arrb_name
 * @property string|null $our_position_type_initials
 *
 * @package App\Models
 */
class OurPositionType extends Model
{
    protected $table = 'our_position_types';
    public $timestamps = false;

    protected $fillable = [
        'our_position_type_name',
        'our_position_type_opp_name',
        'our_position_type_arrb_name',
        'our_position_type_initials'
    ];
}
