<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingAdress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
    	'user_id',
    	'phone_number',
    	'Address',
    	'additional_information',
    	'region',
    	'city',
    	'default',
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }    
}
