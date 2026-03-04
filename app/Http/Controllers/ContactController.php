<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190',
            'message' => 'required|string|max:5000',
        ]);

        $platformEmail = (string) config('platform.contact_email', 'sakha2228@gmail.com');

        try {
            $body = "Nouveau message de contact\n\n"
                . "Nom: {$data['name']}\n"
                . "Email: {$data['email']}\n\n"
                . "Message:\n{$data['message']}\n";

            Mail::raw($body, function ($mail) use ($platformEmail, $data) {
                $mail->to($platformEmail)
                    ->replyTo($data['email'], $data['name'])
                    ->subject('Contact site Sakha - ' . $data['name']);
            });
        } catch (\Throwable $e) {
            report($e);

            // Fallback: keep the message even if SMTP is temporarily unavailable.
            try {
                $fallbackLine = json_encode([
                    'at' => now()->toDateTimeString(),
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'message' => $data['message'],
                    'error' => $e->getMessage(),
                ], JSON_UNESCAPED_UNICODE);

                if ($fallbackLine !== false) {
                    File::append(storage_path('logs/contact_fallback.log'), $fallbackLine . PHP_EOL);
                }
            } catch (\Throwable $inner) {
                report($inner);
            }

            return redirect()
                ->to('/contact?contact_status=warning')
                ->with('warning', 'Votre message a ete enregistre, mais l envoi email est temporairement indisponible.');
        }

        return redirect()
            ->to('/contact?contact_status=success')
            ->with('success', 'Votre message a ete envoye avec succes.');
    }
}
