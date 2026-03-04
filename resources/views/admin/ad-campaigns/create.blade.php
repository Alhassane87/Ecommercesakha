@extends('layouts.admin')

@section('title', 'Nouvelle campagne pub - ' . config('app.name', 'Sakha'))
@section('header', 'Nouvelle campagne pub')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Creer une campagne publicitaire</h2>

        <form method="POST" action="{{ route('admin.ad-campaigns.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('admin.ad-campaigns._form', [
                'placementOptions' => $placementOptions,
                'audienceOptions' => $audienceOptions,
            ])

            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-xl px-5 py-3 text-white font-semibold shadow"
                        style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                    Enregistrer la campagne
                </button>
                <a href="{{ route('admin.ad-campaigns.index') }}"
                   class="inline-flex items-center rounded-xl px-5 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-semibold">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
