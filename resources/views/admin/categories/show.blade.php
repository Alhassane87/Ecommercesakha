@extends('layouts.admin')

@section('title', $category->name . ' - Sakha Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-tags text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Détails de la Catégorie</h1>
            <p class="text-white/90">Visualisez et gérez cette catégorie</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- En-tête avec boutons d'action -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: var(--icon-gradient)">
                <i class="fas fa-tags text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    {{ $category->name }}
                </h1>
                <p class="text-gray-600 mt-2 flex items-center">
                    <i class="fas fa-align-left text-gray-400 mr-2"></i>
                    {{ $category->description ?? 'Aucune description' }}
                </p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}" 
               class="flex items-center space-x-2 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg" style="background: var(--admin-gradient)">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center space-x-2 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg" style="background: var(--action-primary)">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1 flex items-center">
                        <i class="fas fa-boxes mr-2"></i>
                        Total Produits
                    </p>
                    <p class="text-3xl font-bold text-gray-900">{{ $category->products->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        En Stock
                    </p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ $category->products->where('stock', '>', 0)->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Rupture
                    </p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ $category->products->where('stock', 0)->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Produits -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-cubes text-purple-600 mr-3"></i>
                    Produits de cette catégorie
                </h2>
                <div class="flex items-center space-x-4">
                    <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                        <i class="fas fa-hashtag mr-1"></i>
                        {{ $products->total() }} produit(s)
                    </span>
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                       class="flex items-center space-x-2 text-white px-4 py-2 rounded-lg font-semibold transition shadow-lg" style="background: var(--action-success)">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter</span>
                    </a>
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-purple-200 group">
                            <!-- En-tête de la carte -->
                            <div class="relative">
                                @if($product->images->first())
                                    <img class="w-full h-48 object-cover rounded-t-xl" 
                                         src="{{ Storage::url($product->images->first()->path) }}" 
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-xl flex items-center justify-center">
                                        <i class="fas fa-image text-gray-300 text-4xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Badge statut -->
                                <div class="absolute top-3 right-3">
                                    @if($product->stock > 5)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i> En stock
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Stock faible
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-times-circle mr-1"></i> Rupture
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Corps de la carte -->
                            <div class="p-4">
                                <!-- Nom et description -->
                                <div class="mb-3">
                                    <h3 class="font-bold text-gray-900 text-lg mb-1 flex items-center group-hover:text-purple-600 transition-colors">
                                        <i class="fas fa-box text-gray-400 mr-2 text-sm"></i>
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-gray-600 text-sm line-clamp-2">
                                        <i class="fas fa-align-left text-gray-400 mr-1 text-xs"></i>
                                        {{ $product->description ? Str::limit($product->description, 80) : 'Aucune description' }}
                                    </p>
                                </div>

                                <!-- Informations prix et stock -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="text-center p-2 bg-blue-50 rounded-lg border border-blue-100">
                                        <div class="flex items-center justify-center mb-1">
                                            <i class="fas fa-tag text-blue-600 text-xs mr-1"></i>
                                            <span class="text-xs text-gray-600">Prix</span>
                                        </div>
                                        <div class="text-sm font-bold text-blue-600">
                                            {{ number_format($product->price, 0, ',', ' ') }} fcfa
                                        </div>
                                    </div>
                                    <div class="text-center p-2 bg-green-50 rounded-lg border border-green-100">
                                        <div class="flex items-center justify-center mb-1">
                                            <i class="fas fa-layer-group text-green-600 text-xs mr-1"></i>
                                            <span class="text-xs text-gray-600">Stock</span>
                                        </div>
                                        <div class="text-sm font-bold text-green-600">
                                            {{ $product->stock }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="flex items-center space-x-1 text-blue-600 hover:text-blue-800 transition px-2 py-1 rounded hover:bg-blue-50"
                                           title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                            <span class="text-xs">Modifier</span>
                                        </a>
                                        <a href="{{ route('product.show', $product->slug) }}" 
                                           target="_blank"
                                           class="flex items-center space-x-1 text-green-600 hover:text-green-800 transition px-2 py-1 rounded hover:bg-green-50"
                                           title="Voir sur le site">
                                            <i class="fas fa-eye text-sm"></i>
                                            <span class="text-xs">Voir</span>
                                        </a>
                                    </div>
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="flex items-center space-x-1 text-purple-600 hover:text-purple-800 transition px-2 py-1 rounded hover:bg-purple-50"
                                       title="Détails">
                                        <i class="fas fa-info-circle text-sm"></i>
                                        <span class="text-xs">Détails</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Aucun produit
                </h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Cette catégorie ne contient aucun produit pour le moment.
                </p>
                <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                   class="inline-flex items-center space-x-2 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg" style="background: var(--action-primary)">
                    <i class="fas fa-plus"></i>
                    <span>Créer un produit</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Informations détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations de la catégorie -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-info-circle text-purple-600 mr-3"></i>
                Informations de la catégorie
            </h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium flex items-center">
                        <i class="fas fa-font text-blue-500 mr-2"></i>
                        Nom :
                    </span>
                    <span class="text-gray-900 font-semibold">{{ $category->name }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium flex items-center">
                        <i class="fas fa-link text-green-500 mr-2"></i>
                        Slug :
                    </span>
                    <span class="text-gray-900 font-mono text-sm">{{ $category->slug }}</span>
                </div>
                
                <div class="py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium block mb-2 flex items-center">
                        <i class="fas fa-align-left text-yellow-500 mr-2"></i>
                        Description :
                    </span>
                    <p class="text-gray-900">{{ $category->description ?? 'Aucune description' }}</p>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium flex items-center">
                        <i class="fas fa-calendar-plus text-orange-500 mr-2"></i>
                        Créée le :
                    </span>
                    <span class="text-gray-900">{{ $category->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600 font-medium flex items-center">
                        <i class="fas fa-calendar-check text-red-500 mr-2"></i>
                        Modifiée le :
                    </span>
                    <span class="text-gray-900">{{ $category->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-3"></i>
                Actions Rapides
            </h3>
            
            <div class="space-y-4">
                <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                   class="flex items-center space-x-4 p-4 text-white rounded-xl transition shadow-lg" style="background: var(--action-success)">
                    <i class="fas fa-plus-circle text-xl"></i>
                    <div>
                        <div class="font-semibold">Créer un produit</div>
                        <div class="text-green-100 text-sm">Dans cette catégorie</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="flex items-center space-x-4 p-4 text-white rounded-xl hover:shadow-lg transition shadow-lg" style="background: var(--button-primary)">
                    <i class="fas fa-edit text-xl"></i>
                    <div>
                        <div class="font-semibold">Modifier la catégorie</div>
                        <div class="text-blue-100 text-sm">Modifier les informations</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.products.index') }}?category={{ $category->id }}" 
                   class="flex items-center space-x-4 p-4 text-white rounded-xl hover:shadow-lg transition shadow-lg" style="background: var(--action-primary)">
                    <i class="fas fa-list text-xl"></i>
                    <div>
                        <div class="font-semibold">Produits de la catégorie</div>
                        <div class="text-purple-100 text-sm">Voir la liste complète</div>
                    </div>
                </a>

                <a href="{{ route('categories.show', $category->slug) }}" 
                   target="_blank"
                   class="flex items-center space-x-4 p-4 text-white rounded-xl hover:shadow-lg transition shadow-lg" style="background: var(--admin-gradient)">
                    <i class="fas fa-external-link-alt text-xl"></i>
                    <div>
                        <div class="font-semibold">Voir sur le site</div>
                        <div class="text-orange-100 text-sm">Page publique</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection