<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue sur ' . config('app.name', 'Sakha') . ' - Votre compte est créé',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.registration-welcome',
        );
    }
}
