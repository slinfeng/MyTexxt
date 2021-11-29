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
 * Class User
 *
 * @property int $id
 * @property int $sort_num
 * @property string $name
 * @property string $user_ip_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @package App\Models
 */
class UserIpAddress extends Model
{
    use SoftDeletes,Encryptable;
    use LogsActivity;
	protected $table = 'user_ip_address';

    protected $encryptable = [
        'ip_address',
    ];

    protected $casts = [

    ];

	protected $fillable = [
        'sort_num',
        'ip_address',
        'name',
	];


}
