@extends('layouts.app')

@section('title', 'FAQ - ' . config('app.name', 'Sakha'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">FAQ</h1>

        <div class="space-y-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Comment suivre ma commande ?</h2>
                <p class="text-gray-700 dark:text-gray-300 mt-1">
                    Utilisez la page de suivi de commande avec votre numero de commande.
                </p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quels moyens de paiement sont disponibles ?</h2>
                <p class="text-gray-700 dark:text-gray-300 mt-1">
                    Les moyens de paiement affiches au checkout sont les methodes actuellement actives.
                </p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Comment contacter le support ?</h2>
                <p class="text-gray-700 dark:text-gray-300 mt-1">
                    Rendez-vous sur la page contact pour nous envoyer votre demande.
                </p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('track.form') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-white font-semibold" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                Suivre ma commande
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100 font-semibold">
                Contact support
            </a>
        </div>
    </div>
</div>
@endsection
