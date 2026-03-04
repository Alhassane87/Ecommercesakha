@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden py-10 sm:py-14">
        <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: var(--icon-blue);"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full blur-3xl opacity-25" style="background: var(--accent-gradient);"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-3xl p-6 sm:p-8 text-white shadow-xl" style="background: var(--primary-gradient);">
                <div class="flex items-start justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">Paramètres</p>
                        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">Mon Profil</h1>
                        <p class="mt-2 text-white/85">Gérez vos informations personnelles et vos préférences</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-white/20 backdrop-blur px-4 py-2 border border-white/30">
                        <i class="fas fa-user-circle"></i>
                        <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Informations du Profil -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-primary);">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Informations Personnelles</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Mot de Passe -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-info);">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Sécurité</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Suppression du Compte -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center bg-red-600">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Zone Danger</h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
