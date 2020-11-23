<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('users')->get();
        return response()->json(['data' => $orders]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_ref' => 'required|unique:orders',
            'user_id' => 'required',
            'amount' => 'required',
            'items' => 'required',
            'address' => 'required',
        ]);
        
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 400);
        }
        
        $url = 'https://api.paystack.co/transaction/verify/'.$request->payment_ref;
        
        //open connection
        $ch = curl_init();
        //set request parameters 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer ".env('PAYSTACK_SECRET_KEY').""]);
        
        //send request
        $req = curl_exec($ch);
        //close connection
        curl_close($ch);
        //declare an array that will contain the result
        $result = array();

        if($req){
            $result = json_decode($req, true);
        }

        if (array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {
            //Save order details

            $order = Order::create($request->only([
                'payment_ref', 'user_id', 'amount', 'items', 'address'
            ]));

            $order->save();

            return response()->json(['message' => 'Checkout successful. Your order will be processed as soon as possible']);

        }else{
            return response()->json(['message' => 'Invalid transaction. Please try again later'], 400);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
