<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    protected $fillable = [
    	'name',
    	'year_of_manufacture',
    	'price',
        'image_url',
    	'country',
    	'manufacturer',
    	'color',
    	'size',
    	'description',
    	'featured',
    	'category_id',
    	'status',
    ];
    protected $appends = ['is_featured'];
    public function getIsFeaturedAttribute()
    {
        if ($this->featured =='no') {
            return false;
        }
        else{
            return true;
        }
    }
    public function searchableAs()
        {
            return 'products_index';
        }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function baskets()
    {
        return $this->belongsToMany(Basket::class)->withPivot('quantity');
    }
}
