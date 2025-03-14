<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Image::factory()->create([
            'path' => 'https://via.placeholder.com/150',
            'imageable_type' => Post::class,
            'imageable_id' => 1,
        ]);

        Image::factory()->create([
            'path' => 'https://via.placeholder.com/150',
            'imageable_type' => User::class,
            'imageable_id' => 1,
        ]);
    }
}
