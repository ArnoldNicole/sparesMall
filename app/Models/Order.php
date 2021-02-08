<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $fillable = [
    'user_id',
    'amount_received',
    'status',
    'basket_id',
    'shipping_adress_id',
    'payment_method',
    'totalPrice'
];
protected $casts = [    
    'created_at' => 'datetime: d-m-Y',
    'updated_at' => 'datetime: d-m-Y',
];

	public function basket(){
		return $this->hasOne(Basket::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}
    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
