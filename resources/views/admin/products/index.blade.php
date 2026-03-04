@extends('layouts.admin')

@section('title', 'Gestion des Produits - Sakha Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-box text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Gestion des Produits</h1>
            <p class="text-white/90">Gerez tous vos produits, leur stock et leurs variations</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8 p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg" style="background: var(--icon-gradient)">
                <i class="fas fa-box text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Produits</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Gerez tous les produits de votre boutique</p>
            </div>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="flex items-center space-x-2 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg"
           style="background: var(--action-success)">
            <i class="fas fa-plus"></i>
            <span>Nouveau produit</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center space-x-3">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
            <span class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('status'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center space-x-3">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
            <span class="text-green-800 dark:text-green-200 font-medium">{{ session('status') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl flex items-center space-x-3">
            <i class="fas fa-triangle-exclamation text-amber-600 dark:text-amber-400 text-lg"></i>
            <span class="text-amber-800 dark:text-amber-200 font-medium">{{ session('warning') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center space-x-3">
            <i class="fas fa-circle-exclamation text-red-600 dark:text-red-400 text-lg"></i>
            <span class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-list mr-3 text-purple-600 dark:text-purple-400"></i>
                Liste des produits ({{ $products->total() }})
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/40">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 flex items-center">
                            <i class="fas fa-hashtag text-gray-400 dark:text-gray-500 mr-2"></i>
                            ID
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 flex items-center">
                            <i class="fas fa-box text-gray-400 dark:text-gray-500 mr-2"></i>
                            Produit
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 flex items-center">
                            <i class="fas fa-tag text-gray-400 dark:text-gray-500 mr-2"></i>
                            Prix
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 flex items-center">
                            <i class="fas fa-layer-group text-gray-400 dark:text-gray-500 mr-2"></i>
                            Stock
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 flex items-center">
                            <i class="fas fa-cog text-gray-400 dark:text-gray-500 mr-2"></i>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-500 dark:text-gray-400">#{{ $product->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($product->images->first())
                                        <img class="h-10 w-10 rounded-lg object-cover shadow-sm"
                                             src="{{ Storage::url($product->images->first()->path) }}"
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-image text-gray-400 dark:text-gray-300 text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($product->description, 40) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $finalPrice = $product->getFinalPrice();
                                    $discountPercent = $product->getDiscountPercentage();
                                @endphp
                                <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($finalPrice, 0, ',', ' ') }} fcfa
                                </div>
                                @if($discountPercent > 0)
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                            {{ number_format($product->price, 0, ',', ' ') }} fcfa
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">
                                            -{{ $discountPercent }}%
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg font-semibold {{ $product->stock > 5 ? 'text-green-600 dark:text-green-400' : ($product->stock > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ $product->stock }}
                                    </span>
                                    @if($product->stock == 0)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300">
                                            Rupture
                                        </span>
                                    @elseif($product->stock < 5)
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300">
                                            Faible
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="flex items-center space-x-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition px-3 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30"
                                       title="Voir">
                                        <i class="fas fa-eye text-sm"></i>
                                        <span class="text-sm">Voir</span>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="flex items-center space-x-1 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 transition px-3 py-2 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/30"
                                       title="Modifier">
                                        <i class="fas fa-edit text-sm"></i>
                                        <span class="text-sm">Modifier</span>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST"
                                          class="inline"
                                          data-confirm="Etes-vous sur de vouloir supprimer ce produit ?"
                                          data-confirm-title="Supprimer le produit"
                                          data-confirm-ok="Supprimer"
                                          data-confirm-variant="danger">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="flex items-center space-x-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30"
                                                title="Supprimer">
                                            <i class="fas fa-trash text-sm"></i>
                                            <span class="text-sm">Supprimer</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
