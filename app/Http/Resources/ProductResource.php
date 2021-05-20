<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'year_of_manufacture'=>$this->year_of_manufacture,
            'price'=>$this->price,
            'country'=>$this->country,
            'image_url'=>$this->image_url,
            'manufacturer'=>$this->manufacturer,
            'color'=>$this->color,
            'size'=>$this->size,
            'description'=>$this->description,
            'featured'=>$this->is_featured,
            'category_id'=>$this->category_id,
            'status'=>$this->status,
            'category'=>$this->category->categoryName,
            'created_at'=>$this->created_at,
            'images'=>$this->images,
        ];
    }
}

