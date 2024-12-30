<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\PeripheralController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// OAuth
Route::post('auth/token', [OAuthController::class, 'getAccessToken']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// API V2, this route will be check and modified by APIVersionMiddleware using the Accept-Version header
Route::prefix('v2')->group(base_path('routes/api_v2.php'));

// API
Route::group(
    [
        'middleware' => [
            'api_version', // This middleware will check the Accept-Version header and redirect to the correct version
            'api_or_web', // This middleware will check if the request is coming from an API or a web browser
            'auth:sanctum', // This middleware will check if the user is authenticated
            'throttle:60,1', // This middleware will limit the number of requests per minute
            'sanitize' // This middleware will sanitize the input
        ]
    ],
    function () {
    Route::get('/gateway', [GatewayController::class, 'index'])->name('gateway.index');

    Route::get('/gateway/{id}', [GatewayController::class, 'show'])->name('gateway.show')
    ->withoutMiddleware('auth:sanctum')
    ->middleware(['auth:api','scope:manage-users,view-profile']);

    Route::post('/gateway', [GatewayController::class, 'store'])->name('gateway.store');
    Route::put('/gateway/{id}', [GatewayController::class, 'update'])->name('gateway.update');
    Route::delete('/gateway/{gateway}', [GatewayController::class, 'destroy'])->name('gateway.destroy')
    ->middleware('gateway_action:delete');

    Route::get('/peripheral', [PeripheralController::class, 'index'])->name('peripheral.index');
    Route::get('/peripheral/{id}', [PeripheralController::class, 'show'])->name('peripheral.show');
    Route::post('/peripheral', [PeripheralController::class, 'store'])->name('peripheral.store');
    Route::put('/peripheral/{id}', [PeripheralController::class, 'update'])->name('peripheral.update');
    Route::delete('/peripheral/{peripheral}', [PeripheralController::class, 'destroy'])->name('peripheral.destroy')
    ->middleware('role_or_permission:admin');
});

Route::model('gateway', App\Models\Gateway::class);
Route::model('peripheral', App\Models\Peripheral::class);
