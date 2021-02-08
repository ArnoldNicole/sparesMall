<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Http\Resources\OrderResource;

class SellerAjaxController extends Controller
{
    public function __construct(){
    	return $this->middleware(['seller','ajax_only']);
    }

    // Categories

    public function categories(){
    	return Category::all();
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file'=>'image|required|mimes:jpeg,jpg,png,svg,bmp|max:2048'
        ]);
        $imageName=time().'.'.$request->file->extension();
        $request->file->move(public_path('category_images'), $imageName);
        return $imageName;
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return "Deleted image" ;      
    }

    public function deleteFileFromServer($fileName){
        $filePath = public_path().'/category_images/'.$fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
            
        }
        return ; 
    }

    public function create_category(Request $request){
    	// validate request
    	$this->validate($request, [
    	    'categoryName' => 'required',
    	    'iconImage' => 'required',
    	]);
    	return Category::create([
    	    'categoryName' => $request->categoryName,
    	    'iconImage' => $request->iconImage,
    	]);
    }

    public function editCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function deleteCategories(Request $request)
    {
        // first delete the original file from the server
        $this->deleteFileFromServer($request->iconImage);
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Category::where('id', $request->id)->delete();
    }











    //products routes
    public function products(){
    	return ProductResource::collection(Product::all());
    }

    public function uploadProductImage(Request $request)
    {
        $this->validate($request, [
            'file'=>'image|required|mimes:jpeg,jpg,png,svg,bmp|max:2048'
        ]);
        $imageName=time().'.'.$request->file->extension();
        $request->file->move(public_path('product_images'), $imageName);
        return $imageName;
    }

    public function deleteProductImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteProductFileFromServer($fileName);
        return "Deleted image" ;      
    }

    public function deleteProductFileFromServer($fileName){
        $filePath = public_path().'/product_images/'.$fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
            
        }
        return ; 
    }

    public function create_product(Request $request){
    	//validate the data
        $data = $this->validate($request, [
            'image_url'=>'required|string',
            'name'=>'required|string',
            'year_of_manufacture'=>'required|numeric|digits:4',
            'price'=>'required|numeric',
            'country'=>'required|string',
            'manufacturer'=>'required|string',
            'color'=>'required|string',
            'size'=>'required|string',
            'category_id'=>'required|numeric|exists:categories,id',
            'description'=>'required|string|min:30',
        ]);

        $product = Product::create($data);
        return new ProductResource($product);
    }

    public function editProduct(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Product::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function deleteProduct(Request $request)
    {
        // first delete the original file from the server
        $this->deleteProductFileFromServer($request->image_url);
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Product::where('id', $request->id)->delete();
    }
     public function makeFeatured(Request $request){
        $product = Product::find($request->id)->first();
        if ($product->is_featured) {
            $product->featured = "no";
            $product->save();
            return response()->json([
                'featured' => false
            ],200);
        }
        else{
            $product->featured = "yes";
            $product->save();
            return response()->json([
                'featured' => true
            ],200);
        }
     }

     public function allpaidorders(){
        $orders = Order::where('status','Paid, Awaiting Confirmation')->get();

        return OrderResource::collection($orders);
     }

     public function updateOrderToShipping(Order $order){
        $order->update(['status'=>'Shipping In Progress']);
        return response()->json(['msg'=>'Status Updated To Shipping In Progress'],200);
     }

     public function fetchShippingInProgressOrders(){
        $orders = Order::where('status','Shipping In Progress')->get();

        return OrderResource::collection($orders);
     }

     public function fetchCompleteOrders(){
        $orders = Order::where('status','Completed')->get();

        return OrderResource::collection($orders);
     }

     public function closeOrders(Order $order){
        $order->update(['status'=>'Completed']);
        return response()->json(['msg'=>'Order Has Been Closed'],200);
     }
}
