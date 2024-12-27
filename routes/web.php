<?php

require_once __DIR__  . "/auth.php";

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\PeripheralController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::resource('/gateway', GatewayController::class)->middleware('api_or_web');
Route::resource('/peripheral', PeripheralController::class)->middleware('api_or_web');


