@extends('layouts.admin')

@section('title', 'Modifier Utilisateur - Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-user-edit text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Modifier utilisateur</h1>
            <p class="text-white/90">Mettez a jour les informations de {{ $user->name }}</p>
        </div>
    </div>
</div>

<div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
        @if($isLastAdmin)
            <div class="mb-5 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-sm">
                Cet utilisateur est le dernier administrateur actif.
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <select
                    name="role"
                    required
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="customer" @selected(old('role', $user->role) === 'customer')>Client</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrateur</option>
                </select>
                @error('role')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nouveau mot de passe (optionnel)</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            class="js-password-field w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 pr-12">
                        <button
                            type="button"
                            data-password-toggle
                            aria-label="Afficher le mot de passe"
                            class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg data-eye-open class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7"></path>
                            </svg>
                            <svg data-eye-closed class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029M9.878 9.878a3 3 0 114.243 4.243"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.1 6.1L3 3m18 18l-3.101-3.101M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411M6.1 6.1l11.8 11.8"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirmer nouveau mot de passe</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password_confirmation"
                            class="js-password-field w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 pr-12">
                        <button
                            type="button"
                            data-password-toggle
                            aria-label="Afficher le mot de passe"
                            class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg data-eye-open class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7"></path>
                            </svg>
                            <svg data-eye-closed class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029M9.878 9.878a3 3 0 114.243 4.243"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.1 6.1L3 3m18 18l-3.101-3.101M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411M6.1 6.1l11.8 11.8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="px-5 py-3 rounded-xl text-white font-semibold shadow-lg" style="background: var(--action-primary)">
                    <i class="fas fa-save mr-1"></i>Enregistrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                    Retour
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
