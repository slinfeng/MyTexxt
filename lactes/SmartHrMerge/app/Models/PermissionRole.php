<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class PermissionRole
 *
 * @property int $role_id
 * @property int $permission_id
 *
 * @package App\Models
 */
class PermissionRole extends Model
{
    use LogsActivity;
	protected $table = 'permission_role';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'role_id' => 'int',
		'permission_id' => 'int'
	];

	protected $fillable = [
		'role_id',
		'permission_id'
	];
}
