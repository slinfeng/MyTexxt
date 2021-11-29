<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class HireType
 *
 * @property int $id
 * @property string|null $hire_type
 *
 * @package App\Models
 */
class HireType extends Model
{
	protected $table = 'hire_types';
	public $timestamps = false;
    use LogsActivity;

	protected $fillable = [
		'hire_type'
	];
    public function employee_base(){
        return $this -> hasMany('employee_base','hire_type_id','id');
    }
}
