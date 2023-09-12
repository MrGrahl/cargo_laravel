<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Registro de usuario
    Route::post('register', [AuthController::class, 'register']);
    // Apertura de cuenta
    Route::post('open-account', [AuthController::class, 'openAccount']);
    // Inicio de sesión
    Route::post('login', [AuthController::class, 'login']);
    Route::post('driver-login', [AuthController::class, 'driverLogin']);
    // Obtener información del usuario autenticado
    Route::middleware(['auth:api'])->get('/user', function (Request $request) {
        $user = $request->user();
        $user->load('company');
        return $user;
    });
    Route::middleware(['auth:api'])->get('/user-driver', function (Request $request) {
        $user = $request->user();

        $user->load('company');
        $user->load('driver');
        return $user;
    });
    // Verificar autenticación
    Route::middleware(['auth:api'])->get('verify', [AuthController::class, 'verifyAuthentication']);

    // Cerrar sesión
    Route::middleware(['auth:api'])->post('logout', [AuthController::class, 'logout']);

});

Route::prefix('companies')->group(function () {
    // Registro de empresa
    Route::put('/register', [CompanyController::class, 'register'])->middleware('auth:api');
    // Obtener información de una empresa por ID
    Route::get('/{id}', [CompanyController::class, 'get'])->where('id', '[0-9]+')->middleware('auth:api');
    // Obtener lista de empresas
    Route::get('/list', [CompanyController::class, 'index'])->middleware('auth:api');
});

Route::prefix('drivers')->group(function () {
    // Registro de conductor
    Route::put('/register', [DriverController::class, 'register'])->middleware('auth:api');
    // Actualizar información del conductor
    Route::post('/update', [DriverController::class, 'update'])->middleware('auth:api');
    // Obtener información de un conductor por ID
    Route::get('/{id}', [DriverController::class, 'get'])->where('id', '[0-9]+')->middleware('auth:api');
    // Obtener lista de conductores
    Route::get('/list', [DriverController::class, 'index'])->middleware('auth:api');
    // Obtener información de un pedido asignado a un conductor
    Route::get('/orders', [DriverController::class, 'orders'])->middleware('auth:api');
    Route::get('/orders/pending', [DriverController::class, 'pendingOrders'])->middleware('auth:api');
    // Obtener información de un conductor por número de documento
    Route::get('/docnumber/{docnumber}', [DriverController::class, 'getByDocNumber'])->middleware('auth:api');
});

Route::prefix('orders')->group(function () {
    // Crear un nuevo pedido
    Route::put('/store', [OrderController::class, 'store'])->middleware('auth:api');
    // Obtener información de un pedido por ID
    Route::get('/{id}', [OrderController::class, 'get'])->where('id', '[0-9]+')->middleware('auth:api');
    // Cambiar el estado de una orden
    Route::post('/{id}/change-status/{status}', [OrderController::class, 'changeStatus'])->middleware('auth:api');
    // Obtener lista de pedidos
    Route::get('/list', [OrderController::class, 'index'])->middleware('auth:api');
});

Route::prefix('clients')->group(function () {
    // Obtener información de un cliente por número de documento
    Route::get('/docnumber/{docnumber}', [ClientController::class, 'getByDocNumber'])->middleware('auth:api');
    // Obtener lista de clientes
    Route::get('/list', [ClientController::class, 'index'])->middleware('auth:api');
});

Route::prefix('dashboard')->group(function () {
    // Obtener información del panel de control
    Route::get('/index', [DashboardController::class, 'index'])->middleware('auth:api');
});
