<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'origin_id',
        'store',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'tracking_code',
        'status',
        'note',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function store(){
        return $this->belongsTo('App\Models\Store', 'store', 'id');
    }
}
