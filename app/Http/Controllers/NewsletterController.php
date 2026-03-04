<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterSubscriptionConfirmed;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower(trim((string) $validated['email']));

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $email],
            ['subscribed_at' => now()]
        );

        if (!$subscriber->wasRecentlyCreated) {
            return back()
                ->with('success', 'Cet email est deja abonne a la newsletter.')
                ->with('newsletter_subscribed', true);
        }

        try {
            Mail::to($email)->send(new NewsletterSubscriptionConfirmed($email));
            return back()
                ->with('success', 'Abonnement effectue avec succes. Un email de confirmation vient de vous etre envoye.')
                ->with('newsletter_subscribed', true);
        } catch (\Throwable $e) {
            report($e);
            return back()
                ->with('warning', 'Abonnement effectue, mais l\'email de confirmation n\'a pas pu etre envoye pour le moment.')
                ->with('newsletter_subscribed', true);
        }
    }
}
