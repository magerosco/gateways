<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\API\PeripheralController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => ['api_or_web']], function () {
    Route::get('/gateway', [GatewayController::class, 'index'])->name('gateway.index');
    Route::get('/gateway/{id}', [GatewayController::class, 'show'])->name('gateway.show');
    Route::post('/gateway', [GatewayController::class, 'store'])->name('gateway.store');
    Route::put('/gateway/{id}', [GatewayController::class, 'update'])->name('gateway.update');
    Route::delete('/gateway/{id}', [GatewayController::class, 'destroy'])->name('gateway.destroy');

});

Route::resource('/peripheral', PeripheralController::class);
