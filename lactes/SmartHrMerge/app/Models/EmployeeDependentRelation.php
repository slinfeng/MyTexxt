<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class EmployeeDependentRelation
 *
 * @property int $id
 * @property int $employee_id
 * @property string|null $dname
 * @property string|null $dependent_residence_card_num
 * @property int|null $relationship_type
 * @property string|null $dependent_birthday
 * @property string|null $relationship
 * @property string|null $live_type
 * @property string|null $dependent_address
 * @property string|null $estimated
 * @property string|null $data_history
 * @property string|null $created_user
 * @property string|null $updated_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeDependentRelation extends Model
{
	use SoftDeletes;
    use Encryptable;
    use LogsActivity;
    protected $primaryKey='id';

    protected $encryptable = [
        'dname',
        'dependent_residence_card_num',
        'dependent_birthday',
        'relationship',
        'dependent_address',
        'estimated',
        'data_history',
    ];
	protected $table = 'employee_dependent_relations';

	protected $casts = [
		'id' => 'int',
		'employee_id' => 'int',
		'relationship_type' => 'int',
        'data_history' => 'array'
	];

	protected $fillable = [
		'dname',
		'dependent_residence_card_num',
		'relationship_type',
		'dependent_birthday',
		'relationship',
		'live_type',
		'dependent_address',
		'estimated',
	];
    public function getFillable(){
        return $this->fillable;
    }
    public function employee_base(){
        return $this -> belongsTo('employee_base','employee_id','id');
    }
}
