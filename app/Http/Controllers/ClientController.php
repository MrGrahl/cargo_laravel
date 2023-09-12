<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    function index(Request $request){
        $user = $request->user();
        $clients = Client::where('company_id',$user->company_id)->get();
        return response()->json(['status' => 'ok','clients'=>$clients], 200);
    }
    function getByDocNumber(Request $request, $docnumber){
        $client = Client::where('doc_number',$docnumber)->first();
        return response()->json(['status' => 'ok','client'=>$client], 200);
    }
}
