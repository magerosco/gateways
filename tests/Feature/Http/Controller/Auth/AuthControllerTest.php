<?php

namespace Tests\Feature\Http\Controller\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class AuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh  --env=testing');
    }
    public function test_can_register_user(): void
    {
        $data = [
            'name' => 'Test',
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/register', $data);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', $data['name']);
        $response->assertJsonPath('data.email', $data['email']);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $user = $response->json('data');

        $user = User::with('tokens')->find($user['id']);

        $this->assertNotEmpty($user->tokens);

        /**
         * EXAMPLE:
        * The systems must create a log when the user is created in:
        * logging.channels.registered_user.path

        * Ex: Log::channel('registered_user')->info('Sending welcome email to ' ,['user_email' => $data['email']] );

        * We must not use the static url, this should use the one defined in the config file
        * $logFile = storage_path('logs/registeredUser/mi_log.log');
        * $logs = $this->getLastLogLine($logFile); // Only the last line
        * // $logs = File::get($logFile); //Full file
        */

        sleep(1);

        $logPath = Config::get('logging.channels.registered_user.path');
        $logs = $this->getLastLogLine($logPath); // Only the last line

        $this->assertStringContainsString('Sending welcome email to ', $logs);
        $this->assertStringContainsString($data['email'], $logs);
        $this->assertStringContainsString($user['created_at'], $logs);
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

    public function test_can_login_user(): void
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ]);

        $data = [
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/login', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Login success');
        $response->assertJsonStructure(['message', 'token']);
    }

    public function test_can_not_login_user_with_invalid_credentials(): void
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ]);

        $data = [
            'email' => 'Vb4V8@example.com',
            'password' => 'wrong-password',
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/login', $data);

        $response->assertStatus(401);
        $response->assertJsonPath('message', 'Invalid credentials');
    }

    public function test_can_logout_user(): void
    {
        User::factory()->create([
            'name' => 'test',
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ]);

        $data = [
            'email' => 'Vb4V8@example.com',
            'password' => 'password',
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])->postJson('/api/login', $data);

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Login success');
        $response->assertJsonStructure(['message', 'token']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $response->json('token'),
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJsonPath('message', 'Logout success');
    }
}
