<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class PositionType
 *
 * @property int $id
 * @property string|null $position_type
 * @property string|null $position_type_name
 * @package App\Models
 */
class PositionType extends Model
{
	protected $table = 'position_types';
	public $timestamps = false;
    use LogsActivity;

	protected $fillable = [
		'position_type',
        'position_type_name'
	];
    public function employee_base(){
        return $this -> hasMany('employee_base','position_type_id','id');
    }
}
