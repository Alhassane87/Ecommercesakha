@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- En-tête moderne avec animation -->
    <div class="text-center mb-12 relative">
        <div class="absolute inset-0 flex items-center justify-center opacity-10">
            <div class="w-64 h-64 gradient-bg rounded-full blur-3xl float-animation"></div>
        </div>
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 gradient-border p-0.5 rounded-2xl mb-4 float-animation">
                <div class="bg-white dark:bg-gray-800 rounded-2xl w-full h-full flex items-center justify-center">
                    <svg class="w-10 h-10 gradient-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-5xl font-bold gradient-text mb-4 animate-fade-in">
                Notre Catalogue Premium
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                Découvrez notre collection exclusive de produits soigneusement sélectionnés pour vous
            </p>
            
            <!-- Statistiques animées -->
            <div class="grid grid-cols-3 gap-8 mt-10 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-bold gradient-text counter" data-target="{{ $products->count() }}">0</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Produits</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold gradient-text">24/7</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Support</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold gradient-text">100%</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Satisfaction</div>
                </div>
            </div>
        </div>
    </div>

    <x-ad-slot placement="shop_top" :limit="1" />

    <!-- Grille des produits moderne avec cartes plus petites -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @forelse($products as $p)
            <div class="group relative hover-lift">
                @php
                    $primaryImage = $p->images->first();
                    $primaryImageUrl = $primaryImage ? Storage::url($primaryImage->path) : null;
                    $finalPrice = $p->getFinalPrice();
                    $discountPercent = $p->getDiscountPercentage();
                @endphp
                <!-- Carte moderne avec glass effect -->
                <div class="glass-effect rounded-2xl overflow-hidden border border-white/20 dark:border-gray-700/50 h-full product-touch-card"
                     data-touch-zoom
                     onpointerdown="this.classList.add('touch-zoomed')"
                     onpointerup="var el=this; window.setTimeout(function(){ el.classList.remove('touch-zoomed'); }, 220)"
                     onpointercancel="this.classList.remove('touch-zoomed')"
                     ontouchstart="this.classList.add('touch-zoomed')"
                     ontouchend="var el=this; window.setTimeout(function(){ el.classList.remove('touch-zoomed'); }, 220)"
                     ontouchcancel="this.classList.remove('touch-zoomed')">
                    <!-- Badge de statut flottant -->
                    <div class="absolute top-4 right-4 z-30">
                        @if($p->inStock())
                            <div class="gradient-bg text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg animate-pulse">
                                <i class="fas fa-check-circle mr-1"></i>
                                En stock
                            </div>
                        @else
                            <div class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                <i class="fas fa-times-circle mr-1"></i>
                                Rupture
                            </div>
                        @endif
                    </div>

                    <!-- Section Image -->
                    <div class="relative overflow-hidden h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                        @if($primaryImageUrl)
                            <div class="absolute inset-0 bg-cover bg-center scale-105 blur-sm opacity-40" style="background-image: url('{{ $primaryImageUrl }}');"></div>
                            <img src="{{ $primaryImageUrl }}" 
                                 alt="{{ $p->name }}" 
                                 class="relative w-full h-full object-contain p-0">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-xs">Aucune image</span>
                            </div>
                        @endif
                        
                        <!-- Overlay avec boutons d'action -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="absolute bottom-3 left-3 right-3 flex gap-2">
                                <a href="{{ route('product.show', ['slug' => $p->slug ?? $p->id]) }}" class="flex-1 bg-white/90 backdrop-blur-sm text-gray-800 px-2 py-1.5 rounded-lg text-xs font-semibold hover:bg-white transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-eye mr-1"></i>
                                    Voir
                                </a>
                                <button type="button" onclick="addToCart(event, {{ $p->id }})" class="flex-1 gradient-bg text-white px-2 py-1.5 rounded-lg text-xs font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105 add-to-cart-btn" data-product-id="{{ $p->id }}">
                                    <i class="fas fa-shopping-cart mr-1"></i>
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-4 relative">
                        <!-- Catégorie -->
                        @if($p->category)
                            <div class="flex items-center mb-2">
                                <div class="w-4 h-4 rounded-full bg-gradient-to-r from-[var(--primary-color)] to-[var(--secondary-color)] flex items-center justify-center">
                                    <i class="fas fa-tag text-white text-xs"></i>
                                </div>
                                <span class="ml-1.5 text-xs font-medium text-[var(--primary-color)] dark:text-[var(--secondary-color)]">
                                    {{ $p->category->name }}
                                </span>
                            </div>
                        @endif

                        <!-- Nom du produit -->
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1 line-clamp-2 group-hover:text-[var(--primary-color)] dark:group-hover:text-[var(--secondary-color)] transition-colors duration-300">
                            {{ $p->name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 dark:text-gray-400 text-xs mb-3 line-clamp-2 leading-relaxed">
                            {{ $p->short_description ?? \Illuminate\Support\Str::limit($p->description, 50) }}
                        </p>

                        <!-- Prix et évaluation -->
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <div class="text-lg font-bold gradient-text">
                                    {{ number_format($finalPrice ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                                @if($discountPercent > 0)
                                    <div class="flex items-center gap-2">
                                        <div class="text-xs text-gray-500 line-through">
                                            {{ number_format($p->price ?? 0, 0, ',', ' ') }} FCFA
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                                            -{{ $discountPercent }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Étoiles d'évaluation -->
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-xs {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <!-- Bouton d'action principal -->
                        <a href="{{ route('product.show', ['slug' => $p->slug ?? $p->id]) }}" 
                           class="w-full gradient-bg text-white py-2 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-1 text-sm"
                           onclick="event.stopPropagation()">
                            <i class="fas fa-arrow-right text-xs"></i>
                            <span>Découvrir</span>
                        </a>
                    </div>

                    <!-- Bordure animée au survol -->
                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-gradient-to-r group-hover:from-[var(--primary-color)] group-hover:to-[var(--secondary-color)] transition-all duration-300 pointer-events-none"></div>
                </div>
            </div>
        @empty
            <!-- État vide moderne -->
            <div class="col-span-full text-center py-20">
                <div class="max-w-md mx-auto">
                    <div class="w-32 h-32 mx-auto mb-6 relative">
                        <div class="absolute inset-0 gradient-bg rounded-full blur-2xl opacity-30"></div>
                        <div class="relative w-full h-full bg-white dark:bg-gray-800 rounded-full flex items-center justify-center border-2 border-gray-200 dark:border-gray-600">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Catalogue en préparation</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-8">
                        Nous préparons de nouveaux produits exceptionnels pour vous. Revenez bientôt !
                    </p>
                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('home') }}" class="gradient-bg text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-home"></i>
                            <span>Retour à l'accueil</span>
                        </a>
                        <a href="{{ route('contact') }}" class="glass-effect px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-envelope"></i>
                            <span>Nous contacter</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination moderne -->
    @if($products->count())
        <div class="mt-12 flex justify-center">
            <div class="glass-effect rounded-2xl shadow-xl p-4 border border-white/20 dark:border-gray-700/50">
                {{ $products->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif

    <x-ad-slot placement="shop_bottom" :limit="1" />
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

/* Animation du compteur */
.counter {
    transition: all 2s ease-out;
}

/* Effet de bordure dégradé */
.border-gradient-to-r {
    border-image: linear-gradient(to right, var(--primary-color), var(--secondary-color)) 1;
}
</style>

<script>
// Fonction pour ajouter un produit au panier
function addToCart(event, productId) {
    event.preventDefault();
    event.stopPropagation();
    
    const btn = event.target.closest('.add-to-cart-btn');
    const originalContent = btn.innerHTML;
    
    // Afficher l'état de chargement
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Ajout...';

    // Envoyer la requête AJAX
    fetch('{{ route("cart.index") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            qty: 1,
            variation_id: null,
            attributes: null
        })
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            // Essayer de parser le JSON d'erreur
            return response.json().catch(() => {
                throw new Error('Erreur lors de l\'ajout au panier');
            }).then(data => {
                throw new Error(data.error || data.message || 'Erreur lors de l\'ajout au panier');
            });
        }
    })
    .then(data => {
        // Afficher le message de succès
        btn.innerHTML = '<i class="fas fa-check mr-1"></i>Ajouté !';
        btn.style.backgroundColor = '#10b981';
        
        // Mettre à jour le compteur du panier
        updateCartCount(data.cart_count);
        
        // Afficher une notification
        showNotification(data.message || 'Produit ajouté au panier avec succès !', 'success');
        
        // Réinitialiser après 2 secondes
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.style.backgroundColor = '';
        }, 2000);
    })
    .catch(error => {
        console.error('Erreur:', error);
        btn.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Erreur';
        btn.style.backgroundColor = '#ef4444';
        
        showNotification(error.message || 'Erreur lors de l\'ajout au panier', 'error');
        
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.style.backgroundColor = '';
        }, 2000);
    });
}

// Fonction pour mettre à jour le compteur du panier
function updateCartCount(count) {
    const cartIcon = document.querySelector('.fa-shopping-cart')?.closest('.relative');
    if (!cartIcon) return;
    
    let badge = document.getElementById('cart-count-badge');
    
    if (!badge && count > 0) {
        // Créer le badge s'il n'existe pas
        badge = document.createElement('span');
        badge.id = 'cart-count-badge';
        badge.className = 'absolute -top-2 -right-2 gradient-bg text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold animate-pulse';
        cartIcon.appendChild(badge);
    }
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Fonction pour afficher une notification
function showNotification(message, type = 'info') {
    const notificationId = 'notification-' + Date.now();
    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    const notification = document.createElement('div');
    notification.id = notificationId;
    notification.className = `fixed top-4 right-4 p-4 rounded-lg border ${colors[type] || colors.info} shadow-lg animate-fade-in z-50 max-w-md`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-times-circle' : 'fa-info-circle')}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer après 5 secondes
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Animation du compteur
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                setTimeout(updateCounter, 20);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });

    // Animation d'apparition progressive des cartes
    const cards = document.querySelectorAll('.hover-lift');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(30px)';
                    entry.target.style.transition = 'all 0.6s ease-out';
                    
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    });

    cards.forEach(card => observer.observe(card));
});
</script>
@endsection
