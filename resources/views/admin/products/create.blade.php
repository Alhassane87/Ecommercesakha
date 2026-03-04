@extends('layouts.admin')

@push('styles')
<style>
    .dark .product-create-form {
        background-color: #1f2937;
        border: 1px solid #374151;
    }

    .dark .product-create-form h2,
    .dark .product-create-form label {
        color: #e5e7eb;
    }

    .dark .product-create-form .text-gray-500,
    .dark .product-create-form .text-gray-600,
    .dark .product-create-form .text-gray-700 {
        color: #d1d5db;
    }

    .dark .product-create-form input[type="text"],
    .dark .product-create-form input[type="number"],
    .dark .product-create-form select,
    .dark .product-create-form textarea {
        background-color: #111827;
        border-color: #4b5563;
        color: #f3f4f6;
    }

    .dark .product-create-form input::placeholder,
    .dark .product-create-form textarea::placeholder {
        color: #9ca3af;
    }

    .dark .product-create-form .border-dashed {
        border-color: #4b5563;
    }

    .dark .product-create-form .option-chip {
        border-color: #4b5563;
        background-color: #1f2937;
    }

    .dark .product-create-form .option-chip:hover {
        background-color: #111827;
    }

    .dark .product-create-form .cancel-link {
        background-color: #374151;
        color: #f3f4f6;
    }

    .dark .product-create-form .cancel-link:hover {
        background-color: #4b5563;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Titre -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
            <i class="fas fa-box-open text-purple-600"></i>
            Nouveau produit
        </h1>
        <p class="text-gray-500 dark:text-gray-300 mt-1">
            Creez un nouveau produit et ajoutez-le a votre catalogue
        </p>
    </div>

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4 text-sm text-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4 text-sm text-red-700 dark:text-red-300">
            <p class="font-semibold">Le formulaire contient des erreurs :</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
          class="product-create-form bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-8 space-y-8">
        @csrf

        <!-- SECTION : Informations generales -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                Informations generales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nom du produit</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Slug</label>
                    <input type="text" name="slug" required value="{{ old('slug') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Categorie</label>
                    <select name="category_id" id="category_select"
                            class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">-- Aucune categorie --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) old('category_id', $defaultCategoryId) === (string) $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION : Options produit par categorie -->
        <div id="category-attributes-section" class="hidden">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-list text-amber-500"></i>
                Options disponibles pour le client <span class="text-gray-500 text-sm ml-2">(facultatif)</span>
            </h2>
            <p class="text-sm text-gray-500 mb-4">
                Cochez les valeurs que le client pourra choisir pendant l'achat.
            </p>
            <div id="attributes-container" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
        </div>

        <!-- SECTION : Prix & Stock -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-coins text-green-500"></i>
                Prix & Stock
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Prix avant promotion (FCFA)</label>
                    <input type="number" id="price-input" name="price" step="0.01" required value="{{ old('price') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Reduction (%)</label>
                    <input type="number" id="discount-percent-input" name="discount_percent" step="0.01" min="0" max="99.99" value="{{ old('discount_percent') }}"
                           placeholder="Ex: 10 ou 20"
                           class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Prix actuel (FCFA)</label>
                    <input type="number" id="discount-price-input" name="discount_price" step="0.01" min="0" value="{{ old('discount_price') }}"
                           placeholder="Prix apres reduction"
                           class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">Vous pouvez remplir soit le pourcentage, soit le prix actuel.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Stock disponible</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
        </div>

        <!-- SECTION : Images -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-image text-pink-500"></i>
                Images du produit
            </h2>

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
                <input type="file" name="images[]" multiple accept="image/*,.heic,.heif,.avif"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:bg-purple-50 file:text-purple-700
                              hover:file:bg-purple-100">
                <p class="text-xs text-gray-400 mt-2">
                    Formats acceptes : JPG, PNG, GIF, WEBP, HEIC, AVIF - max 5 Mo
                </p>
            </div>
        </div>

        <!-- SECTION : Description -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-align-left text-gray-500"></i>
                Description
            </h2>

            <textarea name="description" rows="4"
                      class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                      placeholder="Description detaillee du produit...">{{ old('description') }}</textarea>
        </div>

        <!-- SECTION : Variations (Optionnelles) -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-layer-group text-indigo-500"></i>
                Options de variations (facultatif)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="option-checkbox" data-option="couleur" name="has_color" value="1" @checked(old('has_color'))>
                    <span class="ml-2 text-gray-700">Couleur</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="option-checkbox" data-option="taille" name="has_size" value="1" @checked(old('has_size'))>
                    <span class="ml-2 text-gray-700">Taille</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="option-checkbox" data-option="pointure" name="has_shoe_size" value="1" @checked(old('has_shoe_size'))>
                    <span class="ml-2 text-gray-700">Pointure</span>
                </label>
            </div>

            <div id="variations-options-wrapper" class="space-y-4"></div>

        </div>

        <!-- ACTIONS -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.products.index') }}"
               class="cancel-link px-6 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Annuler
            </a>

            <button type="submit"
                    class="px-6 py-2 rounded-lg text-white font-semibold hover:opacity-90 transition shadow"
                    style="background: var(--action-primary)">
                <i class="fas fa-save mr-2"></i>
                Creer le produit
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== ATTRIBUTS DE CATEGORIE ==========
    const categorySelect = document.getElementById('category_select');
    const attributesSection = document.getElementById('category-attributes-section');
    const attributesContainer = document.getElementById('attributes-container');
    const oldProductAttributes = @json(old('product_attributes', []));
    const priceInput = document.getElementById('price-input');
    const discountPercentInput = document.getElementById('discount-percent-input');
    const discountPriceInput = document.getElementById('discount-price-input');
    const escapeHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');

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

    // Charger les attributs quand la categorie change
    categorySelect.addEventListener('change', async function() {
        if (!this.value) {
            attributesSection.classList.add('hidden');
            attributesContainer.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`/admin/categories/${this.value}/attributes-api`);
            const data = await response.json();

            if (!data.attributes || data.attributes.length === 0) {
                attributesSection.classList.add('hidden');
                attributesContainer.innerHTML = '';
                return;
            }

            // Afficher la section
            attributesSection.classList.remove('hidden');
            attributesContainer.innerHTML = '';

            // Creer les champs pour chaque attribut
            data.attributes.forEach(attr => {
                const isRequired = attr.is_required;
                const fieldId = `attr_${attr.id}`;
                const oldRawValue = oldProductAttributes[attr.id] ?? oldProductAttributes[String(attr.id)] ?? [];
                const oldValues = Array.isArray(oldRawValue)
                    ? oldRawValue.map(value => String(value))
                    : (oldRawValue !== null && oldRawValue !== undefined && oldRawValue !== '' ? [String(oldRawValue)] : []);
                const oldValue = oldValues[0] ?? '';
                
                let fieldHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            ${attr.name}
                            ${isRequired ? '<span class="text-red-600">*</span>' : ''}
                        </label>
                `;

                if (attr.type === 'select') {
                    fieldHTML += `<div class="space-y-2">`;

                    if (Array.isArray(attr.values) && attr.values.length > 0) {
                        attr.values.forEach((val, index) => {
                            const optionId = `${fieldId}_${index}`;
                            const isChecked = oldValues.includes(String(val.value)) ? 'checked' : '';

                            fieldHTML += `
                                <label for="${optionId}" class="option-chip flex items-center gap-2 rounded-lg border border-gray-200 p-2 hover:bg-gray-50">
                                    <input type="checkbox"
                                           name="product_attributes[${attr.id}][]"
                                           id="${optionId}"
                                           value="${escapeHtml(val.value)}"
                                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                           ${isChecked}>
                                    <span class="text-sm text-gray-700">${escapeHtml(val.display_value)}</span>
                                </label>
                            `;
                        });
                    } else {
                        fieldHTML += `<p class="text-xs text-gray-500">Aucune valeur disponible pour cet attribut.</p>`;
                    }

                    fieldHTML += `</div>`;
                } else if (attr.type === 'text') {
                    fieldHTML += `
                        <input type="text" name="product_attributes[${attr.id}]" id="${fieldId}"
                               class="w-full rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Entrez ${attr.name.toLowerCase()}"
                               value="${escapeHtml(oldValue)}">
                    `;
                } else if (attr.type === 'color') {
                    const colorValue = oldValue || '#000000';
                    fieldHTML += `
                        <div class="flex gap-2">
                            <input type="color" name="product_attributes[${attr.id}]" id="${fieldId}"
                                   class="w-16 h-10 rounded-lg border-gray-300 cursor-pointer"
                                   value="${escapeHtml(colorValue)}">
                            <input type="text" placeholder="#000000" 
                                   class="flex-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                   readonly value="${escapeHtml(colorValue)}">
                        </div>
                    `;
                }

                fieldHTML += `</div>`;
                attributesContainer.insertAdjacentHTML('beforeend', fieldHTML);
            });
        } catch (error) {
            console.error('Erreur lors du chargement des attributs:', error);
        }
    });

    // Charger les attributs si une categorie est deja selectionnee
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }

    // ========== VARIATIONS (EXISTANT) ==========
    const wrapper = document.getElementById('variations-options-wrapper');
    const checkboxes = document.querySelectorAll('.option-checkbox');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const option = cb.dataset.option;
            let section = document.getElementById('section-' + option);
            
            if (cb.checked) {
                if (!section) {
                    const div = document.createElement('div');
                    div.id = 'section-' + option;
                    div.className = 'space-y-2';
                    div.innerHTML = `
                        <label class="block text-sm font-medium text-gray-600">Valeurs pour ${option}</label>
                        <div class="flex gap-2 flex-wrap" id="values-${option}">
                            <input type="text" name="variations_values[${option}][]" placeholder="${option}" class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2">
                        </div>
                        <button type="button" data-option="${option}" class="add-value px-2 py-1 text-white rounded-lg text-sm hover:opacity-90 transition" style="background: var(--action-success)">
                            Ajouter une valeur
                        </button>
                    `;
                    wrapper.appendChild(div);

                    div.querySelector('.add-value').addEventListener('click', function() {
                        const opt = this.dataset.option;
                        const container = document.getElementById('values-' + opt);
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = `variations_values[${opt}][]`;
                        input.placeholder = opt;
                        input.className = 'rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2';
                        container.appendChild(input);
                    });
                }
            } else {
                if (section) section.remove();
            }
        });
    });
});
</script>
@endpush


