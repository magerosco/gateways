<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct( public User $user)
    {
        //
    }
    public function build()
    {
        return $this->subject('Welcome!')
                    ->view('emails.welcome')
                    ->with([
                        'userName' => $this->user->name,
                    ]);
    }
}
