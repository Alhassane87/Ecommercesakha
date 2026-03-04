@extends('layouts.app')

@section('title', 'Commande ' . ($order->display_number ?? ('CMD-' . $order->id)) . ' - ' . config('app.name', 'Sakha'))

@section('content')
@php
    $statusMap = [
        'received' => ['label' => 'Recue', 'icon' => 'fa-inbox', 'badge' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300'],
        'pending' => ['label' => 'En attente', 'icon' => 'fa-clock', 'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300'],
        'confirmed' => ['label' => 'Confirmee', 'icon' => 'fa-circle-check', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'],
        'processing' => ['label' => 'En traitement', 'icon' => 'fa-gears', 'badge' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300'],
        'shipped' => ['label' => 'Expediee', 'icon' => 'fa-truck-fast', 'badge' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300'],
        'delivered' => ['label' => 'Livree', 'icon' => 'fa-box-open', 'badge' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'],
        'cancelled' => ['label' => 'Annulee', 'icon' => 'fa-ban', 'badge' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300'],
    ];

    $statusKey = strtolower((string) ($order->status ?? 'pending'));
    $status = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'icon' => 'fa-circle-info', 'badge' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'];
    $orderNumber = $order->display_number ?? ('CMD-' . $order->id);
    $shippingAddress = is_array($order->shipping_address)
        ? ($order->shipping_address['address'] ?? implode(', ', array_filter($order->shipping_address)))
        : (string) ($order->shipping_address ?? '');
    $trackingUrl = $publicTrackingUrl ?? route('orders.show', ['order' => $order->id, 'token' => $order->public_token]);
    // QR dedicated to quick copy of order number when scanned from another device.
    $qrOrderNumberUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=8&data=' . urlencode($orderNumber);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-6">
    <section class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white shadow-xl" style="background: var(--primary-gradient);">
        <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-black/10 blur-3xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-2">Detail commande</p>
                <h1 class="text-3xl sm:text-4xl font-extrabold mb-2">Commande {{ $orderNumber }}</h1>
                <p class="text-white/85">Passee le {{ $order->created_at?->format('d/m/Y H:i') }}</p>
                <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold {{ $status['badge'] }}">
                    <i class="fas {{ $status['icon'] }}"></i>
                    {{ $status['label'] }}
                </div>
            </div>

            <div class="flex flex-col sm:items-end gap-2">
                <div class="text-white/85 text-sm">Montant total</div>
                <div class="text-3xl font-black">{{ number_format($order->total, 2, ',', ' ') }} fcfa</div>
                <button type="button"
                        onclick="window.print()"
                        class="no-print inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold text-white shadow-lg hover:opacity-90 transition"
                        style="background: var(--button-primary);">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer la facture
                </button>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <section class="xl:col-span-2 space-y-4">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Articles commandes</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $order->items->count() }} article(s) dans cette commande.</p>
            </div>

            @forelse($order->items as $item)
                @php
                    $lineTotal = (float) $item->qty * (float) $item->unit_price;
                    $displayAttributes = $item->display_attributes ?? [];

                    if (empty($displayAttributes) && $item->variation && is_array($item->variation->attributes)) {
                        $displayAttributes = collect($item->variation->attributes)
                            ->map(function ($value, $key) {
                                return ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value;
                            })
                            ->values()
                            ->all();
                    }
                @endphp
                <article class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            @if($item->product && $item->product->images && $item->product->images->first())
                                <img src="{{ Storage::url($item->product->images->first()->path) }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-600">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 dark:text-gray-300 text-lg"></i>
                                </div>
                            @endif

                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $item->product->name ?? 'Produit supprime' }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $item->qty }} x {{ number_format($item->unit_price, 2, ',', ' ') }} fcfa</p>

                                @if(!empty($displayAttributes))
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($displayAttributes as $label)
                                            <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                                {{ $label }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Total ligne</div>
                            <div class="text-lg font-extrabold text-gray-900 dark:text-gray-100">{{ number_format($lineTotal, 2, ',', ' ') }} fcfa</div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center text-gray-500 dark:text-gray-400">
                    Aucun article trouve pour cette commande.
                </div>
            @endforelse
        </section>

        <aside class="xl:col-span-1 space-y-4">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Informations client</h3>
                <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <p><span class="font-semibold">Email:</span> {{ $order->customer_email ?? $order->user->email ?? 'Non renseigne' }}</p>
                    <p><span class="font-semibold">Telephone:</span> {{ $order->customer_phone ?? 'Non renseigne' }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Livraison</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $shippingAddress !== '' ? $shippingAddress : 'Adresse non renseignee' }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Paiement et suivi</h3>
                <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <p><span class="font-semibold">Paiement:</span> {{ strtoupper((string) ($order->payment_provider ?? 'N/A')) }}</p>
                    <p><span class="font-semibold">Reference:</span> {{ $order->payment_reference ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Tracking:</span> {{ $order->tracking_number ?? 'En attente' }}</p>
                </div>
                <div class="mt-4 space-y-3 text-xs">
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-3 py-2 bg-gray-50 dark:bg-gray-900/40">
                        <div class="text-gray-500 dark:text-gray-400 uppercase tracking-wide">Numero de commande</div>
                        <div class="mt-1 flex items-center justify-between gap-2">
                            <code class="font-bold text-gray-900 dark:text-gray-100">{{ $orderNumber }}</code>
                            <button type="button"
                                    class="no-print inline-flex items-center gap-1 rounded-lg border border-gray-200 dark:border-gray-700 px-2 py-1 font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                    data-copy-text="{{ $orderNumber }}"
                                    data-copy-feedback="Numero de commande copie">
                                <i class="fas fa-copy"></i>
                                Copier
                            </button>
                        </div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-3 py-2 bg-gray-50 dark:bg-gray-900/40">
                        <div class="text-gray-500 dark:text-gray-400 uppercase tracking-wide">Lien de suivi</div>
                        <div class="mt-1 flex items-center justify-between gap-2">
                            <a href="{{ $trackingUrl }}"
                               target="_blank"
                               rel="noopener"
                               class="text-blue-700 dark:text-blue-300 font-semibold hover:underline">
                                Ouvrir le suivi
                            </a>
                            <button type="button"
                                    class="no-print inline-flex items-center gap-1 rounded-lg border border-gray-200 dark:border-gray-700 px-2 py-1 font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                    data-copy-text="{{ $trackingUrl }}"
                                    data-copy-feedback="Lien de suivi copie">
                                <i class="fas fa-link"></i>
                                Copier
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">QR numero commande</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Scannez ce QR code pour recuperer et copier directement votre numero de commande.
                </p>
                <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 p-3 flex items-center justify-center">
                    <img src="{{ $qrOrderNumberUrl }}"
                         alt="QR code numero commande {{ $orderNumber }}"
                         class="w-40 h-40">
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                    Le QR contient: <strong>{{ $orderNumber }}</strong>
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm no-print space-y-3">
                @if(auth()->check())
                    <a href="{{ route('orders.index') }}"
                       class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour commandes</span>
                    </a>
                @else
                    <a href="{{ route('shop.index') }}"
                       class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-store"></i>
                        <span>Aller boutique</span>
                    </a>
                @endif

                @auth
                    @if(method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                        <a href="{{ route('admin.orders.edit', $order) }}"
                           class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 rounded-xl text-white font-semibold hover:opacity-90 transition"
                           style="background: var(--action-info);">
                            <i class="fas fa-pen-to-square"></i>
                            <span>Modifier (Admin)</span>
                        </a>
                    @endif
                @endauth
            </div>
        </aside>
    </div>

    <section id="invoice-print-area" class="invoice-sheet">
        <header class="invoice-header">
            <div>
                <h2>{{ config('app.name', 'Sakha') }}</h2>
                <p>Facture client</p>
            </div>
            <div class="invoice-meta">
                <div><strong>Commande:</strong> {{ $orderNumber }}</div>
                <div><strong>Date:</strong> {{ $order->created_at?->format('d/m/Y H:i') }}</div>
                <div><strong>Statut:</strong> {{ $status['label'] }}</div>
            </div>
        </header>

        <section class="invoice-grid">
            <div>
                <h3>Client</h3>
                <p>{{ $order->customer_email ?? $order->user->email ?? 'Non renseigne' }}</p>
                <p>{{ $order->customer_phone ?? 'Non renseigne' }}</p>
            </div>
            <div>
                <h3>Livraison</h3>
                <p>{{ $shippingAddress !== '' ? $shippingAddress : 'Adresse non renseignee' }}</p>
                <p>Tracking: {{ $order->tracking_number ?? 'En attente' }}</p>
            </div>
            <div>
                <h3>Paiement</h3>
                <p>Mode: {{ strtoupper((string) ($order->payment_provider ?? 'N/A')) }}</p>
                <p>Reference: {{ $order->payment_reference ?? 'N/A' }}</p>
            </div>
        </section>

        <section class="invoice-tracking">
            <div class="invoice-qr">
                <img src="{{ $qrOrderNumberUrl }}" alt="QR code numero commande {{ $orderNumber }}">
            </div>
            <div class="invoice-tracking-meta">
                <p><strong>Commande:</strong> {{ $orderNumber }}</p>
                <p><strong>Tracking:</strong> {{ $order->tracking_number ?? $orderNumber }}</p>
                <p><strong>Suivi manuel:</strong> /track</p>
                <p>Le QR contient le numero de commande pour copie rapide.</p>
            </div>
        </section>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantite</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @php
                        $printAttrs = $item->display_attributes ?? [];
                        if (empty($printAttrs) && $item->variation && is_array($item->variation->attributes)) {
                            $printAttrs = collect($item->variation->attributes)
                                ->map(fn ($value, $key) => ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value)
                                ->values()
                                ->all();
                        }
                    @endphp
                    <tr>
                        <td>
                            <div>{{ $item->product->name ?? 'Produit supprime' }}</div>
                            @if(!empty($printAttrs))
                                <small>{{ implode(' | ', $printAttrs) }}</small>
                            @endif
                        </td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->unit_price, 2, ',', ' ') }} fcfa</td>
                        <td>{{ number_format($item->qty * $item->unit_price, 2, ',', ' ') }} fcfa</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td>{{ number_format($order->total, 2, ',', ' ') }} fcfa</td>
                </tr>
            </tfoot>
        </table>

        <footer class="invoice-footer">
            Merci pour votre confiance.
        </footer>
    </section>
</div>
@endsection

@push('styles')
<style>
    .invoice-sheet {
        display: none;
    }

    @media print {
        @page {
            size: 58mm auto;
            margin: 0;
            padding: 0;
        }

        /* Masquer les éléments de navigation et layout */
        nav,
        footer,
        .chatbot-widget,
        .confirm-modal,
        .flash-notifications,
        .no-print,
        .max-w-7xl > section:not(.invoice-sheet),
        .max-w-7xl > .grid {
            display: none !important;
        }

        /* Afficher la facture */
        .invoice-sheet {
            display: block !important;
            width: 58mm;
            margin: 0;
            padding: 1.5mm;
            color: #000;
            font-family: monospace;
            background: #ffffff;
            page-break-inside: avoid;
            line-height: 1.15;
        }

        body {
            margin: 0;
            padding: 0;
            background: white;
        }

        .max-w-7xl {
            max-width: none;
            width: 58mm;
        }

        .invoice-header {
            border-bottom: 1px dashed #000;
            padding-bottom: 1mm;
            margin-bottom: 2mm !important;
            page-break-inside: avoid;
            text-align: center;
            display: block !important;
        }

        .invoice-header h2 {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin: 0 !important;
        }

        .invoice-header p {
            font-size: 6.5px;
            line-height: 1;
            margin: 2px 0 0 !important;
        }

        .invoice-meta {
            text-align: left;
            font-size: 6px;
            line-height: 1.2;
            margin-bottom: 1mm !important;
            display: block !important;
        }

        .invoice-meta div {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1;
            display: block !important;
        }

        .invoice-grid {
            margin-bottom: 2mm !important;
            page-break-inside: avoid;
            font-size: 6px;
            display: block !important;
        }

        .invoice-grid > div {
            margin-bottom: 1mm !important;
            page-break-inside: avoid;
            display: block !important;
        }

        .invoice-grid h3 {
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 0.5mm !important;
            display: block !important;
        }

        .invoice-grid p {
            font-size: 6px;
            line-height: 1;
            display: block !important;
            margin: 0 !important;
            word-break: break-word;
        }

        .invoice-tracking {
            border: 1px dashed #000;
            margin-bottom: 2mm !important;
            padding: 0.8mm;
            display: flex !important;
            gap: 1mm;
            align-items: center;
            page-break-inside: avoid;
        }

        .invoice-qr {
            width: 15mm;
            height: 15mm;
            flex: 0 0 15mm;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .invoice-qr img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .invoice-tracking-meta {
            font-size: 6px;
            line-height: 1.2;
            width: calc(100% - 16mm);
        }

        .invoice-tracking-meta p {
            margin: 0 0 0.4mm !important;
            word-break: break-word;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1mm 0 !important;
            table-layout: fixed;
            page-break-inside: avoid;
            font-size: 6px;
            display: table !important;
        }

        .invoice-table thead {
            display: table-header-group !important;
        }

        .invoice-table tbody {
            display: table-row-group !important;
        }

        .invoice-table tfoot {
            display: table-footer-group !important;
        }

        .invoice-table tr {
            page-break-inside: avoid;
            display: table-row !important;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 1px;
            font-size: 6px;
            vertical-align: top;
            line-height: 1;
            display: table-cell !important;
            word-break: break-word;
        }

        .invoice-table th:nth-child(1),
        .invoice-table td:nth-child(1) {
            width: 43%;
        }

        .invoice-table th:nth-child(2),
        .invoice-table td:nth-child(2) {
            width: 13%;
            text-align: center;
        }

        .invoice-table th:nth-child(3),
        .invoice-table td:nth-child(3) {
            width: 22%;
        }

        .invoice-table th:nth-child(4),
        .invoice-table td:nth-child(4) {
            width: 22%;
        }

        .invoice-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }

        .invoice-table small {
            display: inline !important;
            font-size: 5px;
        }

        .invoice-table tfoot td {
            font-weight: bold;
            font-size: 6.5px;
        }

        .invoice-footer {
            margin-top: 1mm !important;
            font-size: 6px;
            text-align: center;
            color: #000;
            page-break-inside: avoid;
            line-height: 1;
            display: block !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-copy-text]');
        if (!button) {
            return;
        }

        const value = button.getAttribute('data-copy-text') || '';
        if (!value) {
            return;
        }

        const defaultLabel = button.innerHTML;

        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(value);
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = value;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
            }

            const feedback = button.getAttribute('data-copy-feedback') || 'Copie';
            button.innerHTML = '<i class="fas fa-check"></i> ' + feedback;
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = defaultLabel;
                button.disabled = false;
            }, 1400);
        } catch (error) {
            console.error('Erreur de copie', error);
        }
    });
</script>
@endpush
