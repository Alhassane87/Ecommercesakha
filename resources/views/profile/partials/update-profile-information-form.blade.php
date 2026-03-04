<section>
    <header class="mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
            {{ __('Informations du Profil') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Mettez à jour les informations de votre compte.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nom')" class="font-semibold text-gray-700 dark:text-gray-200" />
            <x-text-input id="name" name="name" type="text" class="mt-2 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700 dark:text-gray-200" />
            <x-text-input id="email" name="email" type="email" class="mt-2 block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                    <p class="text-sm text-amber-800 dark:text-amber-200">
                        {{ __('Votre adresse email n\'a pas été vérifiée.') }}
                        <button form="send-verification" class="font-semibold underline text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 transition">
                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2" style="background: var(--button-success);">
                <i class="fas fa-save"></i>
                {{ __('Enregistrer les modifications') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400 font-semibold flex items-center gap-2"
                >
                    <i class="fas fa-check-circle"></i>
                    {{ __('Profil mis à jour avec succès.') }}
                </p>
            @endif
        </div>
    </form>
</section>
