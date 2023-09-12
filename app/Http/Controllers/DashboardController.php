<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $user->load('company');

        $driversCount = User::where('company_id',$user->company_id)->where('role','driver')->with(['driver','company'])->count();
        $pendingOrdersCount = Order::where('company_id',$user->company_id)->where('status','pending')->with(['driver','company'])->count();
        $cancelledOrdersCount = Order::where('company_id',$user->company_id)->where('status','cancelled')->with(['driver','company'])->count();
        $deliveredOrdersCount = Order::where('company_id',$user->company_id)->where('status','delivered')->with(['driver','company'])->count();

        return response()->json([
            'status' => 'ok',
            'driversCount' => $driversCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'cancelledOrdersCount' => $cancelledOrdersCount
        ], 200);
    }
}
