<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            (function () {
                try {
                    var theme = localStorage.getItem('sakha-theme') || 'light';
                    if (theme !== 'light' && theme !== 'dark') {
                        theme = 'light';
                    }
                    var root = document.documentElement;
                    root.classList.remove('dark');
                    if (theme === 'dark') {
                        root.classList.add('dark');
                    }
                    root.setAttribute('data-theme', theme);
                } catch (e) {}
            })();
        </script>

        <title>{{ config('app.name', 'Sakha') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Variables CSS - Palette de couleurs uniforme */
            :root {
                /* Degrades principaux */
                --primary-gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 50%, #45B7D1 100%);
                --secondary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                --dark-gradient: linear-gradient(135deg, #1a1c20 0%, #2d3748 100%);
                
                /* Degrades specialises - Navigation */
                --dashboard-gradient: linear-gradient(135deg, #45B7D1 0%, #667eea 100%);
                --admin-gradient: linear-gradient(135deg, #FF6B6B 0%, #f5576c 100%);
                --profile-gradient: linear-gradient(135deg, #4ECDC4 0%, #45B7D1 100%);
                --logout-gradient: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
                --home-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --shop-gradient: linear-gradient(135deg, #4ECDC4 0%, #00D4FF 100%);
                --categories-gradient: linear-gradient(135deg, #FF6B6B 0%, #FFA500 100%);
                --tracking-gradient: linear-gradient(135deg, #764ba2 0%, #f5576c 100%);
                
                /* Degrades specialises - Actions Admin */
                --action-primary: linear-gradient(135deg, #4C51BF 0%, #5B42B6 100%);
                --action-success: linear-gradient(135deg, #059669 0%, #047857 100%);
                --action-info: linear-gradient(135deg, #0369A1 0%, #0284C7 100%);
                
                /* Degrades specialises - Icones */
                --icon-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --icon-blue: linear-gradient(135deg, #45B7D1 0%, #667eea 100%);
                --icon-green: linear-gradient(135deg, #4ECDC4 0%, #00D4FF 100%);
                --icon-red: linear-gradient(135deg, #FF6B6B 0%, #f5576c 100%);
                
                /* Degrades d'arriere-plan */
                --bg-gradient-light: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
                --bg-gradient-blue-light: linear-gradient(to right, rgba(69, 183, 209, 0.1), rgba(102, 126, 234, 0.1));
                --bg-gradient-green-light: linear-gradient(to right, rgba(78, 205, 196, 0.1), rgba(0, 212, 255, 0.1));
                
                /* Degrades de boutons */
                --button-primary: linear-gradient(to right, #2563EB 0%, #4338CA 100%);
                --button-success: linear-gradient(to right, #059669 0%, #047857 100%);
                --button-action: linear-gradient(to right, #2563EB 0%, #5B21B6 100%);
                
                /* Degrades d'entetes */
                --header-page: linear-gradient(to right, #dbeafe 0%, #e0e7ff 100%);
                --header-section: linear-gradient(135deg, #A78BFA 0%, #667eea 100%);
                
                /* Bordures et separations */
                --border-gradient: linear-gradient(90deg, #667eea, #764ba2);
            }
            
            .gradient-bg { 
                background: var(--primary-gradient);
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white dark:bg-gray-900 shadow-xl h-screen sticky top-0">

                <div class="p-6 border-b">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">S</span>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
                            <div class="text-xs text-gray-500 dark:text-gray-400">administrateur</div>
                        </div>
                    </a>
                </div>

                <nav class="p-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-purple-900/30 dark:hover:to-blue-900/30 hover:text-purple-700 dark:hover:text-white transition">

                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-purple-900/30 dark:hover:to-blue-900/30 hover:text-purple-700 dark:hover:text-white transition">

                        <i class="fas fa-box w-5"></i>
                        <span>Produits</span>
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-purple-900/30 dark:hover:to-blue-900/30 hover:text-purple-700 dark:hover:text-white transition">

                        <i class="fas fa-tags w-5"></i>
                        <span>Categories</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-purple-900/30 dark:hover:to-blue-900/30 hover:text-purple-700 dark:hover:text-white transition">

                        <i class="fas fa-shopping-bag w-5"></i>
                        <span>Commandes</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/30 hover:text-purple-600 dark:hover:text-white transition">

                        <i class="fas fa-users w-5"></i>
                        <span>Utilisateurs</span>
                    </a>

                    <a href="{{ route('admin.ad-campaigns.index') }}"
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-purple-900/30 dark:hover:to-blue-900/30 hover:text-purple-700 dark:hover:text-white transition">

                        <i class="fas fa-bullhorn w-5"></i>
                        <span>Publicites</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Top Header -->
                <header class="shadow-md border-b rounded-t-lg" style="background: var(--admin-gradient)">
                    <div class="flex justify-between items-center px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-800/30 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-bold text-white">S</span>
                            </div>
                            <h1 class="text-xl font-semibold text-white">@yield('header', 'administrateur')</h1>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="hidden sm:block">
                                <div class="theme-toggle-group" role="group" aria-label="Choix du theme administrateur">
                                    <button type="button"
                                            class="theme-toggle-btn"
                                            data-theme-toggle="light"
                                            aria-pressed="false"
                                            title="Mode clair">
                                        <i class="fas fa-sun"></i>
                                    </button>
                                    <button type="button"
                                            class="theme-toggle-btn"
                                            data-theme-toggle="dark"
                                            aria-pressed="false"
                                            title="Mode sombre">
                                        <i class="fas fa-moon"></i>
                                    </button>
                                </div>
                            </div>

                            <a href="{{ url('/') }}" 
                               class="flex items-center space-x-2 text-white/90 hover:text-white transition font-medium">

                                <i class="fas fa-external-link-alt"></i>
                                <span>Voir le site</span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center space-x-2 text-white/90 hover:text-red-200 transition font-medium">

                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Deconnexion</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6">
                    @include('components.flash-notifications')
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        &copy; {{ date('Y') }} {{ config('app.name') }} - administrateur
                    </div>
                </footer>
            </div>
        </div>

        @include('components.confirm-modal')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var fieldWrapper = button.closest('.relative');
                        var input = fieldWrapper ? fieldWrapper.querySelector('.js-password-field') : null;
                        if (!input) return;

                        var openIcon = button.querySelector('[data-eye-open]');
                        var closedIcon = button.querySelector('[data-eye-closed]');
                        var shouldShow = input.type === 'password';

                        input.type = shouldShow ? 'text' : 'password';

                        if (openIcon) openIcon.classList.toggle('hidden', shouldShow);
                        if (closedIcon) closedIcon.classList.toggle('hidden', !shouldShow);

                        button.setAttribute(
                            'aria-label',
                            shouldShow ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
                        );
                    });
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>


