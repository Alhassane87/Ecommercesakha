@extends('layouts.app')

@section('title', 'Accueil - ' . config('app.name'))

@php
function normalizeCategoryIcon($iconValue) {
    $icon = trim((string) $iconValue);
    if ($icon === '') {
        return null;
    }

    // Keep only CSS-class-safe tokens.
    $tokens = preg_split('/\s+/', $icon) ?: [];
    $tokens = array_values(array_filter($tokens, function ($token) {
        return preg_match('/^[a-z0-9_-]+$/i', $token) === 1;
    }));

    if (empty($tokens)) {
        return null;
    }

    $hasStylePrefix = false;
    $hasFaIconToken = false;
    foreach ($tokens as $token) {
        if (in_array($token, ['fas', 'far', 'fab', 'fal', 'fad', 'fat', 'fa-solid', 'fa-regular', 'fa-brands', 'fa-light', 'fa-duotone', 'fa-thin', 'fa-sharp', 'fa'], true)) {
            $hasStylePrefix = true;
        }
        if (strpos($token, 'fa-') === 0) {
            $hasFaIconToken = true;
        }
    }

    // If an icon token exists without style prefix, default to solid.
    if ($hasFaIconToken && !$hasStylePrefix) {
        array_unshift($tokens, 'fas');
    }

    // If user saved only icon name like "mobile-alt" or "fa-mobile-alt".
    if (!$hasFaIconToken && count($tokens) === 1) {
        $base = ltrim($tokens[0], '-');
        if (strpos($base, 'fa-') !== 0) {
            $base = 'fa-' . $base;
        }
        return 'fas ' . $base;
    }

    return implode(' ', $tokens);
}

function resolveCategoryIcon($category) {
    $selectedIcon = normalizeCategoryIcon($category->icon ?? null);
    if (!empty($selectedIcon)) {
        return $selectedIcon;
    }

    // Fallback only when no icon was chosen/saved.
    return \App\Helpers\CategoryHelper::getIcon((string) ($category->name ?? ''));
}
@endphp

@section('content')
    <!-- Hero Section Ultra-Moderne avec Diaporama -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Diaporama d'images en fond -->
        <div class="absolute inset-0">
            <div id="slideshow" class="relative w-full h-full">
                @forelse($slideshowImages as $slide)
                    @php
                        $slideUrl = Storage::url($slide['path']);
                        $slideTarget = $slide['target_url'] ?? null;
                        $slideOpenInNewTab = !empty($slide['open_in_new_tab']);
                    @endphp
                    <div class="slide absolute inset-0 transition-opacity duration-1000 {{ $loop->first ? '' : 'opacity-0' }}">
                        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-45" style="background-image: url('{{ $slideUrl }}');"></div>
                        <div class="absolute inset-0 bg-contain bg-center bg-no-repeat" style="background-image: url('{{ $slideUrl }}');"></div>
                        <div class="absolute inset-0 bg-black/30"></div>
                        @if($slideTarget)
                            <a href="{{ $slideTarget }}"
                               @if($slideOpenInNewTab) target="_blank" rel="noopener noreferrer" @endif
                               class="absolute inset-0 block"
                               aria-label="Voir l'offre publicitaire"></a>
                        @endif
                    </div>
                @empty
                    <div class="slide absolute inset-0 transition-opacity duration-1000">
                        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-45" style="background-image: url('https://picsum.photos/seed/sakha1/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-contain bg-center bg-no-repeat" style="background-image: url('https://picsum.photos/seed/sakha1/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-45" style="background-image: url('https://picsum.photos/seed/sakha2/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-contain bg-center bg-no-repeat" style="background-image: url('https://picsum.photos/seed/sakha2/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-45" style="background-image: url('https://picsum.photos/seed/sakha3/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-contain bg-center bg-no-repeat" style="background-image: url('https://picsum.photos/seed/sakha3/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-45" style="background-image: url('https://picsum.photos/seed/sakha4/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-contain bg-center bg-no-repeat" style="background-image: url('https://picsum.photos/seed/sakha4/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                        <div class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-45" style="background-image: url('https://picsum.photos/seed/sakha5/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-contain bg-center bg-no-repeat" style="background-image: url('https://picsum.photos/seed/sakha5/1920/1080.jpg');"></div>
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                @endforelse
            </div>
            
            <!-- Elements animes -->
            <div class="absolute top-20 left-20 w-72 h-72 bg-white/20 rounded-full blur-3xl float-animation"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-white/10 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-white/15 rounded-full blur-3xl float-animation" style="animation-delay: 4s;"></div>
        </div>
        
        <!-- Contenu principal -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center text-white">
            <div class="animate-fade-in">
                <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
                    BIENVENUE CHEZ<br>
                    <span class="text-emerald-400">Sakha</span>
                </h1>
                <p class="text-xl md:text-2xl mb-12 opacity-90 max-w-3xl mx-auto leading-relaxed">
                    Decouvrez des produits exceptionnels avec une livraison rapide et un service client hors pair.
                </p>
                
                <!-- Boutons d'action modernes -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('shop.index') }}" 
                       class="glass-effect text-white px-10 py-5 rounded-2xl font-bold hover:bg-white hover:text-purple-600 transition-all duration-300 transform hover:scale-110 shadow-2xl text-lg flex items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span>Decouvrir la Boutique</span>
                    </a>
                    <a href="{{ route('track.form') }}" 
                       class="border-3 border-white text-white px-10 py-5 rounded-2xl font-bold hover:bg-white hover:text-purple-600 transition-all duration-300 transform hover:scale-110 text-lg flex items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <span>Suivre Commande</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Indicateur de scroll -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </section>

    <x-ad-slot placement="home_hero" :limit="1" />

    <!-- Avantages Premium -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        @php
            $advantages = [
                [
                    'icon' => 'fas fa-shipping-fast',
                    'title' => 'Livraison 24h',
                    'text' => 'Expedition express partout au Senegal',
                    'from' => '#ef4444',
                    'to' => '#f59e0b',
                    'surfaceA' => '#fff1f2',
                    'surfaceB' => '#fff7ed',
                    'chipBg' => '#fee2e2',
                    'chipText' => '#991b1b',
                    'chip' => 'Express',
                    'ring' => 'rgba(239, 68, 68, 0.22)',
                ],
                [
                    'icon' => 'fas fa-shield-alt',
                    'title' => 'Paiement Securise',
                    'text' => '100% protection et cryptage SSL',
                    'from' => '#3b82f6',
                    'to' => '#06b6d4',
                    'surfaceA' => '#eff6ff',
                    'surfaceB' => '#ecfeff',
                    'chipBg' => '#dbeafe',
                    'chipText' => '#1e3a8a',
                    'chip' => 'Fiable',
                    'ring' => 'rgba(59, 130, 246, 0.22)',
                ],
                [
                    'icon' => 'fas fa-undo-alt',
                    'title' => 'Retours Faciles',
                    'text' => '30 jours satisfait ou rembourse',
                    'from' => '#10b981',
                    'to' => '#14b8a6',
                    'surfaceA' => '#ecfdf5',
                    'surfaceB' => '#f0fdfa',
                    'chipBg' => '#d1fae5',
                    'chipText' => '#065f46',
                    'chip' => '30 jours',
                    'ring' => 'rgba(16, 185, 129, 0.22)',
                ],
                [
                    'icon' => 'fas fa-headset',
                    'title' => 'Support 24/7',
                    'text' => 'Toujours disponible pour vous aider',
                    'from' => '#8b5cf6',
                    'to' => '#ec4899',
                    'surfaceA' => '#f5f3ff',
                    'surfaceB' => '#fdf2f8',
                    'chipBg' => '#ede9fe',
                    'chipText' => '#5b21b6',
                    'chip' => 'Premium',
                    'ring' => 'rgba(139, 92, 246, 0.22)',
                ],
            ];
        @endphp

        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 gradient-border p-0.5 rounded-2xl mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full h-full flex items-center justify-center">
                        <i class="fas fa-star gradient-text text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">NOS AVANTAGES</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Une experience d'achat exceptionnelle avec des services premium
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($advantages as $item)
                    <article class="group relative overflow-hidden rounded-3xl border border-white/70 shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm"
                             style="box-shadow: 0 24px 45px -35px {{ $item['ring'] }};">
                        <div class="absolute inset-0"
                             style="background: linear-gradient(160deg, {{ $item['surfaceA'] }} 0%, {{ $item['surfaceB'] }} 100%);"></div>

                        <div class="absolute -top-12 -right-8 w-32 h-32 rounded-full opacity-20 group-hover:opacity-35 transition-opacity"
                             style="background: linear-gradient(135deg, {{ $item['from'] }} 0%, {{ $item['to'] }} 100%);"></div>
                        <div class="absolute inset-x-0 bottom-0 h-1.5"
                             style="background: linear-gradient(90deg, {{ $item['from'] }} 0%, {{ $item['to'] }} 100%);"></div>

                        <div class="relative p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300"
                                     style="background: linear-gradient(135deg, {{ $item['from'] }} 0%, {{ $item['to'] }} 100%);">
                                    <i class="{{ $item['icon'] }} text-xl"></i>
                                </div>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold"
                                      style="background: {{ $item['chipBg'] }}; color: {{ $item['chipText'] }};">
                                    {{ $item['chip'] }}
                                </span>
                            </div>

                            <h3 class="mt-6 text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                                {{ $item['title'] }}
                            </h3>
                            <p class="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $item['text'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section Categories Premium with Icons -->
    <section class="relative py-24 bg-gradient-to-br from-white via-slate-50 to-cyan-50/40 dark:from-gray-800 dark:via-gray-900 dark:to-slate-900" id="categories">
        @php
            $categoryPalettes = [
                ['from' => '#f97316', 'to' => '#ef4444', 'badgeBg' => 'rgba(255, 255, 255, 0.24)', 'badgeText' => '#fff7ed', 'ctaBg' => '#ffedd5', 'ctaText' => '#9a3412', 'ring' => 'rgba(249, 115, 22, 0.28)'],
                ['from' => '#3b82f6', 'to' => '#14b8a6', 'badgeBg' => 'rgba(255, 255, 255, 0.24)', 'badgeText' => '#eff6ff', 'ctaBg' => '#dbeafe', 'ctaText' => '#1e3a8a', 'ring' => 'rgba(59, 130, 246, 0.28)'],
                ['from' => '#8b5cf6', 'to' => '#ec4899', 'badgeBg' => 'rgba(255, 255, 255, 0.24)', 'badgeText' => '#f5f3ff', 'ctaBg' => '#ede9fe', 'ctaText' => '#5b21b6', 'ring' => 'rgba(139, 92, 246, 0.28)'],
                ['from' => '#10b981', 'to' => '#22c55e', 'badgeBg' => 'rgba(255, 255, 255, 0.24)', 'badgeText' => '#ecfdf5', 'ctaBg' => '#d1fae5', 'ctaText' => '#065f46', 'ring' => 'rgba(16, 185, 129, 0.28)'],
                ['from' => '#0ea5e9', 'to' => '#6366f1', 'badgeBg' => 'rgba(255, 255, 255, 0.24)', 'badgeText' => '#f0f9ff', 'ctaBg' => '#bae6fd', 'ctaText' => '#0c4a6e', 'ring' => 'rgba(14, 165, 233, 0.28)'],
                ['from' => '#f59e0b', 'to' => '#d946ef', 'badgeBg' => 'rgba(255, 255, 255, 0.24)', 'badgeText' => '#fffbeb', 'ctaBg' => '#fef3c7', 'ctaText' => '#92400e', 'ring' => 'rgba(245, 158, 11, 0.28)'],
            ];
        @endphp

        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-16 left-8 w-52 h-52 rounded-full blur-3xl opacity-25" style="background: linear-gradient(135deg, #fb7185 0%, #f59e0b 100%);"></div>
            <div class="absolute bottom-2 right-4 w-72 h-72 rounded-full blur-3xl opacity-25" style="background: linear-gradient(135deg, #22d3ee 0%, #6366f1 100%);"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center rounded-full px-4 py-1 text-xs font-semibold uppercase tracking-widest border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white/80 dark:bg-slate-900/80">
                    Catalogue
                </div>
                <h2 class="mt-5 text-4xl md:text-5xl font-black gradient-text mb-4">NOS CATEGORIES</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Parcourez nos differentes categories et trouvez vos produits en quelques clics.
                </p>
            </div>

            @if($categories->isNotEmpty())
                <div class="relative">
                    <div class="hidden md:flex absolute -left-4 top-1/2 -translate-y-1/2 z-10">
                        <button type="button"
                                class="carousel-arrow w-11 h-11 rounded-full border border-slate-200 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 text-slate-700 dark:text-slate-200 shadow-lg hover:shadow-xl transition"
                                data-carousel-prev="home-categories-carousel"
                                aria-label="Categories precedentes">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>
                    <div class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10">
                        <button type="button"
                                class="carousel-arrow w-11 h-11 rounded-full border border-slate-200 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 text-slate-700 dark:text-slate-200 shadow-lg hover:shadow-xl transition"
                                data-carousel-next="home-categories-carousel"
                                aria-label="Categories suivantes">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div id="home-categories-carousel" class="carousel-track flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-3 px-1">
                        @foreach($categories as $category)
                            @php
                                $palette = $categoryPalettes[$loop->index % count($categoryPalettes)];
                                $categoryIcon = resolveCategoryIcon($category);
                                $categoryInitial = strtoupper(substr(trim((string) $category->name), 0, 1));
                            @endphp
                            <a href="{{ route('categories.show', $category->slug) }}"
                               data-touch-zoom
                               class="group min-w-[260px] sm:min-w-[320px] lg:min-w-[330px] max-w-[330px] flex-shrink-0 snap-start relative isolate overflow-hidden rounded-3xl border border-slate-200/80 dark:border-slate-700/70 bg-white/95 dark:bg-slate-900/90 shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                               style="box-shadow: 0 24px 45px -35px {{ $palette['ring'] }};">
                                <div class="relative h-32 overflow-hidden px-5 py-4 text-white"
                                     style="background: linear-gradient(135deg, {{ $palette['from'] }} 0%, {{ $palette['to'] }} 100%);">
                                    <div class="absolute inset-0 opacity-20"
                                         style="background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.9) 1px, transparent 1px); background-size: 16px 16px;"></div>

                                    <div class="relative flex items-start justify-between gap-3">
                                        <div class="relative w-12 h-12 rounded-xl border border-white/30 bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            <i class="{{ $categoryIcon }} text-lg"></i>
                                            <span class="absolute -bottom-1 -right-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-slate-900/80 px-1 text-[10px] font-bold leading-none text-white">
                                                {{ $categoryInitial }}
                                            </span>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-white/35"
                                              style="background: {{ $palette['badgeBg'] }}; color: {{ $palette['badgeText'] }};">
                                            {{ $category->products_count }} produit(s)
                                        </span>
                                    </div>
                                </div>

                                <div class="relative p-6">
                                    <div class="absolute inset-x-6 -top-2 h-1.5 rounded-full opacity-90"
                                         style="background: linear-gradient(90deg, {{ $palette['from'] }} 0%, {{ $palette['to'] }} 100%);"></div>

                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">
                                        Filtrez les produits de cette categorie en un clic.
                                    </p>

                                    <div class="mt-5 inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold"
                                         style="background: {{ $palette['ctaBg'] }}; color: {{ $palette['ctaText'] }};">
                                        Explorer la categorie
                                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </div>
                                </div>

                                <div class="pointer-events-none absolute -bottom-12 -right-10 h-28 w-28 rounded-full opacity-25 blur-2xl"
                                     style="background: linear-gradient(135deg, {{ $palette['from'] }} 0%, {{ $palette['to'] }} 100%);"></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-16 rounded-3xl border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900/90 shadow-sm">
                    <div class="w-24 h-24 mx-auto mb-6 relative">
                        <div class="absolute inset-0 rounded-full blur-2xl opacity-30" style="background: linear-gradient(135deg, #38bdf8 0%, #6366f1 100%);"></div>
                        <div class="relative w-full h-full bg-white dark:bg-gray-800 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                            <i class="fas fa-tags text-4xl text-gray-400"></i>
                        </div>
                    </div>
                    <p class="text-xl text-gray-500 dark:text-gray-400">Aucune categorie disponible pour le moment.</p>
                </div>
            @endif

            <div class="text-center mt-12">
                <a href="{{ route('categories.index') }}"
                   class="gradient-bg inline-flex items-center justify-center gap-3 text-white px-10 py-5 rounded-3xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-layer-group"></i>
                    <span>Voir tous les catalogues</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Produits Populaires + Recents -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 gradient-border p-0.5 rounded-2xl mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full h-full flex items-center justify-center">
                        <i class="fas fa-store gradient-text text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">PRODUITS A LA UNE</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Parcourez les produits les plus populaires et les plus recents.
                </p>
            </div>

            @php
                $popularList = $popularProducts ?? $products;
                $recentList = $recentProducts ?? collect();
            @endphp

            <div class="space-y-14">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-fire text-orange-500"></i>
                            Populaires
                        </h3>
                        <div class="hidden md:flex items-center gap-2">
                            <button type="button"
                                    class="carousel-arrow w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm"
                                    data-carousel-prev="home-popular-products-carousel"
                                    aria-label="Produits populaires precedents">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button"
                                    class="carousel-arrow w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm"
                                    data-carousel-next="home-popular-products-carousel"
                                    aria-label="Produits populaires suivants">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    @if($popularList->isNotEmpty())
                        <div id="home-popular-products-carousel" class="carousel-track flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 px-1">
                            @foreach($popularList as $product)
                                @php
                                    $primaryImage = $product->images->first();
                                    $primaryImageUrl = $primaryImage ? Storage::url($primaryImage->path) : null;
                                    $finalPrice = $product->getFinalPrice();
                                    $discountPercent = $product->getDiscountPercentage();
                                @endphp
                                <div class="group hover-lift min-w-[260px] sm:min-w-[300px] lg:min-w-[320px] max-w-[320px] flex-shrink-0 snap-start">
                                    <div class="glass-effect rounded-2xl overflow-hidden border border-white/20 dark:border-gray-700/50 product-touch-card"
                                         data-touch-zoom
                                         onpointerdown="this.classList.add('touch-zoomed')"
                                         onpointerup="var el=this; window.setTimeout(function(){ el.classList.remove('touch-zoomed'); }, 220)"
                                         onpointercancel="this.classList.remove('touch-zoomed')"
                                         ontouchstart="this.classList.add('touch-zoomed')"
                                         ontouchend="var el=this; window.setTimeout(function(){ el.classList.remove('touch-zoomed'); }, 220)"
                                         ontouchcancel="this.classList.remove('touch-zoomed')">
                                        <div class="relative overflow-hidden h-56 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                            @if($primaryImageUrl)
                                                <div class="absolute inset-0 bg-cover bg-center scale-105 blur-sm opacity-40" style="background-image: url('{{ $primaryImageUrl }}');"></div>
                                                <a href="{{ route('product.show', $product->slug) }}" class="relative block h-full">
                                                    <img src="{{ $primaryImageUrl }}"
                                                         alt="{{ $product->name }}"
                                                         class="w-full h-full object-contain p-0">
                                                </a>
                                            @else
                                                <a href="{{ route('product.show', $product->slug) }}" class="block h-full">
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                        <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mb-3">
                                                            <i class="fas fa-image text-2xl"></i>
                                                        </div>
                                                        <span class="text-sm">Aucune image</span>
                                                    </div>
                                                </a>
                                            @endif

                                            <div class="absolute top-4 right-4">
                                                <div class="gradient-bg text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    <i class="fas fa-fire mr-1"></i>
                                                    Populaire
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-6">
                                            @if($product->category)
                                                <div class="flex items-center mb-3">
                                                    <div class="w-4 h-4 rounded-full bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] flex items-center justify-center">
                                                        <i class="fas fa-tag text-white text-xs"></i>
                                                    </div>
                                                    <span class="ml-2 text-xs font-medium text-[var(--primary-color)] dark:text-[var(--secondary-color)]">
                                                        {{ $product->category->name }}
                                                    </span>
                                                </div>
                                            @endif

                                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-[var(--primary-color)] transition-colors">
                                                {{ \Illuminate\Support\Str::limit($product->name, 50) }}
                                            </h3>

                                            @if(!empty($product->short_description))
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 80) }}
                                                </p>
                                            @endif

                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="text-xl font-bold gradient-text">
                                                        {{ number_format($finalPrice ?? 0, 2, ',', ' ') }} {{ config('app.currency', 'fcfa') }}
                                                    </span>
                                                    @if($discountPercent > 0)
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="text-xs text-gray-500 line-through">
                                                                {{ number_format($product->price ?? 0, 2, ',', ' ') }} {{ config('app.currency', 'fcfa') }}
                                                            </span>
                                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                                                                -{{ $discountPercent }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <form action="{{ route('cart.index') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="qty" value="1">
                                                    <button type="submit" class="gradient-bg text-white p-3 rounded-full hover:shadow-lg transform hover:scale-110 transition-all duration-300">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Aucun produit populaire pour le moment.</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-clock text-blue-500"></i>
                            Recents
                        </h3>
                        <div class="hidden md:flex items-center gap-2">
                            <button type="button"
                                    class="carousel-arrow w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm"
                                    data-carousel-prev="home-recent-products-carousel"
                                    aria-label="Produits recents precedents">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button"
                                    class="carousel-arrow w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 shadow-sm"
                                    data-carousel-next="home-recent-products-carousel"
                                    aria-label="Produits recents suivants">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    @if($recentList->isNotEmpty())
                        <div id="home-recent-products-carousel" class="carousel-track flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 px-1">
                            @foreach($recentList as $product)
                                @php
                                    $primaryImage = $product->images->first();
                                    $primaryImageUrl = $primaryImage ? Storage::url($primaryImage->path) : null;
                                    $finalPrice = $product->getFinalPrice();
                                    $discountPercent = $product->getDiscountPercentage();
                                @endphp
                                <div class="group hover-lift min-w-[260px] sm:min-w-[300px] lg:min-w-[320px] max-w-[320px] flex-shrink-0 snap-start">
                                    <div class="glass-effect rounded-2xl overflow-hidden border border-white/20 dark:border-gray-700/50 product-touch-card"
                                         data-touch-zoom
                                         onpointerdown="this.classList.add('touch-zoomed')"
                                         onpointerup="var el=this; window.setTimeout(function(){ el.classList.remove('touch-zoomed'); }, 220)"
                                         onpointercancel="this.classList.remove('touch-zoomed')"
                                         ontouchstart="this.classList.add('touch-zoomed')"
                                         ontouchend="var el=this; window.setTimeout(function(){ el.classList.remove('touch-zoomed'); }, 220)"
                                         ontouchcancel="this.classList.remove('touch-zoomed')">
                                        <div class="relative overflow-hidden h-56 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                            @if($primaryImageUrl)
                                                <div class="absolute inset-0 bg-cover bg-center scale-105 blur-sm opacity-40" style="background-image: url('{{ $primaryImageUrl }}');"></div>
                                                <a href="{{ route('product.show', $product->slug) }}" class="relative block h-full">
                                                    <img src="{{ $primaryImageUrl }}"
                                                         alt="{{ $product->name }}"
                                                         class="w-full h-full object-contain p-0">
                                                </a>
                                            @else
                                                <a href="{{ route('product.show', $product->slug) }}" class="block h-full">
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                        <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mb-3">
                                                            <i class="fas fa-image text-2xl"></i>
                                                        </div>
                                                        <span class="text-sm">Aucune image</span>
                                                    </div>
                                                </a>
                                            @endif

                                            <div class="absolute top-4 right-4">
                                                <div class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    <i class="fas fa-bolt mr-1"></i>
                                                    Recent
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-6">
                                            @if($product->category)
                                                <div class="flex items-center mb-3">
                                                    <div class="w-4 h-4 rounded-full bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] flex items-center justify-center">
                                                        <i class="fas fa-tag text-white text-xs"></i>
                                                    </div>
                                                    <span class="ml-2 text-xs font-medium text-[var(--primary-color)] dark:text-[var(--secondary-color)]">
                                                        {{ $product->category->name }}
                                                    </span>
                                                </div>
                                            @endif

                                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-[var(--primary-color)] transition-colors">
                                                {{ \Illuminate\Support\Str::limit($product->name, 50) }}
                                            </h3>

                                            @if(!empty($product->short_description))
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 80) }}
                                                </p>
                                            @endif

                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="text-xl font-bold gradient-text">
                                                        {{ number_format($finalPrice ?? 0, 2, ',', ' ') }} {{ config('app.currency', 'fcfa') }}
                                                    </span>
                                                    @if($discountPercent > 0)
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="text-xs text-gray-500 line-through">
                                                                {{ number_format($product->price ?? 0, 2, ',', ' ') }} {{ config('app.currency', 'fcfa') }}
                                                            </span>
                                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                                                                -{{ $discountPercent }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <form action="{{ route('cart.index') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="qty" value="1">
                                                    <button type="submit" class="gradient-bg text-white p-3 rounded-full hover:shadow-lg transform hover:scale-110 transition-all duration-300">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Aucun produit recent pour le moment.</p>
                    @endif
                </div>
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('shop.index') }}"
                   class="gradient-bg inline-flex items-center justify-center gap-3 text-white px-10 py-5 rounded-3xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-store"></i>
                    <span>Voir toute la boutique</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Statistiques Animees avec Texte Visible -->
    <section class="py-20 relative overflow-hidden bg-gray-900">
        <div class="absolute inset-0 gradient-bg opacity-95"></div>
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl float-animation"></div>
            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl float-animation" style="animation-delay: 3s;"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-white">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-white">CHIFFRES CLES</h2>
                <p class="text-xl text-white max-w-2xl mx-auto">
                    Notre engagement en chiffres
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-600 shadow-2xl">
                    <div class="text-5xl font-bold mb-2 text-yellow-400 counter" data-target="{{ $totalProductsCount ?? $products->count() }}">0</div>
                    <p class="text-lg text-white font-bold">Produits</p>
                </div>
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-600 shadow-2xl">
                    <div class="text-5xl font-bold mb-2 text-green-400 counter" data-target="{{ $totalCategoriesCount ?? $categories->count() }}">0</div>
                    <p class="text-lg text-white font-bold">Categories</p>
                </div>
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-600 shadow-2xl">
                    <div class="text-5xl font-bold mb-2 text-blue-400">24h</div>
                    <p class="text-lg text-white font-bold">Livraison</p>
                </div>
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-600 shadow-2xl">
                    <div class="text-5xl font-bold mb-2 text-purple-400">98%</div>
                    <p class="text-lg text-white font-bold">Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <x-ad-slot placement="home_mid" :limit="2" />

    <!-- Newsletter Premium -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="glass-effect rounded-3xl p-12 border border-white/20 dark:border-gray-700/50">
                <div class="inline-flex items-center justify-center w-16 h-16 gradient-border p-0.5 rounded-2xl mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full h-full flex items-center justify-center">
                        <i class="fas fa-envelope gradient-text text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-4xl font-bold gradient-text mb-4">RESTEZ INFORME</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                    Recevez nos offres exclusives et nouvelles collections
                </p>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex max-w-md mx-auto gap-3" data-newsletter-form>
                    @csrf
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Votre email" 
                           class="flex-1 px-6 py-4 rounded-2xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <button type="submit" class="gradient-bg text-white px-8 py-4 rounded-2xl font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        S'abonner
                    </button>
                </form>
            </div>
        </div>
    </section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1.2s ease-out;
}

/* Animation du compteur */
.counter {
    transition: all 2s ease-out;
}

.carousel-track {
    scrollbar-width: none;
}

.carousel-track::-webkit-scrollbar {
    display: none;
}

.carousel-arrow:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation du diaporama
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    
    function showNextSlide() {
        slides[currentSlide].classList.add('opacity-0');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.remove('opacity-0');
    }
    
    // Changer de slide toutes les 5 secondes
    setInterval(showNextSlide, 5000);

    const setupCarousel = (trackId) => {
        const track = document.getElementById(trackId);
        if (!track) {
            return;
        }

        const prevButtons = Array.from(document.querySelectorAll(`[data-carousel-prev="${trackId}"]`));
        const nextButtons = Array.from(document.querySelectorAll(`[data-carousel-next="${trackId}"]`));
        if (!prevButtons.length && !nextButtons.length) {
            return;
        }

        const updateButtons = () => {
            const maxScrollLeft = Math.max(track.scrollWidth - track.clientWidth, 0);
            const atStart = track.scrollLeft <= 1;
            const atEnd = track.scrollLeft >= (maxScrollLeft - 1);

            prevButtons.forEach((button) => {
                button.disabled = atStart;
            });

            nextButtons.forEach((button) => {
                button.disabled = atEnd;
            });
        };

        const getStep = () => Math.max(track.clientWidth * 0.85, 280);

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                track.scrollBy({ left: -getStep(), behavior: 'smooth' });
            });
        });

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                track.scrollBy({ left: getStep(), behavior: 'smooth' });
            });
        });

        track.addEventListener('scroll', updateButtons, { passive: true });
        window.addEventListener('resize', updateButtons);
        updateButtons();
    };

    setupCarousel('home-categories-carousel');
    setupCarousel('home-popular-products-carousel');
    setupCarousel('home-recent-products-carousel');

    // Animation des compteurs
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                setTimeout(updateCounter, 20);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });

    // Animation d'apparition progressive
    const elements = document.querySelectorAll('.hover-lift');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(30px)';
                    entry.target.style.transition = 'all 0.6s ease-out';
                    
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    });

    elements.forEach(element => observer.observe(element));
});
</script>
@endsection

