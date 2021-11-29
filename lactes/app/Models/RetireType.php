<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class RetireType
 *
 * @property int $id
 * @property string $retire_type
 *
 * @package App\Models
 */
class RetireType extends Model
{
	protected $table = 'retire_type';
	public $timestamps = false;
    use LogsActivity;

	protected $fillable = [
		'retire_type'
	];
    public function employee_base(){
        return $this -> hasMany('employee_base','retire_type_id','id');
    }
}
