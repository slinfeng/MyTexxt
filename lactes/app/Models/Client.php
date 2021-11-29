<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use App\Casts\PostCode;
use App\Traits\Encryptable;
use App\Traits\HasInvoices;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Client
 *
 * @property int $id
 * @property string $client_name
 * @property string $client_abbreviation
 * @property string|null $cooperation_start
 * @property string|null $client_address
 * @property string|null $post_code
 * @property string|null $url
 * @property string|null $mail
 * @property string|null $tel
 * @property string|null $fax
 * @property string|null $our_role
 * @property string|null $memo
 * @property string|null $priority
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Client extends Model
{
    const SETTING_ID = 1;
    const VIEW = 'client_view';
    const MODIFY = 'client_modify';
    const SORT_BY_ID = 0;
	use SoftDeletes,HasInvoices,Encryptable;
    use LogsActivity;
    protected $encryptable = [
        'client_name',
        'client_abbreviation',
        'client_address',
        'post_code',
        'url',
        'mail',
        'tel',
        'fax',
        'memo',
    ];
	protected $table = 'clients';

    protected $casts = [
        'calc_type' => 'int',
    ];

	protected $fillable = [
		'client_name',
		'client_abbreviation',
		'cooperation_start',
		'client_address',
		'post_code',
		'url',
		'mail',
		'tel',
		'fax',
		'our_role',
		'memo',
        'priority',
        'calc_type',
        'document_format'
	];
    public function accounts_estimate()
    {
        return $this->hasMany(AccountsEstimate::class);
    }
    public function accounts_order()
    {
        return $this->hasMany(AccountsOrder::class);
    }

    public function OurPositionType()
    {
        return $this->belongsTo('App\Models\OurPositionType', 'our_role', 'id');
    }
    public function accountsOrderContirmation(){
        return $this->hasMany(AccountsOrderConfirmation::class,'client_id','id');
    }
    public function AccountsOrder(){
        return $this->hasMany(AccountsOrder::class,'client_id','id');
    }
    public function LetterOfTransmittals(){
        return $this->hasMany(LetterOfTransmittal::class,'client_id','id');
    }
    public function Receipts(){
        return $this->hasMany(Receipt::class,'client_id','id');
    }
    public function user(){
        return $this->hasMany(User::class,'client_id','id');
    }
    public function requestSettingClients(){
        return $this->hasOne(RequestSettingClient::class,'client_id','id');
    }
}
