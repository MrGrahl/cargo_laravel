<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'doc_type' => 'required|string',
            'doc_number' => 'required|string|unique:users',
            'company_id' => 'required',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,driver', // Validación para el rol
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = new User([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'doc_type' => $request->doc_number,
            'doc_number' => $request->doc_type,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'password' => bcrypt($request->password),
            'company_id' =>$request->company_id,
            'role' => $request->role,
        ]);

        $user->save();
        if($user->role == 'driver'){
            $driver = new Driver();
            $driver->user_id = $user->id;
            $driver->save();
        }

        return response()->json(['message' => 'Usuario registrado con éxito'], 201);
    }

    public function openAccount(Request $request)
    {
        $userValidator = Validator::make($request->all(), [
            'user.firstname' => 'required|string|max:255',
            'user.lastname' => 'required|string|max:255',
            'user.email' => 'required|string|email|max:255|unique:users,email',
            'user.doc_type' => 'required|string',
            'user.doc_number' => 'required|string|unique:users,doc_number',
            'user.password' => 'required|string|min:6',
            'user.role' => 'required|string|in:admin,driver', // Validación para el rol
        ]);

        $companyValidator = Validator::make($request->all(), [
            'company.name' => 'required|string|max:255',
            'company.industry' => 'required|string|max:255',
            'company.registration_number' => 'required|string|max:255',
            'company.country' => 'required|string|max:50',
            'company.city' => 'required|string|max:50',
            'company.state' => 'required|string|max:50',
            'company.address' => 'required|string|max:100',
            'company.postal_code' => 'string|max:50',
        ]);

        if ($userValidator->fails() || $companyValidator->fails()) {
            return response()->json(['errors' => array_merge($userValidator->errors()->toArray(), $companyValidator->errors()->toArray())], 400);
        }

        $user = new User([
            'firstname' => $request->input('user.firstname'),
            'lastname' => $request->input('user.lastname'),
            'email' => $request->input('user.email'),
            'doc_type' => $request->input('user.doc_type'),
            'doc_number' => $request->input('user.doc_number'),
            'phone1' => $request->input('user.phone1'),
            'phone2' => $request->input('user.phone2'),
            'password' => bcrypt($request->input('user.password')),
            'role' => $request->input('user.role'),
        ]);

        if ($request->input('company_id')) {
            $user->company_id = $request->input('company_id');
        }

        if ($request->input('company')) {
            $company = new Company([
                'name' => $request->input('company.name'),
                'industry' => $request->input('company.industry'),
                'registration_number' => $request->input('company.registration_number'),
                'country' => $request->input('company.country'),
                'state' => $request->input('company.state'),
                'city' => $request->input('company.city'),
                'address' => $request->input('company.address'),
                'postal_code' => $request->input('company.postal_code'),
            ]);
            $company->save();
            $user->company_id = $company->id;
        }

        $user->save();

        if($user->role == 'driver'){
            $driver = new Driver();
            $driver->user_id = $user->id;
            $driver->save();
        }

        return response()->json(['status' => 'OK', 'message' => 'Usuario registrado con éxito'], 201);
    }
    // Login de usuario
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('Access Token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }
    public function driverLogin(Request $request)
    {


        if ($request->role != 'driver') {
            return response()->json([], 400);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('Access Token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }
    public function verifyAuthentication(Request $request)
    {
        // Verificar si el usuario está autenticado
        if ($request->user()) {
            return response()->json(['auth'=>true,'message' => 'Autenticado'], 200);
        } else {
            return response()->json(['auth'=>false,'message' => 'No autenticado'], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Sesión cerrada correctamente'],200);
    }

}
