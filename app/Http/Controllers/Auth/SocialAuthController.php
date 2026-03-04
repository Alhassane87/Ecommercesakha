<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        if (! class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            abort(500, 'Socialite package not installed. Run: composer require laravel/socialite');
        }

        // Vérifier si les identifiants sont configurés
        $clientId = config('services.' . $provider . '.client_id');
        $clientSecret = config('services.' . $provider . '.client_secret');
        $redirect = config('services.' . $provider . '.redirect');

        if (!$clientId || !$clientSecret) {
            return redirect()->route('login')->with('error', 'Configuration OAuth manquante pour ' . $provider . '. Veuillez contacter l\'administrateur.');
        }

        try {
            return \Laravel\Socialite\Facades\Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur de configuration OAuth: ' . $e->getMessage());
        }
    }

    public function callback(string $provider)
    {
        if (! class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            abort(500, 'Socialite package not installed. Run: composer require laravel/socialite');
        }

        try {
            $socialUser = \Laravel\Socialite\Facades\Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur lors de l\'authentification avec ' . $provider . ': ' . $e->getMessage());
        }

        // find or create a user
        $user = User::where('email', $socialUser->getEmail())->first();
        if (! $user) {
            try {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Social User',
                    'email' => $socialUser->getEmail(),
                    // generate a random password (user can reset later)
                    'password' => bcrypt(Str::random(24)),
                ]);
            } catch (\Exception $e) {
                return redirect()->route('login')->with('error', 'Erreur lors de la création du compte: ' . $e->getMessage());
            }
        }

        Auth::login($user, true);

        // redirect to intended (dashboard or admin)
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
