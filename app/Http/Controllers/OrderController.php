<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $orders = Order::where('created_by',$user->id)->with(['user','driver','driver.user','company'])->get();
        return response()->json(['status' => 'ok','orders'=>$orders], 200);
    }
    public function store(Request $request)
    {
        $order = new Order($request->all());
        $order->save();
        return response()->json(['status' => 'ok','order'=>$order], 200);
    }
    public function get(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::where('created_by',$user->id)->with(['user','driver','driver.user'])->first();
        return response()->json(['status' => 'ok','order'=>$order], 200);
    }
    public function changeStatus(Request $request, $id, $status)
    {
        $user = $request->user();
        $order = Order::where('id',$id)->with(['user','driver','driver.user'])->first();
        if($order){
            $order->update(['status'=>$request->status,'delivered_at' => Carbon::now()]);
            return response()->json(['status' => 'ok','order'=>$order], 200);
        }else{
            return response()->json(['status' => 'no','order'=>$order], 400);
        }
    }

}
