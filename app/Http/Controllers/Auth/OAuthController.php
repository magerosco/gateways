<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\DTO\TokenResponseDTO;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
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

        $token = TokenResponseDTO::fromArray($tokenResponse);

        return new JsonResponse(
            [
                'accessToken' => $token->accessToken,
                'expiresAt' => $token->expiresAt,
                'message' => 'Login success',
            ],
            Response::HTTP_OK,
        );
    }

    public function logout(Request $request)
    {
        Log::info('logout');

        $this->tokenService->revokeExistingTokens($request->user());

        Auth::logout();

        return new JsonResponse(
            [
                'message' => 'Logout success',
            ],
            Response::HTTP_OK,
        );
    }
}
