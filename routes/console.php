<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {to? : Recipient email (default: MAIL_FROM_ADDRESS)}', function (?string $to = null) {
    $recipient = trim((string) ($to ?: config('mail.from.address')));

    if ($recipient === '') {
        $this->error('No recipient configured. Set MAIL_FROM_ADDRESS or pass an email.');
        return 1;
    }

    try {
        Mail::raw('SMTP test from Sakha - ' . now()->toDateTimeString(), function ($message) use ($recipient) {
            $message->to($recipient)->subject('SMTP Test - Sakha');
        });

        $this->info('Test email sent to: ' . $recipient);
        return 0;
    } catch (\Throwable $e) {
        report($e);
        $this->error('SMTP send failed: ' . $e->getMessage());
        return 1;
    }
})->purpose('Send a SMTP test email');
