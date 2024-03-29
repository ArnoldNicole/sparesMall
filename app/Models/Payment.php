<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
    	'code',
    	'order_id',
    	'amount'
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }
}
