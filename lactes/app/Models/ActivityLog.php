<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ActivityLogJaTrait;
use App\Casts\ActivityLogJa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityLog
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ActivityLog extends Model
{
	protected $table = 'activity_log';
//	use ActivityLogJaTrait;

	protected $casts = [
		'subject_id' => 'int',
		'causer_id' => 'int',
		'description' => ActivityLogJa::class,
		'subject_type' => ActivityLogJa::class
	];

	protected $fillable = [
		'log_name',
		'description',
		'subject_type',
		'subject_id',
		'causer_type',
		'causer_id',
		'properties'
	];
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
