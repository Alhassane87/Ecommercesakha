<?php

use App\Mail\RegistrationWelcomeMail;
use Illuminate\Support\Facades\Mail;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Mail::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    Mail::assertSent(RegistrationWelcomeMail::class, function (RegistrationWelcomeMail $mail) {
        return $mail->hasTo('test@example.com');
    });
});
