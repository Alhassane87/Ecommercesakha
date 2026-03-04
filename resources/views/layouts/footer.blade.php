<footer class="gradient-bg text-white mt-16 relative overflow-hidden">
    @php
        $platformEmail = (string) config('platform.contact_email', 'sakha2228@gmail.com');
        $platformPhone = (string) config('platform.contact_phone', '762080009');
    @endphp
    <!-- Éléments décoratifs -->
    <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
    <div class="absolute bottom-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Brand moderne -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="gradient-border p-0.5 rounded-xl">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                            <span class="text-white font-bold text-xl">SAKHA</span>
                        </div>
                    </div>
                </div>
                <p class="text-white/90 leading-relaxed font-medium">Votre destination shopping premium pour des produits de qualité avec une expérience d'achat exceptionnelle.</p>
                
                <!-- Réseaux sociaux -->
                <div class="flex gap-3 mt-6">
                    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center hover:scale-110 transition-all duration-300">
                        <i class="fab fa-facebook-f text-white text-sm"></i>
                    </a>
                    <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center hover:scale-110 transition-all duration-300">
                        <i class="fab fa-twitter text-white text-sm"></i>
                    </a>
                    <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center hover:scale-110 transition-all duration-300">
                        <i class="fab fa-instagram text-white text-sm"></i>
                    </a>
                    <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center hover:scale-110 transition-all duration-300">
                        <i class="fab fa-linkedin-in text-white text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Liens Rapides -->
            <div>
                <h3 class="font-bold text-lg mb-6 text-white flex items-center gap-2">
                    <i class="fas fa-shopping-bag text-sm"></i>
                    Boutique
                </h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('shop.index') }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Tous les produits
                    </a></li>
                    <li><a href="{{ route('categories.index') }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Catégories
                    </a></li>
                    <li><a href="{{ route('shop.index', ['sort' => 'latest']) }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Nouveautés
                    </a></li>
                    <li><a href="{{ route('shop.index', ['deals' => 1]) }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Promotions
                    </a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="font-bold text-lg mb-6 text-white flex items-center gap-2">
                    <i class="fas fa-concierge-bell text-sm"></i>
                    Services
                </h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('track.form') }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Suivi commande
                    </a></li>
                    <li><a href="{{ route('info.shipping') }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Livraison
                    </a></li>
                    <li><a href="{{ route('info.returns') }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        Retours
                    </a></li>
                    <li><a href="{{ route('info.faq') }}" class="text-white/90 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <i class="fas fa-chevron-right text-xs text-yellow-400"></i>
                        FAQ
                    </a></li>
                </ul>
            </div>

            <!-- Contact moderne -->
            <div>
                <h3 class="font-bold text-lg mb-6 text-white flex items-center gap-2">
                    <i class="fas fa-headset text-sm"></i>
                    Contact
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm font-medium">Téléphone</p>
                            <p class="text-white font-semibold">{{ $platformPhone }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm font-medium">Email</p>
                            <p class="text-white font-semibold">
                                <a href="mailto:{{ $platformEmail }}" class="hover:underline">{{ $platformEmail }}</a>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white/80 text-sm font-medium">Adresse</p>
                            <p class="text-white font-semibold">Dakar, Sénégal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright moderne -->
        <div class="border-t border-white/30 mt-12 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-white/90 mb-4 md:mb-0 font-medium">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Sakha') }}. Tous droits réservés.
                </p>
                <div class="flex gap-6">
                    <a href="{{ route('legal.notice') }}" class="text-white/90 hover:text-white transition-colors text-sm font-medium">Mentions légales</a>
                    <a href="{{ route('legal.privacy') }}" class="text-white/90 hover:text-white transition-colors text-sm font-medium">Politique de confidentialité</a>
                    <a href="{{ route('legal.terms') }}" class="text-white/90 hover:text-white transition-colors text-sm font-medium">CGV</a>
                </div>
            </div>
        </div>
    </div>
</footer>
