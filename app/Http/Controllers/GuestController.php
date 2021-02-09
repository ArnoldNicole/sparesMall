<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\CartResource;
use App\Models\User;
use App\Http\Resources\ProductResource;
use App\Models\Product;
// use TeamTNT\TNTSearch\TNTSearch;

class GuestController extends Controller
{
    public function welcome(){
    	$cart='';
    	if (Auth::check()) {
    			$basket = '';  
    			if (auth()->user()->baskets->count()==0) {
    				$basket = '';
    			}else{
    				if(auth()->user()->baskets->where('status', 'current')==null){
    					$basket = '';	 
    				}else{
    					$basket = auth()->user()->baskets->where('status', 'current')->first();
    				}
    			}

    			if ($basket == '') {
    				$cart = false;
    			}else{
    				$cart = new CartResource($basket);
    			}
    	}    			
    	else{
    		$cart = false;
    	}
    	return view('welcome', compact('cart'));
    }

    public function search(Request $request){
        $query= $this->validate($request, [
                 'query'=>['required']
    ]);
    $results = Product::search($query['query'])->get();
    return $results;
}

}
