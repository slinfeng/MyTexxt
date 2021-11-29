<?php

namespace App\Models;

use App\Casts\Amount;
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
 * @property string $receipt_amount
 * @property Carbon|null $receipt_date
 * @property int|null $client_id
 * @property string|null $client_name
 * @property string|null $name_or_memo
 * @property string|null $document_end
 * @property int $request_setting_id
 * @property string|null $create_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */

class Receipt extends Model
{
    const VIEW = 'receipt_view';
    const MODIFY = 'receipt_modify';
    const SELF_MODIFY = 'receipt_self_modify';
    use LogsActivity;
    use SoftDeletes,SoftDeletes, CastFormatTrait, SearchableTrait;
    use Encryptable;

    protected $table = 'receipt';

    protected $encryptable = [
        'receipt_amount',
    ];

    protected $casts = [
        'client_id' => 'int',
        'request_setting_id' => 'int',
        'create_user_id' => 'int'
    ];

    protected $fillable = [
        'receipt_amount',
        'receipt_date',
        'document_end',
        'client_id',
        'client_name',
        'name_or_memo',
        'request_setting_id',
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
