<x-guest-layout>
    <div class="fixed inset-0 overflow-y-auto bg-slate-100">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 -top-20 h-80 w-80 rounded-full bg-sky-300/35 blur-3xl"></div>
            <div class="absolute right-0 top-1/3 h-72 w-72 rounded-full bg-indigo-300/30 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-emerald-300/25 blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-full w-full items-center justify-center px-4 py-6 sm:px-6 lg:px-8">
            <div class="grid w-full max-w-6xl overflow-hidden rounded-3xl border border-white/70 bg-white/90 shadow-[0_28px_90px_-35px_rgba(15,23,42,0.45)] backdrop-blur-xl lg:grid-cols-[1.05fr_1fr]">
                <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-slate-900 p-10 text-white">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500 via-indigo-500 to-emerald-500 opacity-90"></div>
                    <div class="absolute -left-16 top-6 h-44 w-44 rounded-full bg-white/25 blur-3xl"></div>
                    <div class="absolute -right-10 bottom-8 h-52 w-52 rounded-full bg-white/20 blur-3xl"></div>

                    <div class="relative">
                        <p class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white/90">
                            Nouveau compte
                        </p>
                        <h2 class="mt-6 text-4xl font-black leading-tight">Rejoignez Sakha</h2>
                        <p class="mt-4 text-sm leading-6 text-white/90">
                            Creez votre compte en quelques secondes pour commander plus vite et suivre toutes vos commandes.
                        </p>
                    </div>

                    <div class="relative space-y-4">
                        <div class="rounded-2xl border border-white/30 bg-white/15 p-4 backdrop-blur-sm">
                            <p class="text-sm font-semibold">Commande simplifiee</p>
                            <p class="mt-1 text-xs text-white/85">Historique, favoris et panier disponibles a tout moment.</p>
                        </div>
                        <div class="rounded-2xl border border-white/30 bg-white/15 p-4 backdrop-blur-sm">
                            <p class="text-sm font-semibold">Offres reservees</p>
                            <p class="mt-1 text-xs text-white/85">Acces prioritaire aux promotions et nouveautes.</p>
                        </div>
                        <div class="rounded-2xl border border-white/30 bg-white/15 p-4 backdrop-blur-sm">
                            <p class="text-sm font-semibold">Paiement fiable</p>
                            <p class="mt-1 text-xs text-white/85">Protection des donnees et verification securisee.</p>
                        </div>
                    </div>
                </aside>

                <section class="max-h-[95vh] overflow-y-auto p-6 sm:p-9 lg:p-10">
                    <div class="mx-auto w-full max-w-md">
                        <div class="mb-6">
                            <a href="{{ route('home') }}" class="inline-flex">
                                <x-brand-logo size="md" />
                            </a>
                        </div>

                        <div class="mb-8">
                            <p class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Inscription
                            </p>
                            <h1 class="mt-4 text-3xl font-black text-slate-900 sm:text-4xl">Creez votre compte</h1>
                            <p class="mt-2 text-sm text-slate-600">
                                Commencez votre experience shopping avec une interface rapide et securisee.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                Verifiez les champs ci-dessous avant de continuer.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Nom complet')" class="text-sm font-semibold text-slate-700" />
                                <x-text-input
                                    id="name"
                                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-sky-500"
                                    type="text"
                                    name="name"
                                    :value="old('name')"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Ex: Awa Ndiaye"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-slate-700" />
                                <x-text-input
                                    id="email"
                                    class="mt-2 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-sky-500"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autocomplete="username"
                                    placeholder="vous@exemple.com"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Mot de passe')" class="text-sm font-semibold text-slate-700" />
                                <div class="relative mt-2">
                                    <x-text-input
                                        id="password"
                                        class="block w-full rounded-xl border-slate-200 bg-white px-4 py-3 pr-12 text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-sky-500"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Au moins 8 caracteres"
                                    />
                                    <button
                                        type="button"
                                        data-toggle-password
                                        data-target="password"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-slate-600"
                                        aria-label="Afficher ou masquer le mot de passe"
                                    >
                                        <svg data-icon="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7"></path>
                                        </svg>
                                        <svg data-icon="hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.051 10.051 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.968 9.968 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l4.24 4.24M3 3l18 18"></path>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-sm font-semibold text-slate-700" />
                                <div class="relative mt-2">
                                    <x-text-input
                                        id="password_confirmation"
                                        class="block w-full rounded-xl border-slate-200 bg-white px-4 py-3 pr-12 text-slate-900 shadow-sm transition focus:border-sky-500 focus:ring-sky-500"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Ressaisir le mot de passe"
                                    />
                                    <button
                                        type="button"
                                        data-toggle-password
                                        data-target="password_confirmation"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-slate-600"
                                        aria-label="Afficher ou masquer la confirmation"
                                    >
                                        <svg data-icon="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7"></path>
                                        </svg>
                                        <svg data-icon="hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.051 10.051 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.968 9.968 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l4.24 4.24M3 3l18 18"></path>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <x-primary-button class="w-full justify-center rounded-xl border-0 bg-gradient-to-r from-sky-500 via-indigo-500 to-emerald-500 px-5 py-3 text-sm font-bold normal-case tracking-normal text-white shadow-lg shadow-indigo-500/25 transition hover:scale-[1.01] hover:from-sky-600 hover:to-emerald-600 focus:ring-sky-500">
                                Creer mon compte
                            </x-primary-button>
                        </form>

                        <div class="my-6 flex items-center gap-3">
                            <div class="h-px flex-1 bg-slate-200"></div>
                            <span class="text-xs font-medium uppercase tracking-wide text-slate-400">ou</span>
                            <div class="h-px flex-1 bg-slate-200"></div>
                        </div>

                        <a
                            href="{{ route('google.redirect') }}"
                            class="flex w-full items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:shadow"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.77-.07-1.52-.2-2.24H12v4.25h5.92c-.26 1.36-1.04 2.52-2.21 3.3v2.77h3.57c2.08-1.92 3.28-4.73 3.28-8.08z"></path>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path>
                                <path fill="#FBBC05" d="M5.84 14.09A7.02 7.02 0 015.5 12c0-.73.13-1.43.34-2.09V7.07H2.18A11.97 11.97 0 001 12c0 1.77.43 3.44 1.18 4.93l2.86-2.22.8-.62z"></path>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.1 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path>
                            </svg>
                            Continuer avec Google
                        </a>

                        <p class="mt-6 text-center text-sm text-slate-600">
                            Vous avez deja un compte ?
                            <a href="{{ route('login') }}" class="font-semibold text-sky-700 transition hover:text-sky-800">
                                Se connecter
                            </a>
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const showIcon = button.querySelector('[data-icon="show"]');
                const hideIcon = button.querySelector('[data-icon="hide"]');

                if (!input) {
                    return;
                }

                const showPassword = input.type === 'password';
                input.type = showPassword ? 'text' : 'password';

                if (showIcon && hideIcon) {
                    showIcon.classList.toggle('hidden', showPassword);
                    hideIcon.classList.toggle('hidden', !showPassword);
                }
            });
        });
    </script>
</x-guest-layout>
