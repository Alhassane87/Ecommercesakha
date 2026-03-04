@extends('layouts.admin')

@section('title', 'Editer ' . $category->name . ' - Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-folder-edit text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Modifier la categorie</h1>
            <p class="text-white/90">Mettez a jour les informations de la categorie</p>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
        <form id="categoryUpdateForm" action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Nom de la categorie <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Ex: Electronique, Mode, Maison"
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Slug (URL) <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="slug"
                    value="{{ old('slug', $category->slug) }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Ex: electronique, mode, maison"
                >
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Le slug sera utilise dans l URL de la categorie.</p>
            </div>

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
                    'fas fa-wine-bottle' => 'Vins',
                    'fas fa-camera' => 'Photo',
                    'fas fa-bicycle' => 'Velo',
                    'fas fa-campground' => 'Outdoor',
                    'fas fa-graduation-cap' => 'Education',
                    'fas fa-shopping-bag' => 'Shopping',
                    'fas fa-clock' => 'Accessoires',
                    'fas fa-plane' => 'Voyage',
                    'fas fa-seedling' => 'Jardin',
                ];
                $selectedIcon = old('icon', $category->icon ?? 'fas fa-tag');
            @endphp

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Icone de la categorie
                </label>
                <div class="grid grid-cols-4 md:grid-cols-6 gap-3 mb-3">
                    @foreach($icons as $iconClass => $iconName)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="icon" value="{{ $iconClass }}" class="hidden peer" {{ $selectedIcon === $iconClass ? 'checked' : '' }}>
                            <div class="flex flex-col items-center p-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-purple-400 peer-checked:border-purple-600 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition">
                                <i class="{{ $iconClass }} text-gray-600 dark:text-gray-400 text-xl mb-2"></i>
                                <span class="text-xs text-gray-600 dark:text-gray-400 text-center">{{ $iconName }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Choisissez une icone representative pour cette categorie.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Description (optionnel)</label>
                <textarea
                    name="description"
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Decrivez cette categorie"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Categorie parente (optionnel)</label>
                <select
                    name="parent_id"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                >
                    <option value="">-- Aucune categorie parente --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ (string) old('parent_id', $category->parent_id) === (string) $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="flex items-center space-x-3">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                    >
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Categorie active</span>
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Les categories inactives ne seront pas visibles sur le site.</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Apercu de la categorie</h3>
                <div class="flex items-center space-x-4">
                    <div id="iconPreview" class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="{{ $selectedIcon }} text-white text-lg"></i>
                    </div>
                    <div>
                        <div id="namePreview" class="font-semibold text-gray-900 dark:text-white">{{ old('name', $category->name) }}</div>
                        <div id="descriptionPreview" class="text-sm text-gray-600 dark:text-gray-400">{{ old('description', $category->description) ?: 'Description apparaitra ici' }}</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-2 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour a la liste</span>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.categories.show', $category) }}" class="flex items-center space-x-2 px-6 py-3 border border-blue-300 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900 transition">
                        <i class="fas fa-eye"></i>
                        <span>Voir</span>
                    </a>

                    <button type="submit" form="categoryUpdateForm" class="flex items-center space-x-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:from-purple-700 hover:to-blue-700 transition shadow-lg">
                        <i class="fas fa-save"></i>
                        <span class="font-semibold">Mettre a jour</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-tags text-purple-600 mr-2"></i>
                    Attributs de la categorie
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Definissez les options disponibles pour les produits de cette categorie.
                </p>
            </div>
            <button type="button" onclick="showAddAttributeModal()" class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Ajouter un attribut</span>
            </button>
        </div>

        <div class="space-y-4" id="attributes-list">
            @forelse($category->attributes as $attribute)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tag text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $attribute->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Type: {{ $attribute->type }} |
                                    {{ $attribute->is_required ? 'Obligatoire' : 'Optionnel' }} |
                                    {{ $attribute->values->count() }} valeur(s)
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="showAddValueModal({{ $attribute->id }})" class="px-3 py-1 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                <i class="fas fa-plus mr-1"></i>Valeur
                            </button>
                            <form action="{{ route('admin.categories.attributes.destroy', $attribute) }}" method="POST" data-confirm="Supprimer cet attribut et toutes ses valeurs ?" data-confirm-title="Supprimer attribut" data-confirm-ok="Supprimer" data-confirm-variant="danger">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($attribute->values as $value)
                            <div class="flex items-center space-x-2 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                @if($value->color_code)
                                    <div class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $value->color_code }}"></div>
                                @endif
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $value->display_value }}</span>
                                <form action="{{ route('admin.categories.attributes.values.destroy', $value) }}" method="POST" class="inline" data-confirm="Supprimer cette valeur ?" data-confirm-title="Supprimer valeur" data-confirm-ok="Supprimer" data-confirm-variant="danger">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                    <i class="fas fa-tags text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">Aucun attribut defini</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Ajoutez des attributs pour la selection d options (Pointure, Couleur, etc.).</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="addAttributeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Nouvel attribut</h3>
            <button type="button" onclick="hideAddAttributeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.categories.attributes.store', $category) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Nom de l attribut</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Ex: Pointure, Couleur, Taille, Modele">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Type</label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="select">Selection (liste deroulante)</option>
                        <option value="text">Texte libre</option>
                        <option value="number">Nombre</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_required" value="1" class="w-4 h-4">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Obligatoire</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Valeurs (une par ligne)</label>
                    <textarea name="values" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Ex pour Pointure:&#10;36&#10;37&#10;38&#10;39&#10;40"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Separez chaque valeur par un retour a la ligne.</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 mt-6">
                <button type="button" onclick="hideAddAttributeModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700">Creer</button>
            </div>
        </form>
    </div>
</div>

<div id="addValueModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Nouvelle valeur</h3>
            <button type="button" onclick="hideAddValueModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="addValueForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Valeur</label>
                    <input type="text" name="value" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Ex: 40, Rouge, M">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Valeur d affichage (optionnel)</label>
                    <input type="text" name="display_value" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Ex: Pointure 40">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Code couleur (optionnel)</label>
                    <input type="color" name="color_code" class="w-full h-12 border border-gray-300 dark:border-gray-600 rounded-lg">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 mt-6">
                <button type="button" onclick="hideAddValueModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const iconInputs = document.querySelectorAll('input[name="icon"]');
    const iconPreview = document.getElementById('iconPreview');
    const nameInput = document.querySelector('input[name="name"]');
    const namePreview = document.getElementById('namePreview');
    const descriptionInput = document.querySelector('textarea[name="description"]');
    const descriptionPreview = document.getElementById('descriptionPreview');

    iconInputs.forEach((input) => {
        input.addEventListener('change', function () {
            if (!iconPreview) {
                return;
            }
            iconPreview.innerHTML = `<i class="${this.value} text-white text-lg"></i>`;
        });
    });

    if (nameInput && namePreview) {
        nameInput.addEventListener('input', function () {
            namePreview.textContent = this.value || 'Nom de la categorie';
        });
    }

    if (descriptionInput && descriptionPreview) {
        descriptionInput.addEventListener('input', function () {
            descriptionPreview.textContent = this.value || 'Description apparaitra ici';
        });
    }
});

function showAddAttributeModal() {
    document.getElementById('addAttributeModal').classList.remove('hidden');
}

function hideAddAttributeModal() {
    document.getElementById('addAttributeModal').classList.add('hidden');
}

function showAddValueModal(attributeId) {
    const form = document.getElementById('addValueForm');
    form.action = `/admin/categories/attributes/${attributeId}/values`;
    document.getElementById('addValueModal').classList.remove('hidden');
}

function hideAddValueModal() {
    document.getElementById('addValueModal').classList.add('hidden');
}
</script>
@endsection
