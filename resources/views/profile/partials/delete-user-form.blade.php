<section class="space-y-6">
    <header class="mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
            {{ __('Supprimer le Compte') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Une fois votre compte supprimé, toutes les données seront perdues définitivement.') }}
        </p>
    </header>

    <!-- Warning Alert -->
    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
        <div class="flex gap-3">
            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg mt-1"></i>
            <div>
                <p class="font-semibold text-red-800 dark:text-red-200">{{ __('Attention !') }}</p>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                    {{ __('Cette action est irréversible. Téléchargez vos données avant de continuer.') }}
                </p>
            </div>
        </div>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2"
    >
        <i class="fas fa-trash-alt"></i>
        {{ __('Supprimer définitivement mon compte') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
            @csrf
            @method('delete')

            <div class="space-y-2">
                <div class="flex items-start gap-3">
                    <i class="fas fa-warning text-red-600 dark:text-red-400 text-xl mt-1"></i>
                    <div>
                        <h2 class="text-lg font-bold text-red-700 dark:text-red-300">
                            {{ __('Êtes-vous sûr ?') }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Cette action supprimera définitivement votre compte et toutes ses données. Confirmez en entrant votre mot de passe.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <x-input-label for="password" :value="__('Mot de Passe')" class="font-semibold text-gray-700 dark:text-gray-200" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="{{ __('Entrez votre mot de passe') }}"
                    autofocus
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    {{ __('Annuler') }}
                </button>

                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i>
                    {{ __('Oui, supprimer définitivement') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
