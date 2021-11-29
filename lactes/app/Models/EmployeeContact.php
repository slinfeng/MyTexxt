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
 * Class EmployeeContact
 *
 * @property int $id
 * @property int $employee_id
 * @property string|null $mail
 * @property string|null $phone
 * @property string|null $telephone
 * @property string|null $fax
 * @property string|null $postcode
 * @property string|null $address
 * @property string|null $nearest_station
 * @property string|null $home_town_postcode
 * @property string|null $home_town_address
 * @property string|null $emergency_name
 * @property string|null $emergency_relationship
 * @property string|null $emergency_phone
 * @property string|null $data_history
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EmployeeContact extends Model
{
	use SoftDeletes;
	protected $table = 'employee_contacts';
    use Encryptable;
    use LogsActivity;

    protected $encryptable = [
        'phone',
        'telephone',
        'fax',
        'postcode',
        'address',
        'nearest_station',
        'home_town_postcode',
        'home_town_address',
        'emergency_name',
        'emergency_phone',
        'data_history',
    ];

    protected $casts = [
        'employee_id' => 'int',
        'data_history' => 'array'
    ];

    protected $fillable = [
        'phone',
        'telephone',
        'fax',
        'postcode',
        'address',
        'nearest_station',
        'home_town_postcode',
        'home_town_address',
        'emergency_name',
        'emergency_relationship',
        'emergency_phone',
    ];
    protected $mobileModify = [
        'phone',
        'telephone',
        'fax',
        'postcode',
        'address',
        'nearest_station',
        'home_town_postcode',
        'home_town_address',
        'emergency_name',
        'emergency_relationship',
        'emergency_phone'
    ];
    public function getFillable(){
        return $this->fillable;
    }
    public function getMobileModify(){
        return $this->mobileModify;
    }
    public function employee_base(){
        return $this -> belongsTo('employee_base','employee_id','id');
    }
}
