<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
	public function __construct(){
		return $this->middleware('seller');
	}
	
   public function index(){
   	return view('seller.index');
   }
}
