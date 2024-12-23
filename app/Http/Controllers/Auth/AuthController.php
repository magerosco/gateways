<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Contracts\TokenServiceInterface;

class AuthController extends Controller
{
    public function __construct(protected TokenServiceInterface $tokenService)
    {
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['data' => $user, 'message' => 'User created successfully']);
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->credentials())) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        // Generate token by sanctum service
        $tokenResponse = $this->tokenService->generateTokenForUser($user);

        return response()->json(['token' => $tokenResponse]);
    }
}
