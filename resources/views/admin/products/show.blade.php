@extends('layouts.admin')

@section('title', 'Détails du Produit - ' . $product->name)

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-box text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Détails du Produit</h1>
            <p class="text-white/90">Visualisez et gérez ce produit</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $product->description ?? 'Aucune description' }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center space-x-2" style="background: var(--admin-gradient)">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center space-x-2" style="background: var(--action-primary)">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Images du produit -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Images du produit</h2>
            @if($product->images->count() > 0)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($product->images as $image)
                        <div class="relative">
                            <img src="{{ Storage::url($image->path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-image text-4xl mb-3"></i>
                    <p>Aucune image</p>
                </div>
            @endif

            @if($product->hasUsageVideo())
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold mb-3">Video de demonstration</h3>
                    <video controls preload="metadata" class="w-full rounded-xl bg-black shadow-lg">
                        <source src="{{ $product->getUsageVideoUrl() }}">
                        Votre navigateur ne supporte pas la lecture video.
                    </video>
                </div>
            @endif
        </div>

        <!-- Informations du produit -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Informations du produit</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Nom :</span>
                    <span class="font-semibold">{{ $product->name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Prix :</span>
                    @php
                        $finalPrice = $product->getFinalPrice();
                        $discountPercent = $product->getDiscountPercentage();
                    @endphp
                    <div class="text-right">
                        <span class="font-semibold text-green-600">{{ number_format($finalPrice, 0, ',', ' ') }} fcfa</span>
                        @if($discountPercent > 0)
                            <div class="text-xs text-gray-500 line-through">{{ number_format($product->price, 0, ',', ' ') }} fcfa</div>
                            <div class="text-xs font-semibold text-red-600">-{{ $discountPercent }}%</div>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Stock :</span>
                    <span class="font-semibold {{ $product->stock > 5 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $product->stock }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Catégorie :</span>
                    <span class="font-semibold">
                        @if($product->category)
                            <a href="{{ route('admin.categories.show', $product->category) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $product->category->name }}
                            </a>
                        @else
                            <span class="text-gray-400">Aucune catégorie</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Slug :</span>
                    <span class="font-mono text-sm">{{ $product->slug }}</span>
                </div>
                <div class="py-2 border-b">
                    <span class="text-gray-600 block mb-2">Description :</span>
                    <p class="text-gray-900">{{ $product->description ?? 'Aucune description' }}</p>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Date de création :</span>
                    <span class="text-sm text-gray-500">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Dernière modification :</span>
                    <span class="text-sm text-gray-500">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de vente -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Statistiques de vente</h2>
        @if(isset($salesSummary))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($salesSummary['total_qty'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600">Quantité vendue</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($salesSummary['total_revenue'] ?? 0, 0, ',', ' ') }} fcfa</div>
                    <div class="text-sm text-gray-600">Chiffre d'affaires</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($salesSummary['orders_count'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600">Commandes</div>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-3"></i>
                <p>Aucune donnée de vente disponible</p>
            </div>
        @endif
    </div>

    <!-- Actions rapides -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('admin.products.edit', $product) }}" 
           class="flex items-center space-x-2 px-4 py-2 text-white rounded-lg transition" style="background: var(--admin-gradient)">
            <i class="fas fa-edit"></i>
            <span>Modifier ce produit</span>
        </a>
        <a href="{{ route('product.show', $product->slug) }}" 
           target="_blank"
           class="flex items-center space-x-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
            <i class="fas fa-eye"></i>
            <span>Voir sur le site</span>
        </a>
        <a href="{{ route('admin.products.index') }}" 
           class="flex items-center space-x-2 px-4 py-2 text-white rounded-lg transition" style="background: var(--action-primary)">
            <i class="fas fa-arrow-left"></i>
            <span>Retour aux produits</span>
        </a>
    </div>
</div>
@endsection
        
