<section>
    <header class="mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
            {{ __('Changer le Mot de Passe') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Utilisez un mot de passe long et aléatoire pour sécuriser votre compte.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de Passe Actuel')" class="font-semibold text-gray-700 dark:text-gray-200" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau Mot de Passe')" class="font-semibold text-gray-700 dark:text-gray-200" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-2 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Minimum 8 caractères avec majuscules, minuscules et chiffres
            </p>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le Mot de Passe')" class="font-semibold text-gray-700 dark:text-gray-200" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" class="px-6 py-3 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2" style="background: var(--button-success);">
                <i class="fas fa-lock"></i>
                {{ __('Mettre à jour le mot de passe') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400 font-semibold flex items-center gap-2"
                >
                    <i class="fas fa-check-circle"></i>
                    {{ __('Mot de passe mis à jour avec succès.') }}
                </p>
            @endif
        </div>
    </form>
</section>
