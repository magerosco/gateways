<?php

namespace Tests\Feature\Integration;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthByGitHubTest extends TestCase
{
    public function test_it_redirects_to_github()
    {
        $response = $this->get('/authentication/github');

        $response->assertStatus(302);
    }

    public function test_it_authenticates_a_user_using_github()
    {
        $githubUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $githubUser->shouldReceive('getId')->andReturn('123456');
        $githubUser->shouldReceive('getEmail')->andReturn('github_user@example.com');
        $githubUser->shouldReceive('getName')->andReturn('John Doe');

        Socialite::shouldReceive('driver->user')->andReturn($githubUser);

        $response = $this->get('/authentication/github/callback');

        $this->assertDatabaseHas('users', [
            'email' => 'github_user@example.com',
            'name' => 'John Doe',
            'provider' => 'github',
            'provider_id' => '123456',
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals('github_user@example.com', Auth::user()->email);

        $response->assertRedirect('/dashboard');
    }

    public function getLastLogLine($filePath)
    {
        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = '';

        if ($file->key() > 0) {
            $file->seek($file->key() - 1);
            $lastLine = $file->current();
        }

        return $lastLine;
    }
}
