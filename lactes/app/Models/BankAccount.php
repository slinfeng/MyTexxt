<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class BankAccount
 *
 * @property int $id
 * @property string|null $bank_name
 * @property string|null $branch_name
 * @property string|null $branch_code
 * @property int|null $account_type
 * @property string|null $account_name
 * @property string|null $account_num
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @package App\Models
 */
class BankAccount extends Model
{
    use SoftDeletes,LogsActivity;
    use Encryptable;
    protected $encryptable = [
        'bank_name',
        'branch_name',
        'branch_code',
        'account_name',
        'account_num',
    ];
	protected $table = 'bank_accounts';
	public $timestamps = false;

	protected $casts = [
		'account_type' => 'int'
	];

	protected $fillable = [
		'bank_name',
		'branch_name',
		'branch_code',
		'account_type',
		'account_name',
		'account_num'
	];

	public function getFillable()
    {
        return $this->fillable;
    }

    public function BankAccountType(){
	    return $this->belongsTo(BankAccountType::class,'account_type','id');
    }
}
