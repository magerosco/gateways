<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::factory(10)->create();

        $posts = Post::all();

        foreach ($posts as $post) {

            $post->tags()->attach(
                $tags->random(rand(1, min(5, $tags->count())))
                ->pluck('id')
                ->toArray()
            );
        }

        $videos = Video::all();

        foreach ($videos as $video) {

            $video->tags()->attach(
                $tags->random(rand(1, min(5, $tags->count())))
                ->pluck('id')
                ->toArray()
            );
        }
    }
}
