<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ResidenceType
 *
 * @property int $id
 * @property string $residence_type
 *
 * @package App\Models
 */
class ResidenceType extends Model
{
	protected $table = 'residence_type';
	public $timestamps = false;
    use LogsActivity;

	protected $fillable = [
		'residence_type'
	];
    public function employeeStay(){
        return $this -> hasMany('employee_stay','residence_type','id');
    }
}
