<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;


class CustomerAjaxController extends Controller
{
   public function __construct()
   {
   	return $this->middleware('ajax_only');
   }

  public function checkCart(Product $product){
  		$basket = '';
  		if ($product) {
  		 //check if a user has a cart with status current
  		//if there is none create a new cart
  	//add the cproduct in the cart
  		if (auth()->user()->baskets->count()==0) {
  			//create a new basket
  			$basket = '';
  		}else{
  			if(auth()->user()->baskets->where('status', 'current')->count()==0){
  				$basket = '';	 
  			}else{
  				$basket = auth()->user()->baskets->where('status', 'current')->first();
  			}
  		}
  		//now that we have a basket check if the product is in the cart
  		$incart = ($basket) ? $basket->products->contains($product->id) : false;
  			if ($incart == true) {
  				return response()->json([
  					'message'=>'Product Exists In Cart'
  				],200);// $basket->products()->dettach($product);
  				
  			}else{
  			return response()->json([
  					'message'=>'Product Does Not Exists In Cart'
  				],201);
  			}
  		}else{
  		   return response()->json([
  		      'error'=>'Product Not Found.'
  		   ],404);
  		}
  }

   public function addToCart(Product $product){
   	$basket = '';
   	if ($product) {
   	 //check if a user has a cart with status current
   	//if there is none create a new cart
   //add the cproduct in the cart
   	if (auth()->user()->baskets->count()==0) {
   		//create a new basket
   		$basket = auth()->user()->baskets()->create([
   			'status'=>'current',
   		]);		
   	}else{
   		if(auth()->user()->baskets->where('status', 'current')->count()==0){
   			$basket = auth()->user()->baskets()->create([
   			'status'=>'current',
   		]);	 
   		}else{
   			$basket = auth()->user()->baskets->where('status', 'current')->first();
   		}
   	}
   	//now that we have a basket check if the product is in the cart
   	$incart = ($basket) ? $basket->products->contains($product->id) : false;
   		if ($incart == true) {
   			return response()->json([
   				'message'=>'Product Exists In Cart'
   			],201);// $basket->products()->dettach($product);
   			
   		}else{
   			 
        return $product->baskets()->attach($basket);
   		}
   	}else{
   	   return response()->json([
   	      'error'=>'Product Not Found.'
   	   ],404);
   	}
   }



   public function removeFromCart(Product $product){
   	$basket = '';
   	if ($product) {
   	 //check if a user has a cart with status current
   	//if there is none create a new cart
   //add the cproduct in the cart
   	if (auth()->user()->baskets->count()==0) {
   		return response()->json(['msg'=>'No Cart Found'],201);
   		//create a new basket
   		// $basket = auth()->user()->baskets()->create([
   		// 	'status'=>'current',
   		// ]);		
   	}else{
   		if(auth()->user()->baskets->where('status', 'current')->count()==0){
   			return response()->json(['msg'=>'No Cart Found'],201);   			
   			// $basket = auth()->user()->baskets()->create([
   			// 'status'=>'current',
   		// ]);	 
   		}else{
   			$basket = auth()->user()->baskets->where('status', 'current')->first();
   		}
   	}
   	//now that we have a basket check if the product is in the cart
   	$incart = ($basket) ? $basket->products->contains($product->id) : false;
   		if ($incart == true) {
   			// return response()->json([
   			// 	'message'=>'Product Exists In Cart'
   			// ],201);
   			 $basket->products()->detach($product);
   			
   		}else{
   			return response()->json([
   				'message'=>'Product Exists In Cart'
   			],201);
   			// return $basket->products()->attach($product);
   		}
   	}else{
   	   return response()->json([
   	      'error'=>'Product Not Found.'
   	   ],404);
   	}
   }

   public function updateQuantity(Request $request)
   {
    $basket = auth()->user()->baskets->where('status','current')->first();
    $product = Product::find($request->id);
    $setter = DB::table('basket_product')
      ->where('basket_id', $basket->id)
      ->where('product_id', $product->id)
    ->update([
      'quantity'=>$request->quantity
    ]); 
    //dd($setter); 
    return response()->json(['success'=>'Saved'],200);
   }

   public function shippingInfo(){
    if (auth()->user()->shipping_adress->count()==0) {
      return response()->json(['msg'=>'User Has No Shipping Adress'], 404);
    }else{
      return auth()->user()->shipping_adress;//response()->json(['msg'=>'Shipping Adresses Found'], 201);

    }
   }

   public function saveShippingAdress(Request $request){
    $data = $this->validate($request, [
      'phone_number'=>'required|digits:10|starts_with:07,01,08',
      'Address'=>'required|string',
      'additional_information'=>'required|string|max:1000',
      'region'=>'required|string',
      'city'=>'required|string',
    ]);

    return auth()->user()->shipping_adress()->create($data);    
   }

   public function saveOrder(Request $request){
    $data = $this->validate($request, [
      'shipToAdress'=>'required|numeric|exists:shipping_adresses,id',
      'paymentMethod'=>'required|string',
    ]);

    $basket = auth()->user()->baskets->where('status','current')->first();
    if ($basket == null) {
       return response()->json(['msg'=>'Nothing In Cart'], 405); 
    } 
    //check if the basket id exists in orders
    $orders = auth()->user()->orders->where('basket_id',$basket->id)->first();
    //dd($orders);
    if ($orders!=null) {
      return response()->json(['msg'=>'Order Exists'], 405);
    }
    //first create the order
   $newOrder =  auth()->user()->orders()->create([
      'amount_received'=>0,
      'totalPrice'=>$request->totalPrice,      
      'basket_id'=>$basket->id,
      'shipping_adress_id'=>$data['shipToAdress'],
      'payment_method'=>$data['paymentMethod'],
    ]);

    $basket->status = 'Checked Out';
    $basket->order_id = $newOrder->id;
    $basket->save();

    return response()->json(['message'=>'Order Initialized, Proceed to Payment'], 201);   

    }

    public function orders(){
      $orders = auth()->user()->orders->where('status','pending');
      //dd($orders);
       return OrderResource::collection($orders);
    }

    public function orderPayment(Request $request){
      $this->validate($request, [
        'code'=>'required|unique:payments|min:5|max:14'
      ]);



    $order = Order::findorfail($request->order);
    //dd($order);
    if ($order->payment!=null) {
      return response()->json(['msg'=>'Order seems to have a pending payment'],404);
    }

     $pay=$order->payment()->create([
        'code' => $request->code,
        'amount' => $order->totalPrice,
      ]); 

      $order->update([
        'amount_received' => $request->amount,
        'status'=>'Paid, Awaiting Confirmation'
      ]);

      //send the customer an email notification

      return response()->json(['msg','Payment Posted'], 200);

    }

    public function changePassword(Request $request){
      $data = $this->validate($request, [
        'oldpassword'=>'required', 'min:8',
        'password'=>'required', 'string', 'min:8', 'confirmed',        
      ]);
      $user = auth()->user();
      if (Hash::check($data['oldpassword'], $user->password)) {
        $user->update([
          'password'=>Hash::make($data['password']),
        ]);
        return response()->json(['msg'=>'Password Changed'],200);

      }else{
        return response()->json(['msg'=>'Incorrect Old Password Supplied'],404);
      }

    }
}
