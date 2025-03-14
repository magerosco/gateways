<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{

    protected $user;
    protected $sanctumToken;

    public function setUp(): void
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
        $this->sanctumToken = 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken;

    }

    public function test_can_list_post(): void
    {
        $post = Post::get()->take(10);
        $response = $this->withHeaders(['Accept' => 'application/json'])->get('/api/post');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure(['data' => ['*' => ['id', 'title','user_name', 'user_id' , 'body', 'created_at', 'updated_at']]]);
        $response->assertJsonCount(10, 'data');
        $response->assertSee($post[0]->title);
        $response->assertSee($post[0]->body);
        $response->assertJsonPath('data.0.id', $post[0]->id);
        $response->assertJsonPath('data.0.title', $post[0]->title);
        $response->assertJsonPath('data.0.body', $post[0]->body);
        $response->assertJsonPath('data.0.user_id', $post[0]->user_id);
        $response->assertJsonPath('data.0.user_name', $post[0]->user->name);


        $this->assertDatabaseCount('posts', 10);
        $this->assertDatabaseHas('posts', [
            'title' => $post->first()->title,
            'body' => $post->first()->body,
            'user_id' => $post->first()->user_id,
        ]);
    }

    public function test_can_store_post(): void
    {

        $category = Category::factory()->create();

        $data = [
            'title' => 'Test',
            'body' => 'Test',
            'user_id' => 1,
            'category_id' => $category->id
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/post', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);
        $response->assertJsonStructure(['message', 'data' => ['id', 'title', 'body', 'created_at', 'updated_at']]);
    }

    public function test_can_not_store_post_without_correct_data(): void
    {
        // title required
        $data = [
            'body' => 'Test',
            'user_id' => 1,
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/post', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');

        // Body required
        $data = [
            'title' => 'Test',
            'user_id' => 1,
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/post', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('body');

        // Title max 255
        $data = [
            'title' => str_repeat('a', 256),
            'body' => 'Test',
            'user_id' => 1,
        ];
        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/post', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment(['title' => ["Too long! The title may not be greater than 255 characters."]]);
        $response->assertJsonValidationErrors('title');

    }

    public function test_can_delete_post(): void
    {

        $post = Post::factory()->create();
        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->sanctumToken
            ]
            )->deleteJson('/api/post/' . $post->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Post deleted']);
    }

    public function test_user_with_no_permission_cannot__delete_post(): void
    {

        $userWithoutPermission = \App\Models\User::factory()->create([
            'name' => 'userWithoutDeletePermission',
            'email' => 'userWithoutDeletePermission@test.com',
            'password' => 'userWithoutDeletePermission',
        ]);

        $sanctumToken = 'Bearer ' . $userWithoutPermission->createToken('TestToken')->plainTextToken;

        $post = Post::factory()->create();
        $response = $this->withHeaders(
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' .$sanctumToken
            ]
            )->deleteJson('/api/post/' . $post->id);

        $response->assertStatus(403);
        $response->assertJsonFragment(['message' => 'You are not authorized.']);
    }
}
