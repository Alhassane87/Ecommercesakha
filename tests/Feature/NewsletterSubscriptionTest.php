<?php

use App\Mail\NewsletterSubscriptionConfirmed;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;

it('subscribes a new email and sends a confirmation email', function () {
    Mail::fake();

    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'abonne@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'abonne@example.com',
    ]);

    Mail::assertSent(NewsletterSubscriptionConfirmed::class, function (NewsletterSubscriptionConfirmed $mail) {
        return $mail->hasTo('abonne@example.com');
    });
});

it('does not duplicate an existing subscriber', function () {
    Mail::fake();

    NewsletterSubscriber::create([
        'email' => 'deja-abonne@example.com',
        'subscribed_at' => now(),
    ]);

    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'deja-abonne@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Cet email est deja abonne a la newsletter.');

    expect(NewsletterSubscriber::where('email', 'deja-abonne@example.com')->count())->toBe(1);
    Mail::assertNothingSent();
});

