<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh --seed --env="testing"');
    }

    public function test_can_create_order(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/order/processOrder', [
            'user_id' => $user->id,
            'payment_method' => 'valid_card',
            'coupon' => 'valid_coupon',
            'products' => [['id' => 1, 'quantity' => 2], ['id' => 2, 'quantity' => 1]],
        ]);

        $response->assertStatus(201);

    }
}
