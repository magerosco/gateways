<?php

namespace Database\Seeders;

use App\Models\Peripheral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeripheralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peripheral::factory(10)->create();
    }
}
