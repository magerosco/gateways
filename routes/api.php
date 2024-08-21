<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\API\PeripheralController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('/gateway', GatewayController::class)->middleware('api_or_web');
Route::resource('/peripheral', PeripheralController::class)->withoutMiddleware('api_or_web');
