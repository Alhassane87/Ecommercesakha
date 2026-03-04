<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Rediriger l'utilisateur vers Google pour l'authentification
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            // Si Socialite n'est pas configuré, afficher la page d'information
            return view('auth.google-info');
        }
    }

    /**
     * Gérer le callback de Google après l'authentification
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Chercher si l'utilisateur existe déjà avec cet email Google
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Si l'utilisateur existe, le connecter
                Auth::login($user);
                return redirect()->intended('/dashboard');
            } else {
                // Si l'utilisateur n'existe pas, en créer un nouveau
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)), // Mot de passe aléatoire
                    'email_verified_at' => now(),
                ]);
                
                Auth::login($newUser);
                return redirect()->intended('/dashboard');
            }
            
        } catch (\Exception $e) {
            // En cas d'erreur, rediriger vers la page de connexion avec un message
            return redirect()->route('login')->with('error', 'Une erreur est survenue lors de la connexion avec Google. Veuillez réessayer.');
        }
    }
}
