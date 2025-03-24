<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use App\Models\Gateway;
use Illuminate\Support\Facades\Artisan;

class ApiV2GatewayTest extends TestCase
{
    protected $user;
    protected $token;

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
        $this->token = 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken;
    }

    public function test_get_gateway_list(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->token])->get('/api/v2/gateway/');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at']],
            'origin',
        ]);

        $response->assertJsonCount(5, 'data');
    }

    public function test_get_gateway_list_by_accept_version_header(): void
    {
        $response = $this->withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
            'Accept-Version' => 'v2',
        ])->get('/api/gateway/');

        //Check redirection
        $response->assertStatus(302);

        //Set redirect url
        $redirectUrl = $response->headers->get('Location');

        $response = $this->get($redirectUrl);

        //After the redirection, it must have the same response as test_get_gateway_list
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at']],
            'origin',
        ]);

        $response->assertJsonCount(5, 'data');
    }
    public function test_get_unsupported_api_version_by_wrong_header(): void
    {
        $response = $this->withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
            'Accept-Version' => 'v3',
        ])->get('/api/gateway/');

        $response->assertStatus(400);
        $response->assertJsonStructure(['error']);
    }
    public function test_get_gateway_detail(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->token])->get('/api/v2/gateway/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'origin',
        ]);
        $response->assertJsonFragment(['id' => 1]);
    }

    public function test_get_gateway_non_existing_gateway_detail(): void
    {
        $response = $this->withHeaders(['Authorization' => $this->token])->get('/api/v2/gateway/9999');
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

        $response = $this->withHeaders(['Authorization' => $this->token])->postJson('/api/v2/gateway', $data);

        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'message',
            'origin',
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

        $response = $this->withHeaders(['Authorization' => $this->token])->postJson('/api/v2/gateway', $data);

        $response->assertStatus(422); // Expecting validation errors
        $response->assertJsonStructure(['success', 'message', 'data' => ['serial_number', 'IPv4_address']]);

        $response->assertJsonFragment([
            'serial_number' => ['The serial number is required.'],
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
        $response = $this->withHeaders(
            [
                'Authorization' => $this->token,
                'Accept-Version' => 'v2'
            ]
        )->putJson("/api/v2/gateway/$id", $updatedData);

        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'message',
            'origin',
        ]);

        $response->assertJsonFragment($updatedData);
        $response->assertStatus(200);
    }

    public function test_can_destroy_gateway()
    {
        $gateway = Gateway::factory()->create();
        $id = $gateway->id;

        $response = $this->withHeaders(['Authorization' => $this->token])->deleteJson("/api/v2/gateway/$id");

        $response->assertJsonStructure(['message', 'origin']);

        $response->assertStatus(200);
    }

    public function test_user_with_no_permission_cannot_destroy_gateway()
    {
        $userWithoutPermission = \App\Models\User::factory()->create([
            'name' => 'userWithoutDeletePermission',
            'email' => 'userWithoutDeletePermission@test.com',
            'password' => 'userWithoutDeletePermission',
        ]);

        $token = 'Bearer ' . $userWithoutPermission->createToken('TestToken')->plainTextToken;

        $gateway = Gateway::factory()->create();
        $id = $gateway->id;

        $response = $this->withHeaders(['Authorization' => $token])->deleteJson("/api/v2/gateway/$id");

        $response->assertJsonStructure(['message']);
        $response->assertStatus(403); // Forbidden, no permission to create
    }

    public function test_get_gateway_list_unauthorized()
    {
        /**
         * Test unauthorized access to the gateway list
         *
         * @return void
         */
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v2/gateway/');
        $response->assertStatus(401); // Expecting unauthorized status
    }
}
