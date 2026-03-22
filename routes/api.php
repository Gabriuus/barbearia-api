<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json(['message' => 'Sistema de Barbearia API (Laravel 12) está rodando perfeitamente. Utilize o Postman ou Apidog para testar os endpoints REST.']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        $user = clone $request->user();
        $user->role = $request->user()->userType->name;
        return $user;
    });

    // Admin routes
    Route::middleware([\App\Http\Middleware\CheckAdminRole::class])->prefix('admin')->group(function () {
        Route::apiResource('admins', \App\Http\Controllers\AdminController::class);
        Route::apiResource('clients', \App\Http\Controllers\ClientController::class)->except(['store']);
        Route::apiResource('schedulings', \App\Http\Controllers\SchedulingController::class)->except(['store']);
    });

    // Client routes
    Route::middleware([\App\Http\Middleware\CheckClientRole::class])->prefix('client')->group(function () {
        Route::post('/schedulings', [\App\Http\Controllers\SchedulingController::class, 'store']);
        Route::get('/schedulings', [\App\Http\Controllers\SchedulingController::class, 'index']);
        Route::get('/schedulings/{scheduling}', [\App\Http\Controllers\SchedulingController::class, 'show']);
        Route::delete('/schedulings/{scheduling}', [\App\Http\Controllers\SchedulingController::class, 'destroy']);
    });
});
