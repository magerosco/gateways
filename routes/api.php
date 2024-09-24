<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\PeripheralController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::group(['middleware' => ['api_or_web', 'auth:sanctum']], function () {
    Route::get('/gateway', [GatewayController::class, 'index'])->name('gateway.index');
    Route::get('/gateway/{id}', [GatewayController::class, 'show'])->name('gateway.show');
    Route::post('/gateway', [GatewayController::class, 'store'])->name('gateway.store');
    Route::put('/gateway/{id}', [GatewayController::class, 'update'])->name('gateway.update');
    Route::delete('/gateway/{gateway}', [GatewayController::class, 'destroy'])->name('gateway.destroy')
    ->middleware('gateway_action:delete');

    Route::get('/peripheral', [PeripheralController::class, 'index'])->name('peripheral.index');
    Route::get('/peripheral/{id}', [PeripheralController::class, 'show'])->name('peripheral.show');
    Route::post('/peripheral', [PeripheralController::class, 'store'])->name('peripheral.store');
    Route::put('/peripheral/{id}', [PeripheralController::class, 'update'])->name('peripheral.update');
    Route::delete('/peripheral/{peripheral}', [PeripheralController::class, 'destroy'])->name('peripheral.destroy');
});

Route::model('gateway', App\Models\Gateway::class);
