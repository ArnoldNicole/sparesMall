<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class CustomerController extends Controller
{
  public function __construct(){
  	return $this->middleware('auth');
  }

  public function init(){
  	$cart= $this->cart();
  	return view('welcome', compact('cart'));
  }

  public function cart(){
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

  	return $cart;

  }
}
