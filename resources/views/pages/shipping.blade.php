@extends('layouts.app')

@section('title', 'Livraison - ' . config('app.name', 'Sakha'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Livraison</h1>
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            Nous preparons les commandes dans les meilleurs delais et nous travaillons avec des transporteurs fiables.
        </p>
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            Les delais varient selon la zone de livraison et la disponibilite des produits.
        </p>
        <p class="text-gray-700 dark:text-gray-300">
            Pour une estimation precise, contactez notre support via la page contact.
        </p>
        <div class="mt-6">
            <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-white font-semibold" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                Nous contacter
            </a>
        </div>
    </div>
</div>
@endsection
