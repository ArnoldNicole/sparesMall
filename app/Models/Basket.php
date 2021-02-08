<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Basket extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable= [
    	'order_id',
    	'user_id',
        'status',
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }
    public function products(){
    	return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
