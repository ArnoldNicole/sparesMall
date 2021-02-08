<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\ProductResource;

class GuestAjaxController extends Controller
{
   public function __construct()
   {
   	return $this->middleware('ajax_only');
   }

   public function featuredproducts(){
   	$products = Product::orderBy('created_at','asc')->where('featured','yes')->take(10)->get();
   	return $products;
   }

   public function products(){
   	return ProductResource::collection(Product::orderBy('created_at','asc')->take(15)->get());
   }
   public function product(Product $product){
      if ($product) {
        return new ProductResource($product);
      }else{
         return response()->json([
            'error'=>'Product Not Found.'
         ],404);
      }      

   }

   public function allProducts(){
      return ProductResource::collection(Product::orderBy('created_at','asc')->paginate(27));
   }

   public function categories(){
      return Category::all();
   }
}
