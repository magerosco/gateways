<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\Gateway;

class GatewayTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_set_database_config(): void
    {
        Artisan::call('migrate:fresh --seed --env="testing"');
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_get_gateway_list(): void
    {
        $response = $this->get('/api/gateway/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at']],
        ]);

        $response->assertJsonCount(5, 'data');
    }

    public function test_get_gateway_detail(): void
    {

        $response = $this->get('/api/gateway/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at']]);
        $response->assertJsonFragment(['id' => 1]);
    }

    public function test_get_gateway_non_existing_gateway_detail(): void
    {
        $response = $this->get('/api/gateway/9999');
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

        $response = $this->postJson('/api/gateway', $data);

        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'message',
        ]);

        $response->assertJsonFragment($data);
        $response->assertStatus(201);
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
        $response = $this->putJson("/api/gateway/$id", $updatedData);

        $response->assertJsonStructure([
            'data' => ['id', 'serial_number', 'name', 'IPv4_address', 'peripheral', 'created_at', 'updated_at'],
            'message',
        ]);

        $response->assertJsonFragment($updatedData);
        $response->assertStatus(200);
    }
}
