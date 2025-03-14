<?php

require_once __DIR__  . "/auth.php";

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\PeripheralController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

Route::resource('/gateway', GatewayController::class)->middleware('api_or_web');
Route::resource('/peripheral', PeripheralController::class)->middleware('api_or_web');


Route::get('/authentication/{provider}', function ($provider) {
    return Socialite::driver($provider)->redirect();
});
Route::get('/authentication/github/callback', function () {

    $provider = 'github';
    $socialUser = Socialite::driver($provider)->user();

    $user = User::updateOrCreate([
        'email' => $socialUser->getEmail(),
    ], [
        'name' => $socialUser->getName(),
        'password' => bcrypt(uniqid()), // Random password
        'provider' => $provider,
        'provider_id' => $socialUser->getId(),
    ]);
    
    Auth::login($user);

    return redirect('/dashboard');
});
