<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    protected $table = 'events';

    protected $casts = [
        'from_date' => 'datetime',
        'to_date' => 'datetime'
    ];

    protected $dates = [
//        'from_date',
//        'to_date'
    ];

    protected $fillable = [
        'user_id',
        'title',
        'text_color',
        'bg_color',
        'from_date',
        'to_date',
        'is_public'
    ];


    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
