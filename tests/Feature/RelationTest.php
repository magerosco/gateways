<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RelationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh --seed --env=testing');
    }

    public function test_a_user_has_many_posts()
    {
        $user = User::factory()->create();
        $post1 = Post::factory()->create(['user_id' => $user->id]);
        $post2 = Post::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->posts);
        $this->assertTrue($user->posts->contains($post1));
        $this->assertTrue($user->posts->contains($post2));
    }

    public function test_a_user_has_many_categories_through_posts()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Post::factory(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertCount(3, $user->categories);
        $this->assertTrue($user->categories->contains($category));
    }

    public function test_with_count_works_for_user_has_many_categories_through_posts()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Post::factory(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // EXAMPLE: withCount
        $result = User::withCount('posts')->find($user->id);

        // in this case the withCount should add a new field called posts_count
        $this->assertCount(3, $result->posts);
        $this->assertSame(3, $result->posts_count);
    }

    public function test_a_video_morph_many_tags()
    {
        $video = Video::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $video->tags()->attach($tag1->id);
        $video->tags()->attach($tag2->id);

        $this->assertCount(2, $video->tags);
    }

    public function test_a_post_morph_many_tags()
    {
        $post = Post::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();
        $tag3 = Tag::factory()->create();

        $post->tags()->attach($tag1->id);
        $post->tags()->attach($tag2->id);
        $post->tags()->attach($tag3->id);

        $this->assertCount(3, $post->tags);
    }

    public function test_where_has_works_as_expected()
    {
        $user = User::factory()->create();
        $faker = Faker::create();
        $category = Category::create([
            'name' => $faker->word(),
        ]);

        Post::factory(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);


        /** EXAMPLE:
         * 1. important specify the relationship, each name must a defined origin,
         * Ex: name could be found in the user table or the profile table.
         *
         * 2. I consider a good practice to get the relationship name from the model instead of writing it manually
         * Ex: $category->getTable() instead of 'categories'
        */
        $users = User::whereHas('posts.category', function ($query) use ($category) {
            $query->where($category->getTable().'.name', 'like', $category->name);
        })->get();

        $this->assertCount(1, $users);
    }

    public function test_transactions_works_as_expected()
    {

        DB::beginTransaction(); // (A) Atomicity - The transaction begins, treating all operations as a single unit.

        try {
            // (C) Consistency - The database enforces rules (e.g., foreign key constraints).
            // Creating a user ensures that related posts have a valid user_id.
            $newUser = User::factory()->create();
            $category = Category::factory()->create();

            // (R) Read Committed - Only committed data is visible to other transactions.
            $user =  User::where('id', $newUser->id)->lockForUpdate()->first();

            // (I) Isolation - These changes are not visible to other transactions until commit.
            Post::factory(3)->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
            ]);

            DB::commit(); // (D) Durability - Once committed, changes are permanently stored.

        } catch (\Exception $e) {
            // (I) Isolation - If an error occurs, other transactions won't see partial changes.
            \Illuminate\Support\Facades\Log::error($e->getMessage());

            DB::rollBack(); // (A) Atomicity - If something goes wrong, all changes are undone.
        }


        $this->assertNotEquals('test', User::find($user->id)->name);
    }

    public function test_add_select_function_works_as_expected()
    {
        User::factory()->create();

        $user = User::select('id', 'name')
            ->addSelect([DB::raw("'test' as test", 'test')])
            ->get()
            ->first();

        $this->assertEquals('test', $user->test);
    }

    public function test_scope_title_works_for_post()
    {
        $post = Post::factory()->create();

        $this->assertEquals($post->title, Post::title($post->title)->get()->first()->title);
    }

    public function test_scope_title_with_lazy_works_for_post()
    {
        $posts = Post::title('n')->lazy();

        $this->assertInstanceOf(\Illuminate\Support\LazyCollection::class, $posts);

        $posts->each(function ($post) {
            $this->assertInstanceOf(Post::class, $post);
            $this->assertStringContainsString('n', $post->title);
        });
    }
    public function test_scope_title_with_chunks_works_for_post()
    {
        Post::chunk(2, function ($posts) {
            $this->assertInstanceOf(\Illuminate\Support\Collection::class, $posts);

            $posts->each(function ($post) {
                $this->assertInstanceOf(Post::class, $post);
            });
        });
    }

    public function test_cursor_works_for_user()
    {
        $users = User::cursor();

        $this->assertInstanceOf(\Illuminate\Support\LazyCollection::class, $users);

        $users->each(function ($user) {
            $this->assertInstanceOf(User::class, $user);
        });
    }
}
