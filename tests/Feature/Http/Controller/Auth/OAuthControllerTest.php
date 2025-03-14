<?php

namespace Tests\Feature\Http\Controller\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class OAuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh  --env=testing --no-interaction');
        Artisan::call('passport:client --personal --env=testing --no-interaction');
        $this->seed('UserSeeder');
    }

    public function test_can_login_user(): void
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ]);

        $data = [
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'X-Auth-Service' => 'oauth',
            ]
            )->postJson('/api/auth/token', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Login success');
        $response->assertJsonStructure(['message', 'accessToken', 'expiresAt']);

        $accessToken = $response->json('accessToken');
        $this->assertNotEmpty($accessToken);

        $jwtParts = explode('.', $accessToken);
        $this->assertCount(3, $jwtParts); //part1 header, part2 payload, part3 signature

        $keyPath = storage_path(env('PASSPORT_PUBLIC_KEY'));
        if (!file_exists($keyPath)) {
            Log::error("Public key file not found at: " . $keyPath);
        }
        $publicKey = file_get_contents($keyPath);
        $decoded = (array) \Firebase\JWT\JWT::decode($accessToken, new \Firebase\JWT\Key($publicKey, 'RS256'));

        $this->assertEquals($user->id, $decoded['sub']); //sub is user id
    }

    public function test_can_logout_user(): void
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ]);

        $data = [
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'X-Auth-Service' => 'oauth',
            ]
            )->postJson('/api/auth/token', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Login success');
        $response->assertJsonStructure(['message', 'accessToken', 'expiresAt']);

        $accessToken = $response->json('accessToken');
        $this->assertNotEmpty($accessToken);

        $jwtParts = explode('.', $accessToken);
        $this->assertCount(3, $jwtParts); //part1 header, part2 payload, part3 signature

        $keyPath = storage_path(env('PASSPORT_PUBLIC_KEY'));
        if (!file_exists($keyPath)) {
            Log::error("Public key file not found at: " . $keyPath);
        }
        $publicKey = file_get_contents($keyPath);
        $decoded = (array) \Firebase\JWT\JWT::decode($accessToken, new \Firebase\JWT\Key($publicKey, 'RS256'));

        $this->assertEquals($user->id, $decoded['sub']); //sub is user id


        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'X-Auth-Service' => 'oauth',
            ]
        )->postJson('/api/auth/logout');
        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Logout success');

        $loggedInUser = Auth::user();
        $this->assertNull($loggedInUser);
    }
}
