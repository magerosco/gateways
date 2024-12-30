<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\GatewayController;

// API V2
Route::group(
    [
        'middleware' => [
            'auth:sanctum', // This middleware will check if the user is authenticated
            'throttle:60,1', // This middleware will limit the number of requests per minute
             'sanitize' // This middleware will sanitize the input
        ]
    ], function () {
    Route::get('/gateway', [GatewayController::class, 'index'])->name('gateway.index');
    Route::get('/gateway/{id}', [GatewayController::class, 'show'])->name('gateway.show');
    Route::post('/gateway', [GatewayController::class, 'store'])->name('gateway.store');
    Route::put('/gateway/{id}', [GatewayController::class, 'update'])->name('gateway.update');
    Route::delete('/gateway/{gateway}', [GatewayController::class, 'destroy'])->name('gateway.destroy');
});

Route::model('gateway', App\Models\Gateway::class);
