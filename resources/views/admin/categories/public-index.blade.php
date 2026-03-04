@extends('layouts.app')

@section('title', 'Categories - ' . config('app.name'))

@php
    $normalizeCategoryIcon = function ($iconValue, $fallback = 'fa-tag') {
        $icon = trim((string) $iconValue);
        if ($icon === '') {
            return 'fas ' . ltrim($fallback, '-');
        }

        $tokens = preg_split('/\s+/', $icon) ?: [];
        $tokens = array_values(array_filter($tokens, function ($token) {
            return preg_match('/^[a-z0-9_-]+$/i', $token) === 1;
        }));

        if (empty($tokens)) {
            return 'fas ' . ltrim($fallback, '-');
        }

        $hasStylePrefix = false;
        $hasFaToken = false;
        foreach ($tokens as $token) {
            if (in_array($token, ['fas', 'far', 'fab', 'fal', 'fad', 'fat', 'fa-solid', 'fa-regular', 'fa-brands', 'fa-light', 'fa-duotone', 'fa-thin', 'fa-sharp', 'fa'], true)) {
                $hasStylePrefix = true;
            }
            if (strpos($token, 'fa-') === 0) {
                $hasFaToken = true;
            }
        }

        if (!$hasFaToken && count($tokens) === 1) {
            $tokens[0] = 'fa-' . ltrim($tokens[0], '-');
            $hasFaToken = true;
        }

        if ($hasFaToken && !$hasStylePrefix) {
            array_unshift($tokens, 'fas');
        }

        return trim(implode(' ', $tokens));
    };
@endphp

@section('content')
<section class="relative overflow-hidden py-10 sm:py-14">
    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: var(--icon-blue);"></div>
    <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full blur-3xl opacity-25" style="background: var(--accent-gradient);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 rounded-3xl p-6 sm:p-8 lg:p-10 text-white shadow-xl" style="background: var(--primary-gradient);">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">Parcourir</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">Nos Categories</h1>
                    <p class="mt-3 text-white/85 text-sm sm:text-base max-w-xl">
                        Explorez toutes les categories disponibles avec leurs produits mis a jour en temps reel.
                    </p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-2xl bg-white/20 backdrop-blur px-4 py-2 border border-white/30">
                    <i class="fas fa-layer-group"></i>
                    <span class="text-sm font-semibold">{{ $categories->count() }} categories</span>
                </div>
            </div>
        </div>

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($categories as $category)
                    @php
                        $iconClass = $normalizeCategoryIcon($category->icon, 'fa-tag');
                        $productsCount = (int) ($category->products_count ?? 0);
                    @endphp

                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="group relative overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-x-0 top-0 h-1.5" style="background: var(--primary-gradient);"></div>
                        <div class="absolute -top-16 -right-16 w-44 h-44 rounded-full opacity-[0.07]" style="background: var(--icon-blue);"></div>

                        <div class="relative p-6 sm:p-7 flex flex-col h-full">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white shadow-lg" style="background: var(--icon-gradient);">
                                    <i class="{{ $iconClass }} text-xl"></i>
                                </div>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold bg-gray-100 text-gray-700 whitespace-nowrap">
                                    {{ $productsCount }} produit(s)
                                </span>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-3">
                                    {{ $category->description ?? 'Decouvrez tous les produits disponibles dans cette categorie.' }}
                                </p>
                            </div>

                            <div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-gray-800 group-hover:gap-3 transition-all">
                                Voir les produits
                                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm mb-8">
                <p class="text-center text-gray-700 text-sm sm:text-base">
                    <span class="font-semibold">{{ $categories->count() }}</span> categories actives
                    <span class="mx-2 text-gray-300">|</span>
                    <a href="{{ route('shop.index') }}" class="font-semibold hover:underline" style="color: #2563EB;">Voir tous les produits</a>
                </p>
            </div>

            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 transition font-semibold group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span>Retour a l'accueil</span>
                </a>
            </div>
        @else
            <div class="text-center py-16 rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-layer-group text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg sm:text-2xl font-bold text-gray-700 mb-2">Aucune categorie disponible</h3>
                <p class="text-sm sm:text-base text-gray-500 mb-8">Les categories apparaitront ici apres creation.</p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:opacity-90 transition"
                   style="background: var(--action-primary);">
                    <i class="fas fa-home"></i>
                    Accueil
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
