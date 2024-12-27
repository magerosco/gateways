<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personalAccessClient = Client::create([
            'name' => 'Personal Access Client',
            'secret' => Str::random(40),
            'redirect' => env('APP_URL'),
            'personal_access_client' => true,
            'password_client' => false,
            'revoked' => false,
        ]);

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $personalAccessClient->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
