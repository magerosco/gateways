<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Gateway;
use App\Traits\RoleScopeMapper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Anasa\ResponseStrategy\AdditionalDataRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GatewayTest extends TestCase
{
    use RoleScopeMapper;

    protected $user;
    protected $sanctumToken;
    protected $oauthToken;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh --seed --env="testing"');

        $this->user = \App\Models\User::where('name', 'admin')->first();
        if (empty($this->user)) {
            $this->user = \App\Models\User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => 'admin',
            ]);
        }

        // Oauth token generation
        $scopes = $this->determineScopesBasedOnRole($this->user->getRoleNames()->all());
        $tokenFactory = app(\Laravel\Passport\PersonalAccessTokenFactory::class);
        $oauthToken = $tokenFactory->make($this->user->getKey(), 'oauthTestToken', $scopes);
        $this->oauthToken = 'Bearer ' . $oauthToken->accessToken;

        // Sanctum token generation
        $sanctumToken = $this->user->createToken('SanctumTestToken');
        $this->sanctumToken = 'Bearer ' . $sanctumToken->plainTextToken;

        $service = AdditionalDataRequest::getInstance();
        $service->setMethod('API');
    }
    
    public function test_get_gateway_list(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->sanctumToken])->get('/api/gateway/');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at']],
        ]);

        $response->assertJsonCount(5, 'data');
    }

    public function test_get_gateway_detail(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->oauthToken])->get('/api/gateway/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at']]);
        $response->assertJsonFragment(['id' => 1]);
    }

    public function test_get_gateway_non_existing_gateway_detail(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->oauthToken])->get('/api/gateway/9999');
        $response->assertStatus(404);
    }

    public function test_can_store_gateway()
    {
        $data = [
            'serial_number' => '12345678',
            'name' => 'Test Gateway',
            'IPv4_address' => '192.168.0.1',
            'peripheral' => [],
        ];

        $response = $this->withHeaders(['Authorization' => $this->sanctumToken])->postJson('/api/gateway', $data);

        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'message',
        ]);

        $response->assertJsonFragment($data);
        $response->assertStatus(201);
    }

    public function test_store_gateway_validation_fail()
    {
        $data = [
            'serial_number' => '', // Missing required serial number
            'name' => '', // Missing required name
            'IPv4_address' => 'invalid_address', // Invalid IPv4
            'peripheral' => [],
        ];

        $response = $this->withHeaders(['Authorization' => $this->sanctumToken])->postJson('/api/gateway', $data);

        $response->assertStatus(422); // Expecting validation errors
        $response->assertJsonStructure(['success', 'message', 'data' => ['serial_number', 'IPv4_address']]);

        $response->assertJsonFragment([
            'serial_number' => ['The serial number field is required.'],
        ]);

        $response->assertJsonFragment([
            'IPv4_address' => ['IPv4_address is invalid.'],
        ]);
    }

    public function test_can_update_gateway()
    {
        $gateway = Gateway::factory()->create();
        $id = $gateway->id;

        $updatedData = [
            'serial_number' => '88888888',
            'IPv4_address' => '192.168.0.2',
            'name' => 'Updated Gateway',
        ];
        $response = $this->withHeaders(['Authorization' => $this->sanctumToken])->putJson("/api/gateway/$id", $updatedData);

        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'message',
        ]);

        $response->assertJsonFragment($updatedData);
        $response->assertStatus(200);
    }

    public function test_can_destroy_gateway()
    {
        $gateway = Gateway::factory()->create();
        $id = $gateway->id;

        $response = $this->withHeaders(['Authorization' => $this->sanctumToken])->deleteJson("/api/gateway/$id");

        $response->assertJsonStructure([
            'data' => [],
            'message',
        ]);

        $response->assertStatus(200);
    }

    public function test_user_with_no_permission_cannot_create_gateway()
    {
        $userWithoutPermission = \App\Models\User::factory()->create([
            'name' => 'userWithoutDeletePermission',
            'email' => 'userWithoutDeletePermission@test.com',
            'password' => 'userWithoutDeletePermission',
        ]);

        $sanctumToken = 'Bearer ' . $userWithoutPermission->createToken('TestToken')->plainTextToken;

        $gateway = Gateway::factory()->create();
        $id = $gateway->id;

        $response = $this->withHeaders(['Authorization' => $sanctumToken])->deleteJson("/api/gateway/$id");
        $response->assertJsonStructure([
            'data' => [],
            'message',
        ]);
        $response->assertStatus(403); // Forbidden, no permission to create
    }

    public function test_get_gateway_list_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/gateway/');
        $response->assertStatus(401); // Expecting unauthorized status
    }
}
