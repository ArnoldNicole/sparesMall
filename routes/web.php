<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'GuestController@welcome');

Auth::routes();

Route::get('/home', function(){
	return redirect('/');
})->name('home');

Route::get('/app/logout', function(){
	 \Auth::logout();
	 return redirect('/');
});
Route::patch('/user/account/updatePassword','CustomerAjaxController@changePassword')->middleware('auth');
Route::post('/search/products','GuestController@search');



// Seller routes
Route::prefix('/seller')->middleware(['auth','seller'])->group(function () {
	Route::prefix('products')->group(function () {
	//add
	// update
	//delete
	//add stock		
	});

	Route::prefix('orders')->group(function () {
	//view
	// ship
	//list
	//close		
	});
	Route::post('product_images/upload','Images\ProductImageController@uploadImage');
	Route::apiResource('product_images', Images\ProductImageController::class);
	Route::prefix('categories')->group(function () {
		Route::post('new','SellerAjaxController@create_category');
		Route::get('all', 'SellerAjaxController@categories');
		Route::post('deleteImage', 'SellerAjaxController@deleteImage');
		Route::post('uploadImage','SellerAjaxController@upload');
		Route::post('edit_category','SellerAjaxController@editCategory');
		Route::delete('deleteCategories','SellerAjaxController@deleteCategories');
	});	

	Route::prefix('products')->group(function () {
		Route::post('new','SellerAjaxController@create_product');
		Route::get('all', 'SellerAjaxController@products');
		Route::post('deleteImage', 'SellerAjaxController@deleteProductImage');
		Route::post('uploadImage','SellerAjaxController@uploadProductImage');
		Route::post('edit_product','SellerAjaxController@editProduct');
		Route::delete('deleteProduct','SellerAjaxController@deleteProduct');
		Route::patch('makeFeatured','SellerAjaxController@makeFeatured');
	});

	Route::prefix('orders')->group(function () {
		Route::get('getOrders','SellerAjaxController@allpaidorders');
		Route::get('fetchCompleteOrders','SellerAjaxController@fetchCompleteOrders');
		Route::get('fetchShippingInProgressOrders','SellerAjaxController@fetchShippingInProgressOrders');
		Route::post('closeOrders/{order}','SellerAjaxController@closeOrders');
		Route::post('getOrders/{order}','SellerAjaxController@updateOrderToShipping');
	});


	Route::prefix('account')->group(function () {
	//index
		Route::get('index','SellerController@index');
	// ship
	//list
	//close		
	});		
});


Route::prefix('/customer')->middleware(['auth'])->group(function () {
	Route::prefix('product')->group(function () {		
	Route::post('addtocart/{product}','CustomerAjaxController@addToCart');
	Route::post('checkCart/{product}','CustomerAjaxController@checkCart');
	Route::post('removeFromCart/{product}','CustomerAjaxController@removeFromCart');
	Route::post('updateQuantity','CustomerAjaxController@updateQuantity');
	Route::get('account_data','CustomerAjaxController@shippingInfo');
	Route::post('add_acount_data','CustomerAjaxController@saveShippingAdress');
	Route::post('placeOrder','CustomerAjaxController@saveOrder');
	Route::get('completeOrder','CustomerController@init');
	Route::get('getOrders','CustomerAjaxController@orders');
	Route::get('fetchCompleteOrders','CustomerAjaxController@fetchCompleteOrders');
	Route::get('fetchShippingInProgressOrders','CustomerAjaxController@fetchShippingInProgressOrders');
	// Route::patch('orderPayment','CustomerAjaxController@orderPayment');
	Route::apiResource('orderPayment', Mpesa\MpesaTransactionController::class);

	//search
	//buy	
	});

	Route::prefix('orders')->group(function () {
	//view
	// cancel	
	//closed		
	});	

	Route::get('account','GuestController@welcome');	
});

Route::get('/featuredProducts','GuestAjaxController@featuredproducts');
Route::get('/products','GuestAjaxController@products');
Route::get('customer/product/description/{product}','GuestAjaxController@product');
Route::get('/customer/browse/products/all','GuestAjaxController@allProducts');
Route::get('/app/seller/categories/all','GuestAjaxController@categories');
Route::any('{slug}', 'GuestController@welcome');