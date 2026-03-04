@extends('layouts.admin')

@section('title', 'Gestion des Catégories - Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-tags text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Gestion des Catégories</h1>
            <p class="text-white/90">Organisez vos catégories et gérez vos produits</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Gestion des Catégories</h1>
            <p class="text-gray-600 dark:text-gray-400">Organisez vos produits par catégories</p>
        </div>
        <a 
            href="{{ route('admin.categories.create') }}" 
            class="flex items-center space-x-2 px-6 py-3 text-white rounded-xl hover:shadow-lg transition shadow-lg" style="background: var(--action-success)">
            <i class="fas fa-plus"></i>
            <span class="font-semibold">Nouvelle Catégorie</span>
        </a>
    </div>

    <!-- Messages de statut -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-xl">
            <div class="flex items-center space-x-2 text-green-800 dark:text-green-200">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('status'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-xl">
            <div class="flex items-center space-x-2 text-green-800 dark:text-green-200">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900 border border-amber-200 dark:border-amber-700 rounded-xl">
            <div class="flex items-center space-x-2 text-amber-800 dark:text-amber-200">
                <i class="fas fa-triangle-exclamation"></i>
                <span>{{ session('warning') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-xl">
            <div class="flex items-center space-x-2 text-red-800 dark:text-red-200">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Grille des catégories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all border border-gray-200 dark:border-gray-700">
                <!-- En-tête de la carte -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center">
                                <i class="{{ $category->icon ?? 'fas fa-folder' }} text-white text-sm"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h3>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center space-x-1">
                            <i class="fas fa-link"></i>
                            <span>{{ $category->slug }}</span>
                        </span>
                        
                        @if($category->parent)
                            <span class="flex items-center space-x-1">
                                <i class="fas fa-level-up-alt"></i>
                                <span>{{ $category->parent->name }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Contenu -->
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->products_count ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Produits</div>
                        </div>
                        
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->children_count ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sous-catégories</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <a 
                                href="{{ route('admin.categories.edit', $category) }}" 
                                class="flex items-center space-x-1 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition"
                            >
                                <i class="fas fa-edit text-xs"></i>
                                <span>Éditer</span>
                            </a>
                            
                            <a 
                                href="{{ route('admin.categories.show', $category) }}" 
                                class="flex items-center space-x-1 px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                            >
                                <i class="fas fa-eye text-xs"></i>
                                <span>Voir</span>
                            </a>
                        </div>

                        <form 
                            action="{{ route('admin.categories.destroy', $category) }}" 
                            method="POST" 
                            data-confirm="Etes-vous sur de vouloir supprimer cette categorie ?" data-confirm-title="Supprimer la categorie" data-confirm-ok="Supprimer" data-confirm-variant="danger"
                        >
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="flex items-center space-x-1 px-3 py-2 text-sm bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition"
                            >
                                <i class="fas fa-trash text-xs"></i>
                                <span>Supprimer</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <!-- État vide -->
            <div class="col-span-full text-center py-16">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-tags text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 dark:text-gray-300 mb-4">Aucune catégorie</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8">Commencez par créer votre première catégorie</p>
                <a 
                    href="{{ route('admin.categories.create') }}" 
                    class="inline-flex items-center space-x-2 px-6 py-3 text-white rounded-xl transition" style="background: var(--action-success)"
                >
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Créer une catégorie</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="mt-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                {{ $categories->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif
</div>
@endsection
