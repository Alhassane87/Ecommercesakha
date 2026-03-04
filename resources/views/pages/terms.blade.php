@extends('layouts.app')

@section('title', 'CGV - ' . config('app.name', 'Sakha'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Conditions generales de vente</h1>
        <p class="text-gray-700 dark:text-gray-300 mb-3">
            Les commandes passees sur la plateforme sont soumises a validation et disponibilite des produits.
        </p>
        <p class="text-gray-700 dark:text-gray-300 mb-3">
            Les prix affiches sont ceux en vigueur au moment de la commande, sauf erreur technique evidente.
        </p>
        <p class="text-gray-700 dark:text-gray-300">
            En cas de litige, notre equipe support reste le premier point de contact pour trouver une solution rapide.
        </p>
    </div>
</div>
@endsection
