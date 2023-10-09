<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $drivers = User::where('company_id',$user->company_id)->where('role','driver')->with(['driver','company'])->get();
        return response()->json(['status' => 'ok','drivers'=>$drivers], 200);
    }

    public function register(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'doc_type' => 'required|string',
            'doc_number' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,driver', // Validación para el rol
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $driverUser = new User([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'doc_type' => $request->doc_type,
            'doc_number' => $request->doc_number,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'password' => bcrypt($request->password),
            'company_id' =>$user->company_id,
            'role' => $request->role,
        ]);

        $driverUser->save();
        if($driverUser->role == 'driver'){
            $driver = new Driver();
            $driver->user_id = $driverUser->id;
            $driver->save();
        }
        $driverUser = User::where('id',$driverUser->id)->with('driver')->first();
        return response()->json(['status' =>'OK','message' => 'Conductor registrado con éxito','driver' => $driverUser], 201);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'doc_type' => 'required|string',
            'doc_number' => 'required|string|unique:users',
            'password' => 'string|min:6',
            'role' => 'required|string|in:driver', // Validación para el rol
        ]);

        $driverUser = User::where('id',$request->id)->with('driver')->first();
        if($driverUser){
            $driverUser->update($request->only(['firstname','lastname','email','doc_type','doc_number']));
            if($request->password){
                $driverUser->password = bcrypt($request->password);
                $driverUser->save();

            }
            return response()->json(['status'=>'OK','driver' => $driverUser,], 200);
        }else{
            return response()->json(['status','error','driver' => null,], 401);
        }
    }

    public function get(Request $request, $id)
    {
        $user = $request->user();
        $driver = User::where('id',$id)->where('role','driver')->with(['driver','company'])->first();
        return response()->json(['status' => 'ok','driver'=>$driver], 200);

        }
        public function pendingOrders(Request $request) {
            $user = $request->user();
            $driver = User::where('company_id', $user->company_id)
                ->where('role', 'driver')
                ->with(['driver', 'company'])
                ->first();

            $orders = Order
                ::where('driver_id', $driver->driver->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc') // Ordenar por fecha en orden descendente
                ->get();

            return response()->json(['status' => 'ok', 'orders' => $orders], 200);
        }
        public function orders(Request $request) {
            $user = $request->user();
            $driver = User::where('company_id', $user->company_id)
                ->where('role', 'driver')
                ->with(['driver', 'company'])
                ->first();

            $orders = Order
                ::where('driver_id', $driver->driver->id)
                ->orderBy('status', 'DESC') // Ordena primero por estado (pending primero)
                ->orderBy('created_at', 'desc') // Luego, ordena por fecha (lo más reciente primero)
                ->get();

            return response()->json(['status' => 'ok', 'orders' => $orders], 200);
        }



    function getByDocNumber(Request $request, $docnumber){
        $driver = Driver::where('doc_number',$docnumber)->with('user')->first();
        return response()->json(['status' => 'ok','driver'=>$driver], 200);
    }

    function arrive(Request $request, $id){
        $driver = Driver::find($id);
        if(!$driver){
            return response()->json(['driver'=>null], 401);
        }else{
            $driver->arrived_at = Carbon::now();
            $driver->arrived = 1;
            $driver->save();
            return response()->json(['status' => 'ok','driver'=>$driver], 200);
        }
    }
    function left(Request $request, $id){
        $driver = Driver::find($id);
        if(!$driver){
            return response()->json(['driver'=>null], 401);
        }else{
            $driver->arrived_at = null;
            $driver->arrived = 0;
            $driver->save();

            return response()->json(['status' => 'ok','driver'=>$driver], 200);
        }
    }
}
