<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BankAccountType
 *
 * @property int $id
 * @property string|null $account_type_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class BankAccountType extends Model
{
	protected $table = 'bank_account_types';

	protected $fillable = [
		'account_type_name'
	];
}
