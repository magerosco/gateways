<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(4)->create();
        User::factory()->create([
            'name' => 'Tester User',
            'email' => 'tester@example.com',
            'password' => '12345678',
        ]);

    }
}
