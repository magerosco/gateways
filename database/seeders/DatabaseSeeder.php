<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GatewaySeeder::class,
            PeripheralSeeder::class,
            RolePermissionSeeder::class,
            PassportClientSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            ImageSeeder::class,
        ]);
    }
}
