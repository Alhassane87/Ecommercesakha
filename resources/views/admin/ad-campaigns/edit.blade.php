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

    @php
        $galleryImages = $campaign->images;
        if ($galleryImages->isEmpty() && !empty($campaign->image_path)) {
            $galleryImages = collect([(object) ['id' => null, 'path' => $campaign->image_path]]);
        }
    @endphp

    @if($galleryImages->isNotEmpty())
        <div class="mt-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Galerie de la campagne</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Definissez l'image principale ou supprimez un visuel devenu inutile.
                    </p>
                </div>
                <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                    {{ $galleryImages->count() }} image(s)
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($galleryImages as $image)
                    @php
                        $isPrimary = $campaign->image_path === $image->path;
                    @endphp

                    <div class="rounded-2xl border {{ $isPrimary ? 'border-emerald-400 ring-2 ring-emerald-200 dark:ring-emerald-900/50' : 'border-gray-200 dark:border-gray-700' }} bg-gray-50 dark:bg-gray-800/40 p-3">
                        <div class="relative rounded-xl overflow-hidden bg-white dark:bg-gray-900">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}"
                                 alt="{{ $campaign->title }}"
                                 class="w-full h-48 object-contain">

                            @if($isPrimary)
                                <span class="absolute left-3 top-3 inline-flex items-center rounded-full bg-emerald-500 px-3 py-1 text-xs font-bold text-white shadow-lg">
                                    Principale
                                </span>
                            @endif
                        </div>

                        @if($image->id)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @unless($isPrimary)
                                    <form method="POST" action="{{ route('admin.ad-campaigns.images.primary', ['adCampaign' => $campaign->id, 'image' => $image->id]) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg px-3 py-2 bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                                            Mettre principale
                                        </button>
                                    </form>
                                @endunless

                                <form method="POST" action="{{ route('admin.ad-campaigns.images.destroy', ['adCampaign' => $campaign->id, 'image' => $image->id]) }}" onsubmit="return confirm('Supprimer cette image ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-lg px-3 py-2 bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
