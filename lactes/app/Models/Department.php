<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Department
 *
 * @property int $id
 * @property string|null $department_name
 *
 * @package App\Models
 */
class Department extends Model
{
	protected $table = 'departments';
    public $timestamps = false;
    use LogsActivity;

	protected $fillable = [
		'department_name'
	];
    public function employee_base(){
        return $this -> hasMany('employee_base','department_type_id','id');
    }
}
