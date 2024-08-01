<?php

namespace Tests\Feature;

use App\Models\Peripheral;
use App\Models\Gateway;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PeripheralTest extends TestCase
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

    public function test_get_peripheral_list(): void
    {
        $response = $this->get('/api/peripheral/');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [['id', 'UID', 'vendor', 'status', 'gateway_id', 'created_at', 'updated_at']],
        ]);

        $response->assertJsonCount(10, 'data');
    }

    public function test_get_peripheral_detail(): void
    {
        $response = $this->get('/api/peripheral/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['id', 'UID', 'vendor', 'status', 'gateway_id', 'created_at', 'updated_at']]);
        $response->assertJsonFragment(['id' => 1]);
    }

    public function test_get_peripheral_non_existing_peripheral_detail(): void
    {
        $response = $this->get('/api/peripheral/9999');
        $response->assertStatus(404);
    }

    public function test_can_store_peripheral()
    {
        $gateway = Gateway::factory()->create();
        $gateway_id = $gateway->id;

        $data = [
            'UID' => '123456',
            'vendor' => 'vendor UPDATED',
            'gateway_id' => $gateway_id,
            'status' => 'online',
        ];

        $response = $this->postJson('/api/peripheral', $data);

        $response->assertJsonStructure([
            'data' => ['id', 'UID', 'vendor', 'status', 'gateway_id', 'created_at', 'updated_at'],
            'message',
        ]);

        $response->assertJsonFragment($data);
        $response->assertStatus(201);
    }
    public function test_cantnot_store_invalid_status_peripheral()
    {
        $gateway = Gateway::factory()->create();
        $gateway_id = $gateway->id;

        $data = [
            'UID' => '123456',
            'vendor' => 'vendor UPDATED',
            'gateway_id' => $gateway_id,
            'status' => 'invalid',
        ];

        $response = $this->postJson('/api/peripheral', $data);

        $response->assertJsonStructure(['success', 'data' => ['status'], 'message']);

        $response->assertJsonFragment(['status' => ['status is invalid.']]);
        $response->assertStatus(400);
    }
    public function test_exceds_limit_peripheral_for_gatewy()
    {
        $gateway = Gateway::factory(11)->create();
        foreach ($gateway as $key => $value) {
            $gateway_id = $value->id;
            $data = [
                'UID' => fake()->randomNumber(6),
                'vendor' => fake()->company(),
                'gateway_id' => $gateway_id,
                'status' => 'online',
            ];
            if ($key < 11) {
                $response = $this->postJson('/api/peripheral', $data);
                $response->assertStatus(201);
                $response->assertJsonStructure(['data' => ['id', 'UID', 'vendor', 'status', 'gateway_id', 'created_at', 'updated_at']]);
                continue;
            }

            $response = $this->postJson('/api/peripheral', $data);
            $response->assertJsonStructure(['success', 'data' => ['status'], 'message']);
            $response->assertJsonFragment(['status' => ["gateway_id can't be associated with more than 10 gateways."]]);
            $response->assertStatus(400);
        }
    }

    public function test_can_update_peripheral()
    {
        $gateway = Gateway::factory()->create();
        $gateway_id = $gateway->id;

        $peripheral = Peripheral::factory()->create();
        $id = $peripheral->id;

        $updatedData = [
            'UID' => '123456',
            'vendor' => 'vendor TEST CREATED',
            'gateway_id' => $gateway_id,
            'status' => 'offline',
        ];
        $response = $this->putJson("/api/peripheral/$id", $updatedData);

        $response->assertJsonStructure([
            'data' => ['id', 'UID', 'vendor', 'status', 'gateway_id', 'created_at', 'updated_at'],
            'message',
        ]);

        $response->assertJsonFragment($updatedData);
        $response->assertStatus(200);
    }
}
