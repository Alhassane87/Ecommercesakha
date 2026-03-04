@extends('layouts.app')

@section('title', 'Politique de confidentialite - ' . config('app.name', 'Sakha'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Politique de confidentialite</h1>
        <p class="text-gray-700 dark:text-gray-300 mb-3">
            Nous collectons uniquement les donnees necessaires au traitement des commandes et au support client.
        </p>
        <p class="text-gray-700 dark:text-gray-300 mb-3">
            Vos donnees ne sont pas revendues. Elles sont protegees selon les bonnes pratiques de securite.
        </p>
        <p class="text-gray-700 dark:text-gray-300">
            Vous pouvez demander la mise a jour ou la suppression de vos informations via notre page contact.
        </p>
    </div>
</div>
@endsection
