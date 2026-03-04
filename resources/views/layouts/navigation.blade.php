<nav class="glass-effect shadow-xl border-b border-white/20 dark:border-gray-700/50 fixed top-0 inset-x-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo moderne -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="group rounded-2xl border border-slate-200/80 bg-white/85 px-3 py-2 shadow-sm backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md dark:border-gray-600 dark:bg-gray-800/85">
                    <x-brand-logo size="sm" />
                </a>
            </div>

            <!-- Barre de Recherche moderne (Desktop) -->
            <div class="hidden md:flex items-center flex-1 max-w-lg mx-8">
                <form action="{{ route('shop.index') }}" method="GET" class="w-full">
                    <div class="relative group">
                        <input type="text" 
                               name="search" 
                               placeholder="Rechercher des produits..." 
                               class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)] focus:border-transparent dark:bg-gray-700/50 dark:text-white transition-all duration-300 glass-effect">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 group-focus-within:text-[var(--primary-color)] transition-colors duration-300"></i>
                        </div>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-mono text-gray-500 bg-gray-100 dark:bg-gray-600 rounded">
                                Ctrl+K
                            </kbd>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Desktop Menu moderne -->
            <div class="hidden md:flex items-center gap-2">
                <div class="hidden lg:flex items-center mr-2">
                    <div class="theme-toggle-group" role="group" aria-label="Choix du theme">
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

                <a href="{{ route('home') }}" class="nav-link-modern group relative px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-home text-gray-600 dark:text-gray-300 group-hover:text-[var(--primary-color)] transition-colors duration-300"></i>
                        <span class="text-gray-700 dark:text-gray-200 group-hover:text-[var(--primary-color)] transition-colors duration-300">Accueil</span>
                    </div>
                    <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                </a>
                
                <a href="{{ route('shop.index') }}" class="nav-link-modern group relative px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-store text-gray-600 dark:text-gray-300 group-hover:text-[var(--primary-color)] transition-colors duration-300"></i>
                        <span class="text-gray-700 dark:text-gray-200 group-hover:text-[var(--primary-color)] transition-colors duration-300">Boutique</span>
                    </div>
                    <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="nav-link-modern group relative px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-tags text-gray-600 dark:text-gray-300 group-hover:text-[var(--primary-color)] transition-colors duration-300"></i>
                        <span class="text-gray-700 dark:text-gray-200 group-hover:text-[var(--primary-color)] transition-colors duration-300">Categories</span>
                    </div>
                    <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                </a>
                
                <a href="{{ route('track.form') }}" class="nav-link-modern group relative px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shipping-fast text-gray-600 dark:text-gray-300 group-hover:text-[var(--primary-color)] transition-colors duration-300"></i>
                        <span class="text-gray-700 dark:text-gray-200 group-hover:text-[var(--primary-color)] transition-colors duration-300">Suivi</span>
                    </div>
                    <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                </a>
                
                @auth
                    <!-- Panier moderne -->
                    <a href="{{ route('cart.index') }}" class="relative group px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <i class="fas fa-shopping-cart text-gray-600 dark:text-gray-300 group-hover:text-[var(--primary-color)] transition-colors duration-300"></i>
                                @php
                                    $cartCount = 0;
                                    try {
                                        if (auth()->check() && auth()->user()->cart) {
                                            $cartCount = auth()->user()->cart->items->count();
                                        }
                                    } catch (Exception $e) {
                                        $cartCount = 0;
                                    }
                                @endphp
                                @if($cartCount > 0)
                                    <span id="cart-count-badge" class="absolute -top-2 -right-2 gradient-bg text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold animate-pulse">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </div>
                            <span class="hidden lg:inline text-gray-700 dark:text-gray-200 group-hover:text-[var(--primary-color)] transition-colors duration-300">Panier</span>
                        </div>
                        <div class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] group-hover:w-full group-hover:left-0 transition-all duration-300"></div>
                    </a>

                    <!-- Menu Utilisateur moderne -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                            <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:text-[var(--primary-color)] transition-colors duration-300"></i>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-56 glass-effect rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50">
                            <div class="p-2">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--dashboard-gradient)">
                                        <i class="fas fa-tachometer-alt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Dashboard</div>
                                        <div class="text-xs text-gray-500">Gerer votre compte</div>
                                    </div>
                                </a>
                                
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--admin-gradient)">
                                            <i class="fas fa-cog text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium">Administration</div>
                                            <div class="text-xs text-gray-500">Panneau d'administration</div>
                                        </div>
                                    </a>
                                @endif
                                
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--profile-gradient)">
                                        <i class="fas fa-user-edit text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Profil</div>
                                        <div class="text-xs text-gray-500">Modifier vos informations</div>
                                    </div>
                                </a>
                                
                                <hr class="my-2 border-gray-200 dark:border-gray-600">
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--logout-gradient)">
                                            <i class="fas fa-sign-out-alt text-white text-sm"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-medium">Deconnexion</div>
                                            <div class="text-xs text-red-400">Quitter votre session</div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sign-in-alt text-gray-600 dark:text-gray-300"></i>
                            <span class="text-gray-700 dark:text-gray-200">Connexion</span>
                        </div>
                    </a>
                    <a href="{{ route('register') }}" class="gradient-bg text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            <span>Inscription</span>
                        </div>
                    </a>
                @endauth
            </div>

            <!-- Mobile buttons modernes -->
            <div class="md:hidden flex items-center gap-3">
                <!-- Recherche Mobile -->
                <button id="mobileSearchBtn" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <i class="fas fa-search text-gray-600 dark:text-gray-300"></i>
                </button>
                
                <!-- Panier Mobile -->
                @auth
                    <a href="{{ route('cart.index') }}" class="relative p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                        <i class="fas fa-shopping-cart text-gray-600 dark:text-gray-300"></i>
                        @php
                            $cartCountMobile = 0;
                            if (auth()->user()->cart && auth()->user()->cart->items) {
                                $cartCountMobile = auth()->user()->cart->items->count();
                            }
                        @endphp
                        @if($cartCountMobile > 0)
                            <span class="absolute -top-1 -right-1 gradient-bg text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-bold">
                                {{ $cartCountMobile }}
                            </span>
                        @endif
                    </a>
                @endauth

                <!-- Menu Mobile -->
                <button id="mobileMenuBtn" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                </button>
            </div>
        </div>

        <!-- Barre de Recherche Mobile moderne -->
        <div id="mobileSearch" class="md:hidden hidden px-4 pb-4">
            <form action="{{ route('shop.index') }}" method="GET">
                <div class="relative group">
                    <input type="text" 
                           name="search" 
                           placeholder="Rechercher des produits..." 
                           class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-transparent dark:bg-gray-700/50 dark:text-white transition-all duration-300 glass-effect">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 group-focus-within:text-[var(--primary-color)] transition-colors duration-300"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Menu moderne -->
    <div id="mobileMenu" class="md:hidden hidden glass-effect border-t border-white/20 dark:border-gray-700/50">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('home') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--home-gradient)">
                    <i class="fas fa-home text-white text-sm"></i>
                </div>
                <span class="text-gray-700 dark:text-gray-200 font-medium">Accueil</span>
            </a>
            
            <a href="{{ route('shop.index') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--shop-gradient)">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                <span class="text-gray-700 dark:text-gray-200 font-medium">Boutique</span>
            </a>
            
            <a href="{{ route('categories.index') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--categories-gradient)">
                    <i class="fas fa-tags text-white text-sm"></i>
                </div>
                <span class="text-gray-700 dark:text-gray-200 font-medium">Categories</span>
            </a>
            
            <a href="{{ route('track.form') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--tracking-gradient)">
                    <i class="fas fa-shipping-fast text-white text-sm"></i>
                </div>
                <span class="text-gray-700 dark:text-gray-200 font-medium">Suivi Commande</span>
            </a>

            <div class="px-4 py-2">
                <p class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                    Theme
                </p>
                <div class="theme-toggle-group" role="group" aria-label="Choix du theme mobile">
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
            
            @auth
                <hr class="my-2 border-gray-200 dark:border-gray-600">
                
                <a href="{{ route('dashboard') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--dashboard-gradient)">
                        <i class="fas fa-tachometer-alt text-white text-sm"></i>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Dashboard</span>
                </a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--admin-gradient)">
                            <i class="fas fa-cog text-white text-sm"></i>
                        </div>
                        <span class="text-gray-700 dark:text-gray-200 font-medium">Administration</span>
                    </a>
                @endif
                
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--action-success)">
                        <i class="fas fa-user-edit text-white text-sm"></i>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Profil</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="mobile-nav-link flex items-center gap-3 w-full px-4 py-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--logout-gradient)">
                            <i class="fas fa-sign-out-alt text-white text-sm"></i>
                        </div>
                        <span class="text-red-600 font-medium">Deconnexion</span>
                    </button>
                </form>
            @else
                <hr class="my-2 border-gray-200 dark:border-gray-600">
                
                <a href="{{ route('login') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--icon-gradient)">
                        <i class="fas fa-sign-in-alt text-white text-sm"></i>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Connexion</span>
                </a>
                
                <a href="{{ route('register') }}" class="mobile-nav-link flex items-center gap-3 px-4 py-3 rounded-xl gradient-bg text-white">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-sm"></i>
                    </div>
                    <span class="font-medium">Inscription</span>
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menu mobile
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Recherche mobile
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearch = document.getElementById('mobileSearch');
    
    if (mobileSearchBtn && mobileSearch) {
        mobileSearchBtn.addEventListener('click', () => {
            mobileSearch.classList.toggle('hidden');
        });
    }
});
</script>


