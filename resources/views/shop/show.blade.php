@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden py-10 sm:py-14">
    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: var(--icon-blue);"></div>
    <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full blur-3xl opacity-25" style="background: var(--accent-gradient);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-3xl p-6 sm:p-8 text-white shadow-xl" style="background: var(--primary-gradient);">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">{{ $product->category->name ?? 'Produit' }}</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">{{ $product->name }}</h1>
                    @php
                        $finalPrice = $product->getFinalPrice();
                        $discountPercent = $product->getDiscountPercentage();
                    @endphp
                    <p class="mt-2 text-white/85">{{ number_format($finalPrice, 0, ',', ' ') }} FCFA @if($discountPercent > 0)<span class="ml-2 inline-block bg-red-500 px-2 py-1 rounded text-sm font-bold">-{{ $discountPercent }}%</span>@endif</p>
                </div>
                <div class="hidden sm:block">
                    @if($product->inStock())
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-white/20 backdrop-blur px-4 py-2 border border-white/30">
                            <i class="fas fa-check-circle"></i>
                            <span class="text-sm font-semibold">En stock</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-red-500/20 backdrop-blur px-4 py-2 border border-red-400/50">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="text-sm font-semibold">Rupture</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 mb-8">
        <!-- Contenu du produit -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Images du produit -->
                <div class="space-y-4">
                    <!-- Image principale -->
                    <div id="main-image-frame" data-touch-zoom class="zoom-main-image-frame h-[28rem] md:h-[34rem] bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden shadow-lg relative group">
                        @if($product->images && $product->images->count() > 0)
                            @php
                                $initialMainImageUrl = Storage::url($product->images->first()->path);
                            @endphp
                            <div id="main-image-backdrop" class="absolute inset-0 bg-cover bg-center scale-110 blur-sm opacity-40" style="background-image: url('{{ $initialMainImageUrl }}');"></div>
                            <img id="main-image" 
                                 src="{{ $initialMainImageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 class="relative z-10 w-full h-full object-contain p-0 transition-all duration-500 main-image">
                            
                            <!-- Indicateur d'image -->
                            <div class="absolute top-4 left-4 z-20 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <span id="current-image-index">1</span>/<span id="total-images">{{ $product->images->count() }}</span>
                            </div>
                            
                            <!-- Navigation fléchée (si plusieurs images) -->
                            @if($product->images->count() > 1)
                                <button id="prev-image" class="absolute left-4 top-1/2 z-20 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-all duration-300 opacity-0 group-hover:opacity-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button id="next-image" class="absolute right-4 top-1/2 z-20 transform -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-all duration-300 opacity-0 group-hover:opacity-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @endif
                            <div class="absolute bottom-4 right-4 z-20 hidden md:flex items-center gap-2 bg-black/65 text-white px-3 py-1.5 rounded-full text-xs font-medium backdrop-blur-sm pointer-events-none">
                                <i class="fas fa-magnifying-glass"></i>
                                <span>Touchez ou survolez pour zoomer</span>
                            </div>
                        @else
                            <!-- État sans image -->
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <svg class="w-20 h-20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm">Aucune image disponible</p>
                            </div>
                        @endif
                    </div>

                    <!-- Galerie d'images miniatures -->
                    @if($product->images && $product->images->count() > 1)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $index => $image)
                                <div class="thumbnail-container relative group cursor-pointer" data-index="{{ $index }}">
                                    <img src="{{ Storage::url($image->path) }}" 
                                         alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                         data-index="{{ $index }}"
                                         class="thumbnail w-full h-16 object-contain bg-gray-100 dark:bg-gray-700 p-1 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border-2 {{ $index === 0 ? 'border-blue-500' : 'border-transparent' }} hover:border-blue-400">
                                    
                                    <!-- Overlay de sélection -->
                                    <div class="absolute inset-0 pointer-events-none bg-blue-500 bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-lg"></div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Détails du produit -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Description
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <h3 class="text-sm font-medium text-blue-600 dark:text-blue-400">Catégorie</h3>
                            </div>
                            <p class="text-gray-900 dark:text-gray-100 font-semibold text-lg">
                                {{ $product->category->name ?? 'Non catégorisé' }}
                            </p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <h3 class="text-sm font-medium text-green-600 dark:text-green-400">Stock</h3>
                            </div>
                            <p class="text-gray-900 dark:text-gray-100 font-semibold text-lg">
                                @if($product->inStock())
                                    <span class="text-green-600 dark:text-green-400">En stock</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">Rupture de stock</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Section Prix et Commande -->
                    <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-700/50 dark:to-gray-800/50 rounded-2xl p-6 border border-gray-200 dark:border-gray-600 shadow-lg">
                        <!-- Prix avec réduction bien visible -->
                        <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                            @php
                                $finalPrice = $product->getFinalPrice();
                                $discountPercent = $product->getDiscountPercentage();
                                $hasDiscount = $discountPercent > 0;
                            @endphp
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Prix</div>
                                    @if($hasDiscount)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg text-gray-500 line-through">
                                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                            </span>
                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold animate-pulse">
                                                -{{ $discountPercent }}%
                                            </span>
                                        </div>
                                    @endif
                                    <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                        {{ number_format($finalPrice, 0, ',', ' ') }} FCFA
                                    </div>
                                    @if($hasDiscount)
                                        <div class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">
                                            <i class="fas fa-tag mr-1"></i>
                                            Économisez {{ number_format($product->price - $finalPrice, 0, ',', ' ') }} FCFA
                                        </div>
                                    @endif
                                </div>
                                @if($hasDiscount)
                                    <div class="text-right">
                                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                                            <span class="text-white font-bold text-lg">-{{ $discountPercent }}%</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sélection des attributs (Pointure, Taille, Couleur, Modèle, Capacité, etc.) -->
                        @php
                            $configuredProductAttributes = $product->attributes;
                            if (is_string($configuredProductAttributes) && $configuredProductAttributes !== '') {
                                $decodedConfiguredProductAttributes = json_decode($configuredProductAttributes, true);
                                if (is_string($decodedConfiguredProductAttributes) && $decodedConfiguredProductAttributes !== '') {
                                    $decodedConfiguredProductAttributes = json_decode($decodedConfiguredProductAttributes, true);
                                }
                                $configuredProductAttributes = is_array($decodedConfiguredProductAttributes) ? $decodedConfiguredProductAttributes : [];
                            } elseif (!is_array($configuredProductAttributes)) {
                                $configuredProductAttributes = [];
                            }
                        @endphp
                        @if($categoryAttributes && $categoryAttributes->count() > 0)
                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-cog text-blue-600 dark:text-blue-400 mr-2"></i>
                                    Options du produit
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Sélectionnez vos options avant d'ajouter au panier
                                </p>
                                
                                <div class="space-y-5">
                                    @foreach($categoryAttributes as $attribute)
                                        @php
                                            $configuredValues = $configuredProductAttributes[$attribute->id] ?? $configuredProductAttributes[(string) $attribute->id] ?? null;

                                            if (is_string($configuredValues) && $configuredValues !== '') {
                                                $decodedConfiguredValues = json_decode($configuredValues, true);
                                                if (is_array($decodedConfiguredValues)) {
                                                    $configuredValues = $decodedConfiguredValues;
                                                }
                                            }

                                            $configuredValues = is_array($configuredValues)
                                                ? collect($configuredValues)->map(fn ($item) => (string) $item)->filter(fn ($item) => $item !== '')->values()->all()
                                                : (($configuredValues !== null && $configuredValues !== '') ? [(string) $configuredValues] : []);

                                            $displayValues = $attribute->values;
                                            if (!empty($configuredValues)) {
                                                $filteredValues = $attribute->values
                                                    ->filter(fn ($value) => in_array((string) $value->value, $configuredValues, true))
                                                    ->values();

                                                if ($filteredValues->isNotEmpty()) {
                                                    $displayValues = $filteredValues;
                                                }
                                            }
                                        @endphp
                                        @if($displayValues->isNotEmpty())
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center">
                                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                                {{ $attribute->name }}
                                                @if($attribute->is_required)
                                                    <span class="text-red-500 ml-1">*</span>
                                                @endif
                                            </label>
                                        
                                        @php
                                            $isColor = strtolower($attribute->name) === 'couleur';
                                            $isPointure = strtolower($attribute->name) === 'pointure';
                                            $isTaille = strtolower($attribute->name) === 'taille';
                                            $isCapacite = strtolower($attribute->name) === 'capacité' || strtolower($attribute->name) === 'capacite';
                                        @endphp
                                        
                                        <div class="flex flex-wrap gap-2 {{ $isPointure || $isTaille ? 'gap-1.5' : '' }}">
                                            @foreach($displayValues as $value)
                                                <button type="button"
                                                        class="attribute-option 
                                                               @if($isColor && $value->color_code)
                                                                   w-10 h-10 rounded-full border-2 border-gray-300 dark:border-gray-600
                                                                   hover:border-blue-500 dark:hover:border-blue-400
                                                                   transition-all duration-200
                                                                   flex items-center justify-center
                                                               @elseif($isPointure || $isTaille)
                                                                   px-3 py-2 rounded-lg border-2 
                                                                   bg-white dark:bg-gray-700
                                                                   border-gray-300 dark:border-gray-600 
                                                                   hover:border-blue-500 dark:hover:border-blue-400
                                                                   hover:bg-blue-50 dark:hover:bg-blue-900/30
                                                                   text-gray-700 dark:text-gray-300
                                                                   font-medium text-sm
                                                                   transition-all duration-200
                                                                   min-w-[50px]
                                                               @elseif($isCapacite)
                                                                   px-4 py-2 rounded-lg border-2 
                                                                   bg-white dark:bg-gray-700
                                                                   border-gray-300 dark:border-gray-600 
                                                                   hover:border-blue-500 dark:hover:border-blue-400
                                                                   hover:bg-blue-50 dark:hover:bg-blue-900/30
                                                                   text-gray-700 dark:text-gray-300
                                                                   font-semibold text-sm
                                                                   transition-all duration-200
                                                               @else
                                                                   px-4 py-2 rounded-lg border-2 
                                                                   bg-white dark:bg-gray-700
                                                                   border-gray-300 dark:border-gray-600 
                                                                   hover:border-blue-500 dark:hover:border-blue-400
                                                                   hover:bg-blue-50 dark:hover:bg-blue-900/30
                                                                   text-gray-700 dark:text-gray-300
                                                                   font-medium text-sm
                                                                   transition-all duration-200
                                                               @endif"
                                                        data-attribute="{{ strtolower($attribute->name) }}"
                                                        data-value="{{ $value->value }}"
                                                        @if($isColor && $value->color_code)
                                                            style="background-color: {{ $value->color_code }};"
                                                            title="{{ $value->display_value }}"
                                                        @endif>
                                                    @if($isColor && $value->color_code)
                                                        <span class="sr-only">{{ $value->display_value }}</span>
                                                        <span class="w-6 h-6 rounded-full inline-block border-2 border-white dark:border-gray-800 shadow-sm"></span>
                                                    @else
                                                        {{ $value->display_value }}
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                            <input type="hidden" 
                                                   name="attributes[{{ strtolower($attribute->name) }}]" 
                                                   id="attr-{{ strtolower($attribute->name) }}"
                                                   class="attribute-input"
                                                   @if($attribute->is_required) required @endif>
                                            
                                            <!-- Message si non sélectionné -->
                                            <div id="error-{{ strtolower($attribute->name) }}" class="hidden mt-2 text-xs text-red-600 dark:text-red-400">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Veuillez sélectionner une {{ strtolower($attribute->name) }}
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    
                                    <!-- Info sur la variation sélectionnée -->
                                    <div id="variation-info" class="hidden p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                                            <div class="text-sm text-green-700 dark:text-green-300">
                                                <span id="variation-stock"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Message si aucun attribut configuré -->
                            <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800">
                                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Aucune option disponible pour ce produit. Vous pouvez l'ajouter directement au panier.
                                </p>
                            </div>
                        @endif

                        <!-- Sélecteur de quantité avec calcul -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-4 h-4 inline mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Quantité
                            </label>
                            <div class="flex items-center space-x-4">
                                <!-- Contrôle de quantité -->
                                <div class="flex items-center border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 shadow-sm">
                                    <button type="button" 
                                            class="quantity-minus px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-l-xl transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <input type="number" 
                                           name="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="99"
                                           class="quantity-input w-16 text-center border-0 bg-transparent text-lg font-bold text-gray-900 dark:text-gray-100 focus:ring-0 focus:outline-none">
                                    <button type="button" 
                                            class="quantity-plus px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-r-xl transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Prix total calculé -->
                                <div class="flex-1 text-right">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total</div>
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 total-price">
                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton d'ajout au panier -->
                        @php
                            $hasVariations = $product->hasVariants();
                            $canAddToCart = $product->inStock() || ($hasVariations && $product->variations()->where('stock', '>', 0)->exists());
                        @endphp
                        @if($canAddToCart)
                            <form action="{{ route('cart.index') }}" method="POST" id="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variation_id" id="variation-id-input">
                                <input type="hidden" name="attributes" id="attributes-input" value="{}">
                                <!-- Champ qty avec l'ID utilisé par le script -->
                                <input type="hidden" name="qty" id="form-quantity" value="1">

                                <button type="submit" 
                                        id="add-to-cart-btn"
                                        class="w-full text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center group add-to-cart-btn disabled:opacity-50 disabled:cursor-not-allowed" style="background: var(--button-primary)">
                                    <svg class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-lg">Ajouter au panier</span>
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <button disabled 
                                    class="w-full bg-gray-400 text-white font-bold py-4 px-6 rounded-xl cursor-not-allowed shadow-inner flex items-center justify-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="text-lg">Produit indisponible</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la gestion des images, quantité et attributs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productId = {{ $product->id }};
        const basePrice = {{ $product->price }};
        const finalPrice = {{ $product->getFinalPrice() }};
        const variations = @json($product->variations ?? []);
        const quantityInput = document.querySelector('.quantity-input');
        const minusBtn = document.querySelector('.quantity-minus');
        const plusBtn = document.querySelector('.quantity-plus');
        const totalPriceElement = document.querySelector('.total-price');
        const formQuantityInput = document.getElementById('form-quantity');
        const addToCartForm = document.getElementById('add-to-cart-form');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const variationIdInput = document.getElementById('variation-id-input');
        const attributesInput = document.getElementById('attributes-input');
        const variationInfo = document.getElementById('variation-info');
        const variationStock = document.getElementById('variation-stock');
        
        let selectedAttributes = {};
        let currentVariation = null;
        let currentPrice = finalPrice;
        
        // Gestion des images
        const mainImage = document.getElementById('main-image');
        const mainImageBackdrop = document.getElementById('main-image-backdrop');
        const mainImageFrame = document.getElementById('main-image-frame');
        const thumbnails = document.querySelectorAll('.thumbnail');
        const thumbnailContainers = document.querySelectorAll('.thumbnail-container');
        const prevButton = document.getElementById('prev-image');
        const nextButton = document.getElementById('next-image');
        const currentIndexElement = document.getElementById('current-image-index');
        const totalImagesElement = document.getElementById('total-images');
        
        let currentImageIndex = 0;
        const productImages = @json($product->images->toArray() ?? []);
        const supportsHoverZoom = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        let zoomEnabled = false;

        function resetMainImageZoom() {
            if (!mainImage) return;
            mainImage.classList.remove('main-image--zoomed');
            mainImage.style.transformOrigin = '50% 50%';
            zoomEnabled = false;
        }

        if (mainImage && mainImageFrame && supportsHoverZoom) {
            mainImageFrame.addEventListener('mouseenter', function() {
                mainImage.classList.add('main-image--zoomed');
                zoomEnabled = true;
            });

            mainImageFrame.addEventListener('mousemove', function(event) {
                if (!zoomEnabled) return;
                const rect = mainImageFrame.getBoundingClientRect();
                if (!rect.width || !rect.height) return;

                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;
                const clampedX = Math.max(0, Math.min(100, x));
                const clampedY = Math.max(0, Math.min(100, y));
                mainImage.style.transformOrigin = clampedX + '% ' + clampedY + '%';
            });

            mainImageFrame.addEventListener('mouseleave', resetMainImageZoom);
        }

        // Fonction pour changer l'image principale
        function changeMainImage(index) {
            if (!mainImage || productImages.length === 0) return;
            
            currentImageIndex = index;
            const imageUrl = "{{ Storage::url('') }}" + productImages[index].path;
            
            // Animation de fondu
            resetMainImageZoom();
            mainImage.style.opacity = '0';
            
            setTimeout(() => {
                mainImage.src = imageUrl;
                if (mainImageBackdrop) {
                    mainImageBackdrop.style.backgroundImage = "url('" + imageUrl + "')";
                }
                mainImage.style.opacity = '1';
            }, 200);

            // Mise à jour de l'indicateur
            currentIndexElement.textContent = index + 1;
            
            // Mise à jour des miniatures actives
            updateActiveThumbnail();
        }

        // Fonction pour mettre à jour la miniature active
        function updateActiveThumbnail() {
            thumbnails.forEach((thumb, index) => {
                if (index === currentImageIndex) {
                    thumb.classList.add('border-blue-500');
                    thumb.classList.remove('border-transparent');
                } else {
                    thumb.classList.remove('border-blue-500');
                    thumb.classList.add('border-transparent');
                }
            });
        }

        // Événements pour les miniatures
        thumbnailContainers.forEach((container) => {
            container.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const index = Number(this.dataset.index);
                if (!Number.isInteger(index) || index < 0) {
                    return;
                }

                changeMainImage(index);
                
                // Animation de feedback sur la miniature
                const thumb = this.querySelector('.thumbnail');
                if (!thumb) {
                    return;
                }

                thumb.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    thumb.style.transform = 'scale(1)';
                }, 200);
            });
        });

        // Navigation fléchée
        if (prevButton && nextButton) {
            prevButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                let newIndex = currentImageIndex - 1;
                if (newIndex < 0) newIndex = productImages.length - 1;
                changeMainImage(newIndex);
            });

            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                let newIndex = currentImageIndex + 1;
                if (newIndex >= productImages.length) newIndex = 0;
                changeMainImage(newIndex);
            });
        }

        // Gestion des attributs
        document.querySelectorAll('.attribute-option').forEach(button => {
            button.addEventListener('click', function() {
                const attribute = this.dataset.attribute;
                const value = this.dataset.value;
                
                // Désélectionner les autres options du même attribut
                document.querySelectorAll(`[data-attribute="${attribute}"]`).forEach(btn => {
                    // Retirer les classes de sélection
                    btn.classList.remove('border-blue-500', 'bg-blue-100', 'dark:bg-blue-900', 'ring-2', 'ring-blue-500', 'ring-offset-2');
                    // Remettre les classes par défaut
                    if (!btn.style.backgroundColor) {
                        btn.classList.add('border-gray-300', 'dark:border-gray-600');
                        if (!btn.classList.contains('rounded-full')) {
                            btn.classList.add('bg-white', 'dark:bg-gray-700');
                        }
                    }
                });
                
                // Sélectionner cette option
                this.classList.add('border-blue-500', 'ring-2', 'ring-blue-500', 'ring-offset-2');
                if (!this.style.backgroundColor) {
                    this.classList.add('bg-blue-100', 'dark:bg-blue-900');
                    this.classList.remove('bg-white', 'dark:bg-gray-700');
                }
                this.classList.remove('border-gray-300', 'dark:border-gray-600');
                
                // Mettre à jour les attributs sélectionnés
                selectedAttributes[attribute] = value;
                const attrInput = document.getElementById(`attr-${attribute}`);
                if (attrInput) {
                    attrInput.value = value;
                }
                attributesInput.value = JSON.stringify(selectedAttributes);
                
                // Masquer l'erreur si elle existe
                const errorDiv = document.getElementById(`error-${attribute}`);
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                }
                
                // Chercher la variation correspondante
                findVariation();
                
                // Vérifier les attributs requis
                setTimeout(checkRequiredAttributes, 100);
            });
        });
        
        function findVariation() {
            if (Object.keys(selectedAttributes).length === 0) {
                currentVariation = null;
                currentPrice = finalPrice;
                variationInfo.classList.add('hidden');
                updateTotalPrice();
                return;
            }
            
            // Chercher une variation correspondante
            const matchingVariation = variations.find(variation => {
                const varAttrs = variation.attributes || {};
                return Object.keys(selectedAttributes).every(key => 
                    varAttrs[key] === selectedAttributes[key]
                ) && Object.keys(varAttrs).length === Object.keys(selectedAttributes).length;
            });
            
            if (matchingVariation) {
                currentVariation = matchingVariation;
                currentPrice = matchingVariation.price || finalPrice;
                variationIdInput.value = matchingVariation.id;
                
                // Afficher les infos de stock
                if (matchingVariation.stock > 0) {
                    variationStock.textContent = `Stock disponible : ${matchingVariation.stock}`;
                    variationInfo.classList.remove('hidden');
                    addToCartBtn.disabled = false;
                } else {
                    variationStock.textContent = 'Rupture de stock pour cette variation';
                    variationInfo.classList.remove('hidden');
                    addToCartBtn.disabled = true;
                }
            } else {
                currentVariation = null;
                currentPrice = finalPrice;
                variationIdInput.value = '';
                variationInfo.classList.add('hidden');
                addToCartBtn.disabled = false;
            }
            
            updateTotalPrice();
        }
        
        // Gestion de la quantité et calcul du prix
        function updateTotalPrice() {
            const quantity = parseInt(quantityInput.value) || 1;
            const totalPrice = currentPrice * quantity;
            totalPriceElement.textContent = formatPrice(totalPrice) + ' FCFA';
            formQuantityInput.value = quantity; // Mise à jour du champ hidden
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(price));
        }

        function validateQuantity() {
            let value = parseInt(quantityInput.value);
            if (isNaN(value) || value < 1) {
                value = 1;
            } else if (value > 99) {
                value = 99;
            }
            quantityInput.value = value;
            updateTotalPrice();
        }

        minusBtn?.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotalPrice();
                
                minusBtn.classList.add('scale-90');
                setTimeout(() => minusBtn.classList.remove('scale-90'), 150);
            }
        });

        plusBtn?.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < 99) {
                quantityInput.value = currentValue + 1;
                updateTotalPrice();
                
                plusBtn.classList.add('scale-90');
                setTimeout(() => plusBtn.classList.remove('scale-90'), 150);
            }
        });

        quantityInput?.addEventListener('input', validateQuantity);
        quantityInput?.addEventListener('blur', validateQuantity);

        // Gestion du formulaire - SIMPLIFIÉE
        addToCartForm?.addEventListener('submit', function(e) {
            // S'assurer que la quantité est à jour
            const currentQuantity = parseInt(quantityInput.value);
            if (isNaN(currentQuantity) || currentQuantity < 1) {
                e.preventDefault();
                quantityInput.value = 1;
                formQuantityInput.value = 1;
                updateTotalPrice();
                alert('Veuillez choisir une quantité valide');
                return;
            }
            
            // Mettre à jour le champ hidden avec la quantité actuelle
            formQuantityInput.value = currentQuantity;
            
            // Désactiver le bouton temporairement pour éviter les doubles clics
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = `
                <svg class="w-6 h-6 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span class="text-lg">Ajout en cours...</span>
            `;
            
            // Le formulaire continue normalement vers le serveur
        });

        // Initialisations
        updateTotalPrice();
        
        // Initialiser le total d'images
        if (totalImagesElement && productImages.length > 0) {
            totalImagesElement.textContent = productImages.length;
        }
        
        // Vérifier si des attributs sont requis
        const requiredAttributes = document.querySelectorAll('.attribute-input[required]');
        if (requiredAttributes.length > 0) {
            checkRequiredAttributes();
        }
        
        function checkRequiredAttributes() {
            let allSelected = true;
            requiredAttributes.forEach(input => {
                const attributeName = input.id.replace('attr-', '');
                const errorDiv = document.getElementById(`error-${attributeName}`);
                
                if (!input.value) {
                    allSelected = false;
                    if (errorDiv) errorDiv.classList.remove('hidden');
                } else {
                    if (errorDiv) errorDiv.classList.add('hidden');
                }
            });
            
            if (allSelected && requiredAttributes.length > 0) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = `
                    <svg class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-lg">Ajouter au panier</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                `;
            } else if (requiredAttributes.length > 0) {
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<span class="text-lg">⚠️ Sélectionnez toutes les options requises</span>';
            }
        }
        
        // Vérifier à chaque sélection
        document.querySelectorAll('.attribute-option').forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(checkRequiredAttributes, 100);
            });
        });
    });
</script>

<style>
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .quantity-input {
        -moz-appearance: textfield;
    }
    
    .scale-90 {
        transform: scale(0.9);
        transition: transform 0.15s ease-in-out;
    }

    .zoom-main-image-frame {
        cursor: zoom-in;
    }
    
    .main-image {
        transition: transform 0.22s ease-out, opacity 0.25s ease-in-out, transform-origin 0.08s linear;
        will-change: transform, transform-origin;
    }

    .main-image.main-image--zoomed {
        transform: scale(1.9);
    }
    
    .thumbnail {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    
    #prev-image, #next-image {
        transition: all 0.3s ease-in-out;
        cursor: pointer;
    }
    
    /* Style pour les miniatures au survol */
    .thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Style pour la miniature active */
    .thumbnail.border-blue-500 {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }

    /* Animation de spin */
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @media (hover: none), (pointer: coarse) {
        .zoom-main-image-frame {
            cursor: default;
        }

        .main-image.main-image--zoomed {
            transform: scale(1);
        }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
