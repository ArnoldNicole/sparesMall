<?php

namespace App\Http\Controllers\Mpesa;

use App\Helpers\Mpesa;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class MpesaTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $order = Order::findorfail($request->order);
        if ($order->payment!=null) {
        return response()->json(['msg'=>'Order seems to have a pending payment'],404);
        }
        $amount=$request->amount;
        $pnb = ltrim(auth()->user()->phone_number, '0');
        $msisdn='254'.$pnb;    
        $TransactionDesc='OrderPayment';
        $initiate_transaction = new Mpesa;
        $response = $initiate_transaction->sendSTKPush($amount, $msisdn, $TransactionDesc, auth()->user()->phone_number);


        $order->payment()->create([
            'code' => $order->id.'_'.$request->amount.'_'.auth()->user()->phone_number,
            'amount' => $order->totalPrice,
        ]); 

        $order->update([
            'amount_received' => $request->amount,
            'status'=>'Paid, Awaiting Confirmation'
        ]);

        //send the customer an email notification

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
