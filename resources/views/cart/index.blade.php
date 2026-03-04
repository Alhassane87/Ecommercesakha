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
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">Shopping Cart</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">Mon Panier</h1>
                    <p class="mt-2 text-white/85">{{ $cart && $cart->items->count() ? $cart->items->count() . ' article(s) en attente' : 'Votre panier est vide' }}</p>
                </div>
                <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-white/20 backdrop-blur px-4 py-2 border border-white/30">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="text-sm font-semibold">Panier</span>
                </div>
            </div>
        </div>

        @if($cart && $cart->items->count())
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Tableau des articles -->
                <div class="xl:col-span-2">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Produit</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Quantité</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Prix</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($cart->items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Photo du produit -->
                                                    <div class="flex-shrink-0 w-16 h-16 bg-white dark:bg-gray-600 rounded-xl border border-gray-200 dark:border-gray-500 overflow-hidden">
                                                        @if($item->product->images->count() > 0)
                                                            <img 
                                                                src="{{ Storage::url($item->product->images->first()->path) }}" 
                                                                alt="{{ $item->product->name }}"
                                                                class="w-full h-full object-cover"
                                                            >
                                                        @else
                                                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center">
                                                                <i class="fas fa-box text-gray-400 dark:text-gray-500"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                            {{ $item->product->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ number_format($item->unit_price, 2, ',', ' ') }} fcfa
                                                        </div>
                                                        @php
                                                            $displayAttributes = [];
                                                            if (is_array($item->selected_attributes)) {
                                                                foreach ($item->selected_attributes as $key => $value) {
                                                                    if (is_array($value)) {
                                                                        $value = implode(', ', array_filter(array_map('strval', $value)));
                                                                    } else {
                                                                        $value = trim((string) $value);
                                                                    }
                                                                    if ($value === '') continue;
                                                                    $displayAttributes[] = ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value;
                                                                }
                                                            }
                                                        @endphp
                                                        @if(!empty($displayAttributes))
                                                            <div class="mt-2 flex flex-wrap gap-1.5">
                                                                @foreach($displayAttributes as $label)
                                                                    <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-[11px] font-medium text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                                                        {{ $label }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="inline-flex items-center space-x-2 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" name="action" value="decrease" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-400">
                                                            <i class="fas fa-minus text-xs"></i>
                                                        </button>
                                                        <span class="w-12 text-center font-semibold text-gray-900 dark:text-gray-100">
                                                            {{ $item->qty }}
                                                        </span>
                                                        <button type="submit" name="action" value="increase" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-400">
                                                            <i class="fas fa-plus text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ number_format($item->unit_price * $item->qty, 2, ',', ' ') }} fcfa
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-all duration-200 text-sm font-medium">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Résumé de commande -->
                <aside class="xl:col-span-1">
                    <div class="xl:sticky xl:top-24 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-primary);">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Résumé</h3>
                        </div>

                        <div class="space-y-3 max-h-[380px] overflow-auto pr-1 mb-5 pb-5 border-b border-gray-200 dark:border-gray-700">
                            @foreach($cart->items as $item)
                                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <p class="font-semibold text-sm text-gray-900 dark:text-gray-100 flex-1">{{ $item->product->name }}</p>
                                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ number_format($item->unit_price * $item->qty, 2, ',', ' ') }} fcfa</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Quantité: {{ $item->qty }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-2 mb-6">
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span>Sous-total</span>
                                <span>{{ number_format($cart->items->sum(fn($i) => $i->qty * $i->unit_price), 2, ',', ' ') }} fcfa</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span>Livraison</span>
                                <span>À confirmer</span>
                            </div>
                            <div class="flex items-center justify-between text-lg font-extrabold text-gray-900 dark:text-gray-100 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span>Total</span>
                                <span>{{ number_format($cart->items->sum(fn($i) => $i->qty * $i->unit_price), 2, ',', ' ') }} fcfa</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('checkout.show') }}" 
                               class="w-full py-3 px-4 text-white font-bold rounded-xl transition hover:opacity-90 shadow-lg flex items-center justify-center gap-2"
                               style="background: var(--button-success);">
                                <i class="fas fa-check-circle"></i>
                                Passer commande
                            </a>
                            <a href="{{ route('shop.index') }}" 
                               class="w-full py-3 px-4 text-white font-bold rounded-xl transition hover:opacity-90 shadow-lg flex items-center justify-center gap-2"
                               style="background: var(--action-info);">
                                <i class="fas fa-store"></i>
                                Continuer achats
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        @else
            <!-- Panier vide -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg p-10 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl flex items-center justify-center mb-4 text-white" style="background: var(--action-info);">
                    <i class="fas fa-cart-shopping text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Votre panier est vide</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Explorez notre catalogue et découvrez des produits exceptionnels !</p>
                <a href="{{ route('shop.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:opacity-90 transition"
                   style="background: var(--action-success);">
                    <i class="fas fa-store"></i>
                    <span>Découvrir la boutique</span>
                </a>
            </div>
        @endif
    </div>
</section>

@endsection
