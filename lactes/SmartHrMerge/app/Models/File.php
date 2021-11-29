<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class File
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $path
 * @property string|null $thumbnail
 * @property string|null $name
 * @property string|null $basename
 * @property string|null $mimetype
 * @property string|null $filesize
 * @property string|null $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int is_in_local
 *
 * @package App\Models
 */
class File extends Model
{
    const IN_LOCAL = 1,IN_CLOUD = 0;
	use SoftDeletes;
	protected $table = 'files';

	protected $casts = [
		'user_id' => 'int',
		'is_in_local' => 'int'
	];

	protected $fillable = [
		'user_id',
		'path',
		'thumbnail',
		'name',
		'basename',
		'mimetype',
		'filesize',
		'type',
		'is_in_local',
	];

    public function accounts_invoices()
    {
        return $this->hasOne(AccountsInvoice::class,'id','file_id');
    }
    public function employeeBase()
    {
        return $this->hasOne(EmployeeBase::class,'id','icon');
    }
    public function employeeSkill()
    {
        return $this->hasOne(EmployeeSkill::class,'id','residence_card_copy');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
