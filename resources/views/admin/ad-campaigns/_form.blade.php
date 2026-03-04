@php
    $campaign = $campaign ?? null;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Titre</label>
        <input type="text" name="title" required
               value="{{ old('title', $campaign->title ?? '') }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Position</label>
        <select name="placement" required class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
            @foreach($placementOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('placement', $campaign->placement ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Audience</label>
        <select name="audience" required class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
            @foreach($audienceOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('audience', $campaign->audience ?? 'all') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Lien cible</label>
        <input type="text" name="target_url" placeholder="https://... ou /shop"
               value="{{ old('target_url', $campaign->target_url ?? '') }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Texte du bouton</label>
        <input type="text" name="button_text" placeholder="Voir l'offre"
               value="{{ old('button_text', $campaign->button_text ?? '') }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Priorite (plus petit = plus visible)</label>
        <input type="number" name="priority" min="0" max="9999"
               value="{{ old('priority', $campaign->priority ?? 100) }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Couleur de fond</label>
        <input type="color" name="background_color" value="{{ old('background_color', $campaign->background_color ?? '#0f172a') }}"
               class="h-12 w-full rounded-xl border border-gray-300 dark:border-gray-600 px-2 py-1 dark:bg-gray-800">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Couleur du texte</label>
        <input type="color" name="text_color" value="{{ old('text_color', $campaign->text_color ?? '#ffffff') }}"
               class="h-12 w-full rounded-xl border border-gray-300 dark:border-gray-600 px-2 py-1 dark:bg-gray-800">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Debut diffusion</label>
        <input type="datetime-local" name="starts_at"
               value="{{ old('starts_at', isset($campaign?->starts_at) ? $campaign->starts_at->format('Y-m-d\TH:i') : '') }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Fin diffusion</label>
        <input type="datetime-local" name="ends_at"
               value="{{ old('ends_at', isset($campaign?->ends_at) ? $campaign->ends_at->format('Y-m-d\TH:i') : '') }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Frequence max par session</label>
        <input type="number" name="max_impressions_per_session" min="1" max="100"
               value="{{ old('max_impressions_per_session', $campaign->max_impressions_per_session ?? '') }}"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Image publicitaire</label>
        <input type="file" name="image" accept="image/*"
               class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">
        @if(!empty($campaign?->image_path))
            <div class="mt-3">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($campaign->image_path) }}" alt="{{ $campaign->title }}" class="h-20 rounded-lg border border-gray-200 dark:border-gray-700">
            </div>
        @endif
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Description</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl border border-gray-300 dark:border-gray-600 px-4 py-3 dark:bg-gray-800 dark:text-white">{{ old('description', $campaign->description ?? '') }}</textarea>
    </div>

    <div class="lg:col-span-2 flex flex-wrap gap-6">
        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $campaign->is_active ?? true))
                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            Campagne active
        </label>

        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
            <input type="checkbox" name="open_in_new_tab" value="1" @checked(old('open_in_new_tab', $campaign->open_in_new_tab ?? false))
                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            Ouvrir le lien dans un nouvel onglet
        </label>
    </div>
</div>

@if($errors->any())
    <div class="mt-6 rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-700 p-4">
        <ul class="text-sm text-red-700 dark:text-red-200 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
