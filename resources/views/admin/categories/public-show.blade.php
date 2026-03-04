@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))

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

    $categoryIconClass = $normalizeCategoryIcon($category->icon, 'fa-tag');
@endphp

@section('content')
<section class="relative overflow-hidden py-10 sm:py-14">
    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: var(--icon-blue);"></div>
    <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full blur-3xl opacity-25" style="background: var(--accent-gradient);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-3xl p-6 sm:p-8 lg:p-10 text-white shadow-xl" style="background: var(--primary-gradient);">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-sm text-white/80 mb-3">
                        <a href="{{ route('home') }}" class="hover:text-white">Accueil</a>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                        <a href="{{ route('categories.index') }}" class="hover:text-white">Categories</a>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                        <span class="text-white font-semibold">{{ $category->name }}</span>
                    </nav>

                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">{{ $category->name }}</h1>
                    <p class="mt-2 text-white/90 text-sm sm:text-base">{{ $products->total() }} produit(s) dans cette categorie</p>

                    @if(!empty($category->description))
                        <p class="mt-3 max-w-2xl text-white/85 text-sm sm:text-base">{{ $category->description }}</p>
                    @endif
                </div>

                <div class="inline-flex items-center gap-3 rounded-2xl bg-white/20 backdrop-blur px-4 py-3 border border-white/30">
                    <div class="w-12 h-12 rounded-xl bg-white/25 flex items-center justify-center">
                        <i class="{{ $categoryIconClass }} text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em] text-white/80">Categorie</p>
                        <p class="font-semibold">{{ $category->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10">
                @foreach($products as $product)
                    @php
                        $primaryImage = $product->images->first();
                        $primaryImageUrl = $primaryImage ? \Illuminate\Support\Facades\Storage::url($primaryImage->path) : null;
                        $finalPrice = $product->getFinalPrice();
                        $discountPercent = $product->getDiscountPercentage();
                        $isInStock = (int) ($product->stock ?? 0) > 0;
                    @endphp

                    <article data-touch-zoom class="group bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                        <a href="{{ route('product.show', $product->slug) }}" class="block">
                            <div class="relative h-52 bg-gray-100 overflow-hidden">
                                @if($primaryImageUrl)
                                    <img src="{{ $primaryImageUrl }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-bold {{ $isInStock ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $isInStock ? 'En stock' : 'Rupture' }}
                                    </span>
                                </div>

                                @if($discountPercent > 0)
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold bg-rose-100 text-rose-700">
                                            -{{ $discountPercent }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="p-4 sm:p-5">
                            <a href="{{ route('product.show', $product->slug) }}" class="block">
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-2 line-clamp-2">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <div class="flex items-end justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-lg font-extrabold text-gray-900">
                                        {{ number_format((float) $finalPrice, 0, ',', ' ') }} FCFA
                                    </p>
                                    @if($discountPercent > 0)
                                        <p class="text-xs text-gray-500 line-through">
                                            {{ number_format((float) ($product->price ?? 0), 0, ',', ' ') }} FCFA
                                        </p>
                                    @endif
                                </div>

                                <form action="{{ route('cart.index') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-full text-white shadow-md hover:opacity-90 transition"
                                            style="background: var(--button-action);"
                                            title="Ajouter au panier"
                                            aria-label="Ajouter {{ $product->name }} au panier">
                                        <i class="fas fa-cart-plus text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-3 sm:p-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-16 rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-700 mb-2">Aucun produit dans cette categorie</h3>
                <p class="text-gray-500 mb-8">Decouvrez les autres categories disponibles.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex items-center justify-center gap-2 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:opacity-90 transition"
                       style="background: var(--action-primary);">
                        <i class="fas fa-tags"></i>
                        Voir les categories
                    </a>
                    <a href="{{ route('shop.index') }}"
                       class="inline-flex items-center justify-center gap-2 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:opacity-90 transition"
                       style="background: var(--button-success);">
                        <i class="fas fa-store"></i>
                        Voir tous les produits
                    </a>
                </div>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-10 pt-6 border-t border-gray-200">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 font-semibold text-gray-700 hover:text-gray-900 transition">
                <i class="fas fa-arrow-left"></i>
                Retour aux categories
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
                <i class="fas fa-home"></i>
                Accueil
            </a>
        </div>
    </div>
</section>
@endsection
