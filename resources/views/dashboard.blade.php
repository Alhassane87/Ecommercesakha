@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
@php
    $user = auth()->user();

    $totalOrders = $user ? (int) $user->orders()->count() : 0;
    $activeOrders = $user ? (int) $user->orders()->whereIn('status', ['received', 'pending', 'confirmed', 'processing', 'shipped'])->count() : 0;
    $deliveredOrders = $user ? (int) $user->orders()->where('status', 'delivered')->count() : 0;
    $loyaltyPoints = $deliveredOrders * 50;

    $recentOrders = $user
        ? $user->orders()->latest()->take(5)->get()
        : collect();

    $waRaw = (string) config('platform.contact_whatsapp', config('platform.contact_phone', '762080009'));
    $waDigits = preg_replace('/\D+/', '', $waRaw) ?? '';
    if ($waDigits !== '' && strlen($waDigits) === 9 && strpos($waDigits, '221') !== 0) {
        $waDigits = '221' . $waDigits;
    }
    $waText = rawurlencode('Bonjour Sakha, j ai besoin d aide sur mon compte.');
    $waUrl = $waDigits !== '' ? "https://wa.me/{$waDigits}?text={$waText}" : null;

    $statusMeta = [
        'received' => ['label' => 'Recue', 'class' => 'bg-blue-100 text-blue-700'],
        'pending' => ['label' => 'En attente', 'class' => 'bg-amber-100 text-amber-700'],
        'confirmed' => ['label' => 'Confirmee', 'class' => 'bg-sky-100 text-sky-700'],
        'processing' => ['label' => 'Traitement', 'class' => 'bg-indigo-100 text-indigo-700'],
        'shipped' => ['label' => 'Expediee', 'class' => 'bg-violet-100 text-violet-700'],
        'delivered' => ['label' => 'Livree', 'class' => 'bg-emerald-100 text-emerald-700'],
        'cancelled' => ['label' => 'Annulee', 'class' => 'bg-rose-100 text-rose-700'],
    ];
@endphp

<section class="relative overflow-hidden py-8 sm:py-12">
    <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full blur-3xl opacity-25" style="background: var(--icon-blue);"></div>
    <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full blur-3xl opacity-25" style="background: var(--accent-gradient);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl text-white p-6 sm:p-8 shadow-2xl mb-8" style="background: var(--primary-gradient);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80">Espace Client</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold mt-2">Bonjour {{ $user?->name }}</h1>
                    <p class="mt-2 text-white/90">Suivez vos commandes, gerez votre compte et contactez le support rapidement.</p>
                </div>
                <div class="inline-flex items-center gap-3 rounded-2xl bg-white/20 backdrop-blur px-4 py-3 border border-white/30">
                    <div class="w-12 h-12 rounded-xl bg-white/25 flex items-center justify-center">
                        <span class="text-xl font-bold">{{ strtoupper(substr((string) ($user?->name ?? 'U'), 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-white/80">Compte</p>
                        <p class="font-semibold">{{ $user?->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            <article class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total commandes</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $totalOrders }}</p>
                <p class="text-xs text-gray-500 mt-1">Historique global</p>
            </article>

            <article class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Commandes actives</p>
                <p class="text-3xl font-extrabold text-indigo-700 mt-2">{{ $activeOrders }}</p>
                <p class="text-xs text-gray-500 mt-1">En cours de traitement</p>
            </article>

            <article class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Commandes livrees</p>
                <p class="text-3xl font-extrabold text-emerald-700 mt-2">{{ $deliveredOrders }}</p>
                <p class="text-xs text-gray-500 mt-1">Commandes finalisees</p>
            </article>

            <article class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <p class="text-sm text-gray-500">Points fidelite</p>
                <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $loyaltyPoints }}</p>
                <p class="text-xs text-gray-500 mt-1">50 points par commande livree</p>
            </article>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            <a href="{{ route('orders.index') }}" class="rounded-2xl p-5 text-white shadow-xl hover:opacity-95 transition" style="background: var(--action-info);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white/85">Mes commandes</p>
                        <p class="font-bold text-lg mt-1">Voir le detail</p>
                    </div>
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </a>

            <a href="{{ route('shop.index') }}" class="rounded-2xl p-5 text-white shadow-xl hover:opacity-95 transition" style="background: var(--shop-gradient);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white/85">Boutique</p>
                        <p class="font-bold text-lg mt-1">Continuer mes achats</p>
                    </div>
                    <i class="fas fa-store text-xl"></i>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}" class="rounded-2xl p-5 text-white shadow-xl hover:opacity-95 transition" style="background: var(--profile-gradient);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white/85">Mon profil</p>
                        <p class="font-bold text-lg mt-1">Mettre a jour</p>
                    </div>
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
            </a>

            <a href="{{ route('contact') }}" class="rounded-2xl p-5 text-white shadow-xl hover:opacity-95 transition" style="background: var(--action-success);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white/85">Support</p>
                        <p class="font-bold text-lg mt-1">Contacter Sakha</p>
                    </div>
                    <i class="fas fa-headset text-xl"></i>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 rounded-3xl bg-white border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Activite recente</h2>
                    <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">Voir tout</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="p-6 sm:p-8 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                        </div>
                        <p class="mt-4 text-gray-600">Aucune commande pour le moment.</p>
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 text-white rounded-xl font-semibold hover:opacity-90 transition" style="background: var(--action-primary);">
                            <i class="fas fa-shopping-bag"></i>
                            Commencer mes achats
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($recentOrders as $order)
                            @php
                                $meta = $statusMeta[$order->status] ?? ['label' => ucfirst((string) $order->status), 'class' => 'bg-gray-100 text-gray-700'];
                            @endphp
                            <div class="px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">Commande #{{ $order->order_number ?? $order->id }}</p>
                                    <p class="text-sm text-gray-500">{{ number_format((float) $order->total, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $meta['class'] }}">
                                        {{ $meta['label'] }}
                                    </span>
                                    <a href="{{ route('orders.show', ['order' => $order->id, 'token' => $order->public_token]) }}" class="text-sm font-semibold text-blue-600 hover:underline">
                                        Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="rounded-3xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                <h3 class="text-lg font-bold text-gray-900">Besoin d aide ?</h3>
                <p class="text-sm text-gray-600 mt-2">Notre equipe support est disponible pour les commandes, livraisons et paiements.</p>

                <div class="mt-5 space-y-3">
                    <a href="{{ route('contact') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-white font-semibold hover:opacity-90 transition" style="background: var(--button-primary);">
                        <i class="fas fa-envelope"></i>
                        Ouvrir le support
                    </a>

                    @if($waUrl)
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-white font-semibold bg-green-600 hover:bg-green-700 transition">
                            <i class="fab fa-whatsapp"></i>
                            Support WhatsApp
                        </a>
                    @endif
                </div>

                <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs uppercase tracking-wider text-gray-500">Infos rapides</p>
                    <ul class="mt-3 space-y-2 text-sm text-gray-700">
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-600"></i> Suivi commande: /track</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-600"></i> Mise a jour profil: Mon Profil</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-600"></i> Catalogue complet: Boutique</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
