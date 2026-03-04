@extends('layouts.app')

@section('title', 'Mes Commandes - ' . config('app.name', 'Sakha'))

@section('content')
@php
    $statusMeta = [
        'received' => ['label' => 'Recue', 'icon' => 'fa-inbox', 'badge' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300'],
        'pending' => ['label' => 'En attente', 'icon' => 'fa-clock', 'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300'],
        'confirmed' => ['label' => 'Confirmee', 'icon' => 'fa-circle-check', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'],
        'processing' => ['label' => 'En traitement', 'icon' => 'fa-gears', 'badge' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300'],
        'shipped' => ['label' => 'Expediee', 'icon' => 'fa-truck-fast', 'badge' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300'],
        'delivered' => ['label' => 'Livree', 'icon' => 'fa-box-open', 'badge' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'],
        'cancelled' => ['label' => 'Annulee', 'icon' => 'fa-ban', 'badge' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300'],
    ];

    $pendingCount = (int) (($statusCounts['pending'] ?? 0) + ($statusCounts['received'] ?? 0) + ($statusCounts['confirmed'] ?? 0) + ($statusCounts['processing'] ?? 0));
    $deliveredCount = (int) ($statusCounts['delivered'] ?? 0);
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">
    <section class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-xl" style="background: var(--primary-gradient);">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/15 blur-2xl"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-black/10 blur-3xl"></div>

        <div class="relative">
            <p class="text-xs uppercase tracking-[0.22em] text-white/80 mb-2">Historique</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-3">Mes commandes</h1>
            <p class="text-white/85 max-w-2xl">Suivez vos achats, verifiez les statuts et ouvrez chaque commande pour voir le detail complet et imprimer la facture.</p>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-2xl bg-white/20 backdrop-blur border border-white/30 p-4">
                    <div class="text-xs text-white/80 uppercase tracking-wide">Total commandes</div>
                    <div class="mt-1 text-2xl font-black">{{ $totalOrders }}</div>
                </div>
                <div class="rounded-2xl bg-white/20 backdrop-blur border border-white/30 p-4">
                    <div class="text-xs text-white/80 uppercase tracking-wide">En cours</div>
                    <div class="mt-1 text-2xl font-black">{{ $pendingCount }}</div>
                </div>
                <div class="rounded-2xl bg-white/20 backdrop-blur border border-white/30 p-4">
                    <div class="text-xs text-white/80 uppercase tracking-wide">Livrees</div>
                    <div class="mt-1 text-2xl font-black">{{ $deliveredCount }}</div>
                </div>
            </div>
        </div>
    </section>

    @if($orders->count())
        <section class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusKey = strtolower((string) ($order->status ?? 'pending'));
                    $meta = $statusMeta[$statusKey] ?? ['label' => ucfirst($statusKey), 'icon' => 'fa-circle-info', 'badge' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'];
                    $displayNumber = $order->display_number ?? ('CMD-' . $order->id);
                @endphp
                <article class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300">
                    <a href="{{ route('orders.show', $order) }}" class="block p-5 sm:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl text-white flex items-center justify-center shadow-lg" style="background: var(--action-primary);">
                                    <i class="fas {{ $meta['icon'] }}"></i>
                                </div>

                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-gray-100">Commande {{ $displayNumber }}</h2>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $meta['badge'] }}">
                                            <i class="fas {{ $meta['icon'] }} text-[10px]"></i>
                                            {{ $meta['label'] }}
                                        </span>
                                    </div>

                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span><i class="fas fa-calendar-day mr-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        <span><i class="fas fa-boxes-stacked mr-1"></i>{{ $order->items_count ?? $order->items->count() }} article(s)</span>
                                        @if($order->tracking_number)
                                            <span><i class="fas fa-route mr-1"></i>Suivi: {{ $order->tracking_number }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between lg:justify-end gap-5">
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</div>
                                    <div class="text-xl font-extrabold text-gray-900 dark:text-gray-100">{{ number_format($order->total, 2, ',', ' ') }} fcfa</div>
                                </div>
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold">
                                    Ouvrir
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </section>

        <div class="pt-2">
            {{ $orders->links() }}
        </div>
    @else
        <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-10 text-center shadow-sm">
            <div class="mx-auto w-20 h-20 rounded-2xl flex items-center justify-center text-white mb-4" style="background: var(--action-info);">
                <i class="fas fa-bag-shopping text-3xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Aucune commande pour le moment</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Parcourez la boutique et lancez votre premiere commande.</p>
            <a href="{{ route('shop.index') }}"
               class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-xl text-white font-semibold shadow-lg hover:opacity-90 transition"
               style="background: var(--action-success);">
                <i class="fas fa-store"></i>
                <span>Aller a la boutique</span>
            </a>
        </section>
    @endif
</div>
@endsection
