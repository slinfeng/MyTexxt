<?php

namespace App\Models;

use App\Traits\CastFormatTrait;
use App\Traits\Encryptable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class LetterOfTransmittal
 *
 * @property int $id
 * @property float $top_bottom_distance
 * @property float $left_right_distance
 * @property string $name
 * @property Carbon|null $delivery_date
 * @property int $client_id
 * @property string|null $client_address
 * @property string|null $memo
 * @property int $request_setting_id
 * @property string|null $title
 * @property string|null $content
 * @property string|null $document_send
 * @property string|null $create_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */

class LetterOfTransmittal extends Model
{
    const SETTING_ID = 6;
    const VIEW = 'transmittal_view';
    const MODIFY = 'transmittal_modify';
    const SELF_MODIFY = 'transmittal_self_modify';
    use LogsActivity;
    use SoftDeletes;
    use Encryptable;
    protected $table = 'letter_of_transmittal';

    protected $encryptable = [
        'client_address',
        'document_send'
    ];


    protected $casts = [
        'client_id' => 'int',
        'top_bottom_distance' => 'float',
        'left_right_distance' => 'float',
        'request_setting_id' => 'int',
        'create_user_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'delivery_date',
        'top_bottom_distance',
        'left_right_distance',
        'client_id',
        'client_address',
        'memo',
        'request_setting_id',
        'title',
        'content',
        'document_send',
        'create_user_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function requestSetting()
    {
        return $this->hasOne(RequestSetting::class,'id','request_setting_id');
    }
}
