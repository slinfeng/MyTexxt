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
 * Class AccountsOrderConfirmation
 *
 * @property int $id
 * @property int $client_id
 * @property string|null $period
 * @property string $order_manage_code
 * @property string $project_name_or_file_name
 * @property int|null $our_position_type
 * @property int $file_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class AccountsOrderConfirmation extends Model
{
    const SETTING_ID = 4;
    const CODE_FIELD = 'order_manage_code';
    const VIEW = 'orderconfirm_view';
    const MODIFY = 'orderconfirm_modify';
	use SoftDeletes,SearchableTrait;
    use Encryptable;
    use LogsActivity;
	protected $table = 'accounts_order_confirmations';
    protected $encryptable = [
        'cname',
        'project_name_or_file_name',
        ];
	protected $casts = [
		'client_id' => 'int',
		'our_position_type' => 'int',
		'file_id' => 'int',
	];


	protected $fillable = [
		'client_id',
		'cname',
        'period',
		'order_manage_code',
		'project_name_or_file_name',
		'our_position_type',
		'file_id',
	];

	protected $searchable = [
        'columns' => [
            'accounts_order_confirmations.order_manage_code' => 10,
            'accounts_order_confirmations.project_name_or_file_name' => 10,
            'accounts_order_confirmations.estimate_subtotal' => 5,
            'accounts_order_confirmations.period' => 5,
            'clients.client_name' => 10,
            'our_position_types.our_position_type_opp_name' => 10,
        ],
        'joins' => [
            'clients' => ['accounts_order_confirmations.client_id', 'clients.id'],
            'our_position_types' => ['accounts_order_confirmations.our_position_type', 'our_position_types.id'],
            'files' => ['accounts_order_confirmations.file_id', 'files.id'],
        ],
    ];

	public function client(){
	    return $this->belongsTo(Client::class,'client_id',"id");
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function our_position_types(){
        return $this->belongsTo(OurPositionType::class,'our_position_type','id');
    }
}
