<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $table = 'stores';
    protected $fillable = [
        'data',
        'title',
        'url',
        'status',
        'create_product',
        'sync_order',
        'tracking',
        'user_id',
        'sync_at',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function orders(){
        return $this->hasMany('App\Models\Order', 'store', 'id');
    }
}
