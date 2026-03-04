@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name', 'Sakha'))

@section('content')
<section class="relative overflow-hidden py-10 sm:py-14">
    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: var(--icon-blue);"></div>
    <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full blur-3xl opacity-25" style="background: var(--accent-gradient);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 rounded-3xl p-6 sm:p-8 text-white shadow-xl" style="background: var(--primary-gradient);">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">Checkout</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">Finaliser votre commande</h1>
                    <p class="mt-2 text-white/85">Paiement disponible actuellement: paiement a la livraison uniquement.</p>
                </div>
                <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-white/20 backdrop-blur px-4 py-2 border border-white/30">
                    <i class="fas fa-truck-fast"></i>
                    <span class="text-sm font-semibold">Paiement a la reception</span>
                </div>
            </div>
        </div>

        @if(!$cart || !$cart->items->count())
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-10 text-center shadow-lg">
                <div class="mx-auto w-16 h-16 rounded-2xl flex items-center justify-center mb-4 text-white" style="background: var(--action-info);">
                    <i class="fas fa-cart-shopping text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Votre panier est vide</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Ajoutez des produits avant de passer a la caisse.</p>
                <a href="{{ route('shop.index') }}"
                   class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:opacity-90 transition"
                   style="background: var(--action-primary);">
                    <i class="fas fa-store"></i>
                    <span>Retour a la boutique</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2">
                    <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 px-5 py-4">
                                <p class="font-semibold text-red-700 dark:text-red-300">Le formulaire contient des erreurs:</p>
                                <ul class="mt-2 list-disc pl-5 text-sm text-red-700 dark:text-red-300">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 sm:p-8 shadow-lg">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-primary);">
                                    <i class="fas fa-location-dot"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Adresse de livraison</h2>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Adresse complete *</label>
                                    <textarea
                                        name="shipping_address[address]"
                                        rows="3"
                                        required
                                        class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Rue, ville, code postal..."
                                    >{{ old('shipping_address.address') }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Email (optionnel)</label>
                                        <input
                                            type="email"
                                            name="customer_email"
                                            value="{{ old('customer_email') }}"
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="votre@email.com"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Telephone *</label>
                                        <input
                                            type="text"
                                            name="customer_phone"
                                            value="{{ old('customer_phone') }}"
                                            required
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Votre numero"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 sm:p-8 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center" style="background: var(--action-success);">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Methode de paiement</h2>
                            </div>

                            <input type="hidden" name="payment_method" value="cash">

                            <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-emerald-800 dark:text-emerald-200">Paiement a la livraison</p>
                                            <p class="text-sm text-emerald-700 dark:text-emerald-300">Vous payez en cash a la reception de votre commande.</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-wide px-2 py-1 rounded-full bg-emerald-600 text-white">Actif</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total" value="{{ $cart->items->sum(fn($i) => $i->qty * $i->unit_price) }}">

                        <button type="submit"
                                class="w-full rounded-2xl px-6 py-4 text-white font-bold text-base shadow-xl hover:opacity-90 transition"
                                style="background: var(--button-success);">
                            <i class="fas fa-check-circle mr-2"></i>
                            Confirmer la commande
                        </button>
                    </form>
                </div>

                <aside class="xl:col-span-1">
                    <div class="xl:sticky xl:top-24 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Recapitulatif</h3>

                        <div class="space-y-3 max-h-[380px] overflow-auto pr-1">
                            @foreach($cart->items as $item)
                                @php
                                    $displayAttributes = [];
                                    if (is_array($item->selected_attributes)) {
                                        foreach ($item->selected_attributes as $key => $value) {
                                            if (is_array($value)) {
                                                $value = implode(', ', array_filter(array_map('strval', $value)));
                                            } else {
                                                $value = trim((string) $value);
                                            }

                                            if ($value === '') {
                                                continue;
                                            }

                                            $displayAttributes[] = ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value;
                                        }
                                    }
                                @endphp

                                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Quantite: {{ $item->qty }}</p>
                                        </div>
                                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ number_format($item->unit_price * $item->qty, 2, ',', ' ') }} fcfa</p>
                                    </div>

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
                            @endforeach
                        </div>

                        <div class="mt-5 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span>Sous-total</span>
                                <span>{{ number_format($cart->items->sum(fn($i) => $i->qty * $i->unit_price), 2, ',', ' ') }} fcfa</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span>Livraison</span>
                                <span>A confirmer</span>
                            </div>
                            <div class="flex items-center justify-between text-lg font-extrabold text-gray-900 dark:text-gray-100 pt-2">
                                <span>Total</span>
                                <span>{{ number_format($cart->items->sum(fn($i) => $i->qty * $i->unit_price), 2, ',', ' ') }} fcfa</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</section>
@endsection
