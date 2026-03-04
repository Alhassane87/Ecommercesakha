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

        <title>@yield('title', config('app.name', 'Sakha'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Palette de couleurs moderne et transitaire - UNIFORMISÉE A-Z */
            :root {
                /* Dégradés principaux */
                --primary-gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 50%, #45B7D1 100%);
                --secondary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                --dark-gradient: linear-gradient(135deg, #1a1c20 0%, #2d3748 100%);
                
                /* Dégradés spécialisés - Navigation */
                --dashboard-gradient: linear-gradient(135deg, #45B7D1 0%, #667eea 100%);
                --admin-gradient: linear-gradient(135deg, #FF6B6B 0%, #f5576c 100%);
                --profile-gradient: linear-gradient(135deg, #4ECDC4 0%, #45B7D1 100%);
                --logout-gradient: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
                --home-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --shop-gradient: linear-gradient(135deg, #4ECDC4 0%, #00D4FF 100%);
                --categories-gradient: linear-gradient(135deg, #FF6B6B 0%, #FFA500 100%);
                --tracking-gradient: linear-gradient(135deg, #764ba2 0%, #f5576c 100%);
                
                /* Dégradés spécialisés - Actions Admin */
                --action-primary: linear-gradient(135deg, #4C51BF 0%, #5B42B6 100%);
                --action-success: linear-gradient(135deg, #059669 0%, #047857 100%);
                --action-info: linear-gradient(135deg, #0369A1 0%, #0284C7 100%);
                
                /* Dégradés spécialisés - Icônes */
                --icon-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --icon-blue: linear-gradient(135deg, #45B7D1 0%, #667eea 100%);
                --icon-green: linear-gradient(135deg, #4ECDC4 0%, #00D4FF 100%);
                --icon-red: linear-gradient(135deg, #FF6B6B 0%, #f5576c 100%);
                
                /* Dégradés d'arrière-plan */
                --bg-gradient-light: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(118, 75, 162, 0.08));
                --bg-gradient-blue-light: linear-gradient(to right, rgba(69, 183, 209, 0.1), rgba(102, 126, 234, 0.1));
                --bg-gradient-green-light: linear-gradient(to right, rgba(78, 205, 196, 0.1), rgba(0, 212, 255, 0.1));
                
                /* Dégradés de boutons */
                --button-primary: linear-gradient(to right, #2563EB 0%, #4338CA 100%);
                --button-success: linear-gradient(to right, #059669 0%, #047857 100%);
                --button-action: linear-gradient(to right, #2563EB 0%, #5B21B6 100%);
                
                /* Dégradés d'en-têtes */
                --header-page: linear-gradient(to right, #dbeafe 0%, #e0e7ff 100%);
                --header-section: linear-gradient(135deg, #A78BFA 0%, #667eea 100%);
                
                /* Bordures et séparations */
                --border-gradient: linear-gradient(90deg, #667eea, #764ba2);
                
                /* Couleurs de base */
                --primary-color: #FF6B6B;
                --secondary-color: #4ECDC4;
                --accent-color: #45B7D1;
                --dark-color: #1a1c20;
                --light-color: #f7fafc;
                
                /* Ombres */
                --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                --shadow-large: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            body {
                font-family: 'Figtree', sans-serif;
                background: var(--light-color);
            }

            .gradient-bg { 
                background: var(--primary-gradient);
                background-size: 200% 200%;
                animation: gradientShift 8s ease infinite;
            }

            .gradient-text {
                background: var(--primary-gradient);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .gradient-border {
                background: var(--primary-gradient);
                padding: 2px;
                border-radius: 12px;
            }

            .hover-lift {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hover-lift:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: var(--shadow-large);
            }

            .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .dark .glass-effect {
                background: rgba(26, 28, 32, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            .float-animation {
                animation: float 6s ease-in-out infinite;
            }

            [x-cloak] { display: none !important; }

            /* Scrollbar moderne */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: var(--primary-gradient);
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--secondary-gradient);
            }

            [data-touch-zoom] {
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
                transition: transform 120ms ease-out, box-shadow 120ms ease-out, filter 120ms ease-out;
                transform-origin: center center;
                will-change: transform;
            }

            [data-touch-zoom].touch-zoomed,
            [data-touch-zoom]:active {
                transform: scale(0.92) !important;
                box-shadow: 0 10px 20px -16px rgba(15, 23, 42, 0.35);
                filter: brightness(0.96);
            }

            [data-touch-zoom].product-touch-card.touch-zoomed,
            [data-touch-zoom].product-touch-card:active {
                transform: scale(0.86) !important;
                box-shadow: 0 16px 28px -22px rgba(15, 23, 42, 0.45);
                filter: brightness(0.9);
            }

            /* Print Styles */
            @media print {
                nav, 
                footer, 
                .chatbot-widget, 
                .confirm-modal,
                .flash-notifications,
                .no-print {
                    display: none !important;
                }
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 {{ request()->routeIs('home') ? 'pt-0' : 'pt-16' }}">
            @include('layouts.navigation')
            @include('components.flash-notifications')

            <!-- Page Content -->
            <main>
                <x-ad-slot placement="global_top" :limit="1" />
                @yield('content')
                <x-ad-slot placement="global_bottom" :limit="1" />
            </main>

            @include('layouts.footer')
        </div>

        <!-- Chatbot Widget -->
        @include('components.whatsapp-widget')
        @include('components.chatbot-widget')
        @include('components.confirm-modal')
        @include('components.newsletter-popup')

        <script>
            // Animations au chargement
            document.addEventListener('DOMContentLoaded', function() {
                // Animation d'apparition progressive
                const elements = document.querySelectorAll('.hover-lift, .glass-effect');
                elements.forEach((el, index) => {
                    setTimeout(() => {
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(20px)';
                        el.style.transition = 'all 0.6s ease-out';
                        
                        setTimeout(() => {
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                        }, 100);
                    }, index * 100);
                });

                const touchZoomSelector = '[data-touch-zoom]';
                let activeTouchZoomEl = null;
                let clearTouchZoomTimer = null;

                const clearTouchZoom = () => {
                    if (activeTouchZoomEl) {
                        activeTouchZoomEl.classList.remove('touch-zoomed');
                        activeTouchZoomEl = null;
                    }
                };

                const scheduleClearTouchZoom = (delay = 220) => {
                    if (clearTouchZoomTimer) {
                        clearTimeout(clearTouchZoomTimer);
                    }
                    clearTouchZoomTimer = setTimeout(() => {
                        clearTouchZoom();
                        clearTouchZoomTimer = null;
                    }, delay);
                };

                const activateTouchZoom = (target) => {
                    const card = target && target.closest ? target.closest(touchZoomSelector) : null;
                    if (!card) {
                        return;
                    }

                    if (clearTouchZoomTimer) {
                        clearTimeout(clearTouchZoomTimer);
                        clearTouchZoomTimer = null;
                    }

                    if (activeTouchZoomEl && activeTouchZoomEl !== card) {
                        activeTouchZoomEl.classList.remove('touch-zoomed');
                    }

                    activeTouchZoomEl = card;
                    activeTouchZoomEl.classList.add('touch-zoomed');
                };

                document.addEventListener('touchstart', (event) => {
                    activateTouchZoom(event.target);
                }, { passive: true, capture: true });

                document.addEventListener('touchend', () => {
                    scheduleClearTouchZoom(220);
                }, { passive: true, capture: true });

                document.addEventListener('touchcancel', () => {
                    scheduleClearTouchZoom(120);
                }, { passive: true, capture: true });

                document.addEventListener('pointerdown', (event) => {
                    activateTouchZoom(event.target);
                }, { passive: true, capture: true });

                document.addEventListener('pointerup', () => {
                    scheduleClearTouchZoom(220);
                }, { passive: true, capture: true });

                document.addEventListener('pointercancel', () => {
                    scheduleClearTouchZoom(120);
                }, { passive: true, capture: true });
            });
        </script>
        @stack('scripts')
    </body>
</html>
