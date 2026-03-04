<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationWelcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $welcomeMailSent = true;

        try {
            Mail::to($user->email)->send(new RegistrationWelcomeMail($user));
        } catch (\Throwable $e) {
            report($e);
            $welcomeMailSent = false;
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with(
            $welcomeMailSent ? 'success' : 'warning',
            $welcomeMailSent
                ? 'Compte cree avec succes. Un email de bienvenue vous a ete envoye.'
                : 'Compte cree avec succes, mais l\'email de bienvenue n\'a pas pu etre envoye pour le moment.'
        );
    }
}
