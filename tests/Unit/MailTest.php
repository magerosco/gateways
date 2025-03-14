<?php

namespace Tests\Unit;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class MailTest extends TestCase
{
    public function test_welcome_email_is_sent()
    {
        Mail::fake();

        $user = User::factory()->make([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
        ]);
        Mail::to($user->email)->send(new WelcomeEmail($user));

        Mail::assertSent(WelcomeEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
