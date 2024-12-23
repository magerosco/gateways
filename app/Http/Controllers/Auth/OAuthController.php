<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Contracts\TokenServiceInterface;

class OAuthController extends Controller
{
    public function __construct(protected TokenServiceInterface $tokenService)
    {
    }

    public function getAccessToken(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->credentials())) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        // Generate token by passport service
        $tokenResponse = $this->tokenService->generateTokenForUser($user);

        return response()->json($tokenResponse);
    }
}
