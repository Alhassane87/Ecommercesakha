@extends('layouts.admin')

@section('title', 'Nouvelle Categorie - Admin')

@push('styles')
<style>
    .dark .category-create-form {
        border: 1px solid #374151;
    }

    .dark .category-create-form input[type="text"],
    .dark .category-create-form select,
    .dark .category-create-form textarea {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    .dark .category-create-form input::placeholder,
    .dark .category-create-form textarea::placeholder {
        color: #9ca3af;
    }

    .dark .category-create-form .icon-choice i,
    .dark .category-create-form .icon-choice span {
        color: #d1d5db;
    }
</style>
@endpush

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-folder-plus text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Creer une Categorie</h1>
            <p class="text-white/90">Ajoutez une nouvelle categorie</p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- En-tete -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Nouvelle Categorie</h1>
        <p class="text-gray-600 dark:text-gray-300">Creez une nouvelle categorie pour organiser vos produits</p>
    </div>

    <!-- Formulaire -->
    <div class="category-create-form bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Nom -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Nom de la categorie
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Ex: Electronique, Mode, Maison..."
                >
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Slug (URL)
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="slug" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Ex: electronique, mode, maison"
                >
                <p class="text-xs text-gray-500 dark:text-gray-300 mt-2">Le slug sera utilise dans l'URL de la categorie</p>
            </div>

            <!-- Icone -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Icone de la categorie
                    <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-4 md:grid-cols-6 gap-3 mb-3">
                    @php
                        $icons = [
                            'fas fa-mobile-alt' => 'Electronique',
                            'fas fa-tshirt' => 'Mode',
                            'fas fa-shoe-prints' => 'Baskets',
                            'fas fa-home' => 'Maison',
                            'fas fa-dumbbell' => 'Sport',
                            'fas fa-laptop' => 'Informatique',
                            'fas fa-book' => 'Livres',
                            'fas fa-utensils' => 'Cuisine',
                            'fas fa-heart' => 'Beaute',
                            'fas fa-gamepad' => 'Jeux',
                            'fas fa-music' => 'Musique',
                            'fas fa-car' => 'Auto',
                            'fas fa-baby' => 'Bebe',
                            'fas fa-tools' => 'Bricolage',
                            'fas fa-paw' => 'Animaux',
                            'fas fa-gem' => 'Bijoux',
                            'fas fa-pills' => 'Complement alimentaire',
                            'fas fa-prescription-bottle-alt' => 'Parapharmacie',
                            'fas fa-leaf' => 'Bio naturel',
                            'fas fa-apple-alt' => 'Nutrition',
                            'fas fa-heartbeat' => 'Sante',
                            'fas fa-medkit' => 'Soins',
                            'fas fa-blender' => 'Electromenager',
                            'fas fa-couch' => 'Meubles',
                            'fas fa-store' => 'Supermarche',
                            'fas fa-shipping-fast' => 'Logistique',
                            
                            'fas fa-camera' => 'Photo',
                            'fas fa-bicycle' => 'Velo',
                            'fas fa-campground' => 'Outdoor',
                            'fas fa-graduation-cap' => 'Education',
                            'fas fa-shopping-bag' => 'Shopping',
                            'fas fa-clock' => 'Accessoires',
                            'fas fa-plane' => 'Voyage',
                            'fas fa-seedling' => 'Jardin',
                        ];
                    @endphp
                    @foreach($icons as $iconClass => $iconName)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="icon" value="{{ $iconClass }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                            <div class="icon-choice flex flex-col items-center p-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-purple-400 peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition">
                                <i class="{{ $iconClass }} text-gray-600 dark:text-gray-300 text-xl mb-2 peer-checked:text-purple-600"></i>
                                <span class="text-xs text-gray-600 dark:text-gray-300 text-center">{{ $iconName }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-300">Choisissez une icone representative pour cette categorie</p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Description (optionnel)
                </label>
                <textarea 
                    name="description" 
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Decrivez cette categorie..."
                ></textarea>
            </div>

            <!-- Categorie parente -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Categorie parente (optionnel)
                </label>
                <select 
                    name="parent_id" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                >
                    <option value="">-- Aucune categorie parente --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Statut actif -->
            <div>
                <label class="flex items-center space-x-3">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        checked
                        class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                    >
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Categorie active</span>
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">Les categories inactives ne seront pas visibles sur le site</p>
            </div>

            <!-- Apercu en temps reel -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Apercu de la categorie</h3>
                <div class="flex items-center space-x-4">
                    <div id="iconPreview" class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <div id="namePreview" class="font-semibold text-gray-900 dark:text-white">Nom de la categorie</div>
                        <div id="descriptionPreview" class="text-sm text-gray-600 dark:text-gray-300">Description apparaitra ici</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a 
                    href="{{ route('admin.categories.index') }}" 
                    class="flex items-center space-x-2 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour a la liste</span>
                </a>
                
                <button 
                    type="submit" 
                    class="flex items-center space-x-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:from-purple-700 hover:to-blue-700 transition shadow-lg"
                >
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Creer la categorie</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mise a jour de l'apercu de l'icone
    const iconInputs = document.querySelectorAll('input[name="icon"]');
    const iconPreview = document.getElementById('iconPreview');
    
    iconInputs.forEach(input => {
        input.addEventListener('change', function() {
            const iconClass = this.value;
            iconPreview.innerHTML = `<i class="${iconClass} text-white text-lg"></i>`;
        });
    });

    // Mise a jour de l'apercu du nom
    const nameInput = document.querySelector('input[name="name"]');
    const namePreview = document.getElementById('namePreview');
    
    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || 'Nom de la categorie';
    });

    // Mise a jour de l'apercu de la description
    const descriptionInput = document.querySelector('textarea[name="description"]');
    const descriptionPreview = document.getElementById('descriptionPreview');
    
    descriptionInput.addEventListener('input', function() {
        descriptionPreview.textContent = this.value || 'Description apparaitra ici';
    });
});
</script>
@endsection



