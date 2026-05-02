@extends('layouts.admin')

@section('title', 'Modifier ' . $product->name . ' - Sakha Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-edit text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Modifier le Produit</h1>
            <p class="text-white/90">Mettez à jour les informations du produit</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier produit</h1>
                <p class="text-gray-600 mt-2">{{ $product->name }}</p>
            </div>
        </div>
        <a href="{{ route('admin.products.index') }}" 
           class="flex items-center space-x-2 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg" style="background: var(--action-primary)">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form id="product-edit-form" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Flash messages & validation errors --}}
                    @if(session('status'))
                        <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-700">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Colonne gauche -->
                        <div class="space-y-6">
                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-font text-blue-600 mr-2"></i>
                                    Nom du produit
                                </label>
                                <input name="name" 
                                       value="{{ $product->name }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>
                            </div>

                            <!-- Catégorie -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-tags text-green-600 mr-2"></i>
                                    Catégorie
                                </label>
                                <select name="category_id" 
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                    <option value="">-- Aucune catégorie --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @if($product->category_id == $cat->id) selected @endif>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Images existantes -->
                            @if($product->images->count())
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                        <i class="fas fa-images text-orange-600 mr-2"></i>
                                        Images existantes
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                        @foreach($product->images as $img)
                                            <div class="border border-gray-200 rounded-xl p-3 bg-gray-50">
                                                <img src="{{ asset('storage/' . $img->path) }}" 
                                                     alt="" 
                                                     class="w-full h-32 object-cover rounded-lg mb-2">
                                                <button type="button"
                                                        data-delete-url="{{ route('admin.products.images.destroy', ['product' => $product->id, 'image' => $img->id]) }}"
                                                        class="delete-image-btn w-full flex items-center justify-center space-x-1 text-red-600 hover:text-red-800 hover:bg-red-50 py-2 rounded-lg transition text-sm">
                                                    <i class="fas fa-trash"></i>
                                                    <span>Supprimer</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Nouvelles images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                    Ajouter des images
                                </label>
                                <input type="file" 
                                       name="images[]" 
                                       multiple 
                                        accept="image/*,.heic,.heif,.avif" 
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition">
                            </div>

                            <!-- Video de demonstration -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-video text-rose-600 mr-2"></i>
                                    Video de demonstration
                                </label>

                                @if($product->hasUsageVideo())
                                    <div class="rounded-2xl border border-rose-100 bg-rose-50/60 p-4 mb-4">
                                        <video controls preload="metadata" class="w-full rounded-xl bg-black shadow-md">
                                            <source src="{{ $product->getUsageVideoUrl() }}">
                                            Votre navigateur ne supporte pas la lecture video.
                                        </video>
                                        <label class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                                            <input type="checkbox" name="remove_usage_video" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                            Supprimer la video actuelle
                                        </label>
                                    </div>
                                @endif

                                <input type="file"
                                       name="usage_video"
                                       accept="video/mp4,video/webm,video/ogg,video/quicktime,.mp4,.mov,.webm,.ogg,.m4v"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition">
                                <p class="hidden text-xs text-gray-500 mt-2" aria-hidden="true">
                                    Ajoutez une nouvelle video pour remplacer l’actuelle ou laissez vide pour la conserver.
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Ajoutez une nouvelle video pour remplacer l'actuelle ou laissez vide pour la conserver.
                                </p>
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div class="space-y-6">
                            <!-- Slug -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-link text-purple-600 mr-2"></i>
                                    Slug (URL)
                                </label>
                                <input name="slug" 
                                       value="{{ $product->slug }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                       required>
                            </div>

                            @php
                                $basePrice = (float) old('price', $product->price);
                                $storedDiscountPrice = old('discount_price', $product->discount_price);
                                $computedDiscountPercent = old('discount_percent');
                                if (($computedDiscountPercent === null || $computedDiscountPercent === '') && $storedDiscountPrice !== null && $storedDiscountPrice !== '' && $basePrice > 0 && (float) $storedDiscountPrice < $basePrice) {
                                    $computedDiscountPercent = number_format((($basePrice - (float) $storedDiscountPrice) / $basePrice) * 100, 2, '.', '');
                                }
                            @endphp

                            <!-- Prix -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-tag text-yellow-600 mr-2"></i>
                                    Prix avant promotion
                                </label>
                                <input name="price" 
                                       id="price-input"
                                       type="number" 
                                       step="0.01" 
                                       value="{{ old('price', $product->price) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-percent text-blue-600 mr-2"></i>
                                    Reduction (%)
                                </label>
                                <input name="discount_percent"
                                       id="discount-percent-input"
                                       type="number"
                                       step="0.01"
                                       min="0"
                                       max="99.99"
                                       value="{{ $computedDiscountPercent }}"
                                       placeholder="Ex: 10 ou 20"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-tags text-emerald-600 mr-2"></i>
                                    Prix actuel
                                </label>
                                <input name="discount_price"
                                       id="discount-price-input"
                                       type="number"
                                       step="0.01"
                                       min="0"
                                       value="{{ $storedDiscountPrice }}"
                                       placeholder="Prix apres reduction"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                <p class="text-xs text-gray-500 mt-1">Remplissez soit le pourcentage, soit le prix actuel.</p>
                            </div>

                            <!-- Stock -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-layer-group text-red-600 mr-2"></i>
                                    Stock
                                </label>
                                <input name="stock" 
                                       type="number" 
                                       value="{{ old('stock', $product->stock) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            </div>

                            <!-- Description -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-align-left text-indigo-600 mr-2"></i>
                                    Description
                                </label>
                                <textarea name="description" 
                                          rows="4"
                                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ $product->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="flex items-center space-x-2 text-white px-8 py-3 rounded-xl font-semibold transition shadow-lg" style="background: var(--admin-gradient)">
                            <i class="fas fa-save"></i>
                            <span>Mettre à jour</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Statistiques -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Aperçu ventes
                </h3>
                
                @if(isset($salesSummary))
                    <div class="space-y-4">
                        <!-- Quantité vendue -->
                        <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($salesSummary['total_qty']) }}</div>
                            <div class="text-sm text-gray-600 mt-1">Quantité vendue</div>
                        </div>

                        <!-- Chiffre d'affaires -->
                        <div class="text-center p-4 bg-green-50 rounded-xl border border-green-100">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($salesSummary['total_revenue'], 0, ',', ' ') }} fcfa</div>
                            <div class="text-sm text-gray-600 mt-1">Chiffre d'affaires</div>
                        </div>

                        <!-- Commandes récentes -->
                        <div class="mt-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-clock text-orange-600 mr-2"></i>
                                Commandes récentes
                            </h4>
                            @if($salesSummary['recent']->count())
                                <div class="space-y-3">
                                    @foreach($salesSummary['recent'] as $item)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div>
                                                <div class="font-medium text-gray-900 text-sm">
                                                    {{ $item->qty }} × {{ number_format($item->unit_price, 0, ',', ' ') }} fcfa
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                            <div>
                                                @if($item->order)
                                                    <a href="{{ route('admin.orders.edit', $item->order->id) }}" 
                                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                        #{{ $item->order->id }}
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400">—</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400">
                                    <i class="fas fa-shopping-cart text-lg mb-2"></i>
                                    <p>Aucune vente récente</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-chart-bar text-2xl mb-3"></i>
                        <p class="text-sm">Aucune donnée de ventes disponible</p>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('product-edit-form');
    const priceInput = document.getElementById('price-input');
    const discountPercentInput = document.getElementById('discount-percent-input');
    const discountPriceInput = document.getElementById('discount-price-input');
    let csrf = null;
    if (form) {
        const tokenInput = form.querySelector('input[name="_token"]');
        if (tokenInput) csrf = tokenInput.value;
    }

    const toNumber = (value) => {
        const parsed = Number.parseFloat(value);
        return Number.isFinite(parsed) ? parsed : NaN;
    };

    const roundMoney = (value) => Math.round(value * 100) / 100;

    if (priceInput && discountPercentInput && discountPriceInput) {
        discountPercentInput.addEventListener('input', function() {
            const price = toNumber(priceInput.value);
            const percent = toNumber(this.value);
            if (!Number.isFinite(price) || price <= 0 || !Number.isFinite(percent) || percent <= 0) {
                return;
            }
            const computed = roundMoney(price * (1 - (percent / 100)));
            discountPriceInput.value = computed > 0 ? computed.toFixed(2) : '';
        });

        discountPriceInput.addEventListener('input', function() {
            const price = toNumber(priceInput.value);
            const promo = toNumber(this.value);
            if (!Number.isFinite(price) || price <= 0 || !Number.isFinite(promo) || promo <= 0 || promo >= price) {
                return;
            }
            const computed = roundMoney(((price - promo) / price) * 100);
            discountPercentInput.value = computed.toFixed(2);
        });

        priceInput.addEventListener('input', function() {
            const price = toNumber(this.value);
            const percent = toNumber(discountPercentInput.value);
            if (Number.isFinite(price) && price > 0 && Number.isFinite(percent) && percent > 0) {
                const computed = roundMoney(price * (1 - (percent / 100)));
                discountPriceInput.value = computed > 0 ? computed.toFixed(2) : '';
            }
        });
    }

    document.querySelectorAll('.delete-image-btn').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            const ok = window.AppConfirm
                ? await window.AppConfirm('Confirmez-vous la suppression de cette image ?', {
                    title: 'Supprimer image',
                    confirmText: 'Supprimer',
                    variant: 'danger'
                })
                : confirm('Confirmez-vous la suppression de cette image ?');

            if (!ok) return;
            const url = btn.dataset.deleteUrl;
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            }).then(function(response) {
                if (response.ok) {
                    // enlever le bloc visuel de l'image
                    const wrapper = btn.closest('.border');
                    if (wrapper) wrapper.remove();
                } else {
                    response.text().then(function(t){ alert('Erreur suppression: ' + t); });
                }
            }).catch(function(){
                alert('Erreur réseau lors de la suppression de l\'image');
            });
        });
    });
});
</script>
@endpush
