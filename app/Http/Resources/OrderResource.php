<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ShippingAdress;

class OrderResource extends JsonResource
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
            'amount_received'=>$this->amount_received,
            'status'=>$this->status,            
            'payment_method'=>$this->payment_method,
            'id'=>$this->id,
            'created_at'=>$this->created_at->diffForHumans(),            
            'products' => $this->basket->products,                      
            'address'=>ShippingAdress::find($this->shipping_adress_id),
            'totalPrice'=>$this->totalPrice,
            'payment'=>$this->payment,            
        ];
    }
}
