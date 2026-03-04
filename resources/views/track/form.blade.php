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
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">Suivi de commande</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">Suivre ma commande</h1>
                    <p class="mt-2 text-white/85">Entrez votre numéro de commande pour connaître l'état d'avancement en temps réel.</p>
                </div>
                <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-white/20 backdrop-blur px-4 py-2 border border-white/30">
                    <i class="fas fa-truck-fast"></i>
                    <span class="text-sm font-semibold">Suivi en Direct</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Search Form -->
            <div class="lg:col-span-2">
                <!-- Error Message -->
                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-5 py-4">
                        <p class="font-semibold text-red-700 dark:text-red-300 flex items-center gap-2">
                            <i class="fas fa-circle-exclamation"></i>
                            Commande introuvable
                        </p>
                        <p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 sm:p-8 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-primary);">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Rechercher votre commande</h2>
                    </div>

                    <form action="{{ route('track.lookup') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label for="tracking_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                Numéro de commande *
                            </label>
                            <input 
                                id="tracking_number"
                                type="text"
                                name="tracking_number" 
                                class="w-full px-4 py-3 text-base border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                                required 
                                placeholder="Ex : CMD202512070001"
                                autofocus
                            >
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                Vous trouverez ce numéro dans votre email de confirmation
                            </p>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full py-3 px-6 text-white font-bold rounded-xl transition hover:opacity-90 shadow-lg flex items-center justify-center gap-2"
                            style="background: var(--button-success);"
                        >
                            <i class="fas fa-search"></i>
                            <span>Rechercher ma commande</span>
                        </button>
                    </form>
                </div>

                <!-- Information Cards -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg text-white flex items-center justify-center flex-shrink-0" style="background: var(--action-primary);">
                                <i class="fas fa-truck-fast"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100">Suivi en temps réel</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Suivez chaque étape du traitement et de la livraison</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg text-white flex items-center justify-center flex-shrink-0" style="background: var(--action-success);">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100">Détails complets</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Voir tous les détails de votre commande et articles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <aside class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-info);">
                            <i class="fas fa-circle-question"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Aide rapide</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4">
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">
                                <i class="fas fa-lightbulb mr-2"></i>Où trouver mon numéro ?
                            </p>
                            <p class="text-xs text-blue-800 dark:text-blue-300">Regardez votre email de confirmation. Il commence par "CMD" suivi de chiffres.</p>
                        </div>

                        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-4">
                            <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-200 mb-2">
                                <i class="fas fa-hourglass-half mr-2"></i>Délai de livraison
                            </p>
                            <p class="text-xs text-emerald-800 dark:text-emerald-300">3 à 7 jours ouvrables selon votre localisation.</p>
                        </div>

                        <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4">
                            <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-2">
                                <i class="fas fa-headset mr-2"></i>Support
                            </p>
                            <p class="text-xs text-amber-800 dark:text-amber-300">Contactez-nous si vous n'avez pas reçu votre commande.</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection
