@extends('layouts.app')

@section('title', 'Retours - ' . config('app.name', 'Sakha'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Retours</h1>
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            Si un produit recu ne correspond pas a votre commande, contactez-nous rapidement.
        </p>
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            Nous analysons chaque demande de retour pour proposer une solution adaptee.
        </p>
        <p class="text-gray-700 dark:text-gray-300">
            Pensez a conserver l emballage et votre preuve de commande pour accelerer le traitement.
        </p>
        <div class="mt-6">
            <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-white font-semibold" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                Demander un retour
            </a>
        </div>
    </div>
</div>
@endsection
