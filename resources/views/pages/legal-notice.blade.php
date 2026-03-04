@extends('layouts.app')

@section('title', 'Mentions legales - ' . config('app.name', 'Sakha'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Mentions legales</h1>
        <p class="text-gray-700 dark:text-gray-300 mb-3">
            Ce site est edite par {{ config('app.name', 'Sakha') }}.
        </p>
        <p class="text-gray-700 dark:text-gray-300 mb-3">
            Pour toute demande legale ou administrative, utilisez la page contact.
        </p>
        <p class="text-gray-700 dark:text-gray-300">
            Les informations presentes sur le site peuvent etre mises a jour a tout moment.
        </p>
    </div>
</div>
@endsection
