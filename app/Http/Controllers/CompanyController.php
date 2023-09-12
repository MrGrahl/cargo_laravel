<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

    public function index(Request $request){
        $companies = Company::all();
        return response()->json(['status' => 'ok','companies'=>$companies], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'country' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'postal_code' => 'string|max:50',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $company = new Company(
            $request->only([
                'name',
                'industry',
                'registration_number',
                'country',
                'address',
                'city',
                'state',
                'postal_code'
            ])
        );
        $company->save();

        return response()->json(['status' => 'ok','company'=>$company], 200);
    }

    public function get(Request $request, $id)
    {
        $company = Company::find($id);
        return response()->json(['status' => 'ok','company'=>$company], 200);
    }
}
