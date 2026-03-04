@extends('layouts.admin')

@section('title', 'Modifier campagne pub - ' . config('app.name', 'Sakha'))
@section('header', 'Modifier campagne pub')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Modifier: {{ $campaign->title }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Impressions: {{ number_format($campaign->impressions) }} | Clics: {{ number_format($campaign->clicks) }} | CTR: {{ number_format($campaign->ctr, 2) }}%
        </p>

        <form method="POST" action="{{ route('admin.ad-campaigns.update', $campaign) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.ad-campaigns._form', [
                'campaign' => $campaign,
                'placementOptions' => $placementOptions,
                'audienceOptions' => $audienceOptions,
            ])

            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-xl px-5 py-3 text-white font-semibold shadow"
                        style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                    Sauvegarder
                </button>
                <a href="{{ route('admin.ad-campaigns.index') }}"
                   class="inline-flex items-center rounded-xl px-5 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-semibold">
                    Retour
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
