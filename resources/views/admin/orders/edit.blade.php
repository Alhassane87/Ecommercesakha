@extends('layouts.admin')

@section('title', 'Éditer Commande #' . $order->id . ' - Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-shopping-bag text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Éditer la Commande</h1>
            <p class="text-white/90">Mettez à jour le statut et les détails</p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Éditer la Commande</h1>
                <p class="text-gray-600 dark:text-gray-400">Modifiez le statut et les informations de la commande #{{ $order->id }}</p>
            </div>
            <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: var(--icon-gradient)">
                <i class="fas fa-shopping-bag text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Informations de la commande -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Résumé commande -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Résumé de la commande</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Client</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->user?->email ?? 'Guest' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Montant total</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ number_format($order->total, 2) }} fcfa</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Statut actuel</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($order->status === 'processing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'))) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            @if($order->items->isNotEmpty())
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Choix du client</h4>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            @php
                                $displayAttributes = $item->display_attributes ?? [];
                                if (empty($displayAttributes) && $item->variation && is_array($item->variation->attributes)) {
                                    $displayAttributes = collect($item->variation->attributes)
                                        ->map(fn ($value, $key) => ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value)
                                        ->values()
                                        ->all();
                                }
                            @endphp
                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->product->name ?? 'Produit supprimé' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->qty }} x {{ number_format($item->unit_price, 2, ',', ' ') }} fcfa
                                    </div>
                                </div>
                                @if(!empty($displayAttributes))
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($displayAttributes as $label)
                                            <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                                {{ $label }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Aucune option sélectionnée.</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('orders.show', $order) }}" 
                   class="flex items-center space-x-2 w-full px-4 py-3 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-xl hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                    <i class="fas fa-eye"></i>
                    <span>Voir la commande</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center space-x-2 w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour aux commandes</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
        <form method="post" action="{{ route('admin.orders.update', $order) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Statut de la commande
                    <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                >
                    @php 
                        // Codes internes : pending, processing, shipped, delivered, cancelled
                        $statuses = [
                            'pending' => ['label' => 'En attente'],
                            'processing' => ['label' => 'En traitement'],
                            'shipped' => ['label' => 'Expédiée'],
                            'delivered' => ['label' => 'Livrée'],
                            'cancelled' => ['label' => 'Annulée'],
                        ];
                    @endphp
                    @foreach($statuses as $code => $status)
                        <option value="{{ $code }}" {{ $order->status === $code ? 'selected' : '' }}>
                            {{ $status['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Numéro de suivi -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Numéro de suivi
                </label>
                <input 
                    type="text" 
                    name="tracking_number" 
                    value="{{ old('tracking_number', $order->tracking_number) }}" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition"
                    placeholder="Ex: TRK123456789"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Le numéro de suivi sera communiqué au client</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a 
                    href="{{ route('admin.orders.index') }}" 
                    class="flex items-center space-x-2 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Annuler</span>
                </a>
                
                <button 
                    type="submit" 
                    class="flex items-center space-x-2 px-8 py-3 text-white rounded-xl transition shadow-lg" style="background: var(--action-primary)"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour la commande</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
