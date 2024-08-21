<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::resource('/gateway', GatewayController::class)->middleware('api_or_web');
// Route::resource('/peripheral', PeripheralController::class)->withoutMiddleware('api_or_web');

require __DIR__.'/auth.php';
