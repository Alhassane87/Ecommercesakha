<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $email)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre abonnement à la newsletter - ' . config('app.name', 'Sakha'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.subscription-confirmed',
        );
    }
}
