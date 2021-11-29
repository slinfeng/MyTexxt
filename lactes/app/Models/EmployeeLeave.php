<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmployeeLeave
 *
 * @property int $id
 * @property int $employee_id
 * @property int $annual_leave_type
 * @property string $annual_leave_total
 * @property string $annual_leave_balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class EmployeeLeave extends Model
{
	protected $table = 'employee_leave';

	protected $casts = [
		'employee_id' => 'int',
		'annual_leave_type' => 'int',
		'annual_leave_total' => 'array',
		'annual_leave_balance' => 'array'
	];

	protected $fillable = [
		'employee_id',
		'annual_leave_type',
		'annual_leave_total',
		'annual_leave_balance'
	];
}
