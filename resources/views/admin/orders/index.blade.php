@extends('layouts.admin')

@section('title', 'Gestion des Commandes - Admin')

@section('content')
<div class="text-white py-8 px-6 rounded-lg shadow-lg mb-6" style="background: var(--primary-gradient)">
    <div class="flex items-center space-x-4">
        <i class="fas fa-shopping-bag text-white text-3xl"></i>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Gestion des Commandes</h1>
            <p class="text-white/90">Suivez et gérez toutes vos commandes</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Gestion des Commandes</h1>
            <p class="text-gray-600 dark:text-gray-400">Suivez et gérez toutes les commandes de votre boutique</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->total() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Commandes totales</div>
            </div>
        </div>
    </div>

    <!-- Messages de statut -->
    @if(session('status'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-xl">
            <div class="flex items-center space-x-2 text-green-800 dark:text-green-200">
                <i class="fas fa-check-circle"></i>
                <span class="font-semibold">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Téléphone</label>
                    <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Numéro de téléphone..." 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Email</label>
                    <input type="text" name="email" value="{{ request('email') }}" placeholder="Email du client..." 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">N° Commande</label>
                    <input type="text" name="order_number" value="{{ request('order_number') }}" placeholder="Numéro de commande..." 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Date début</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Date fin</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 text-white px-4 py-2 rounded-xl transition duration-200" style="background: var(--action-primary)">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="text-white px-4 py-2 rounded-xl transition duration-200" style="background: var(--action-info)">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tableau des commandes -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <!-- En-tête du tableau -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
            <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-900 dark:text-white">
                <div class="col-span-1">#</div>
                <div class="col-span-3">Client</div>
                <div class="col-span-2">Montant</div>
                <div class="col-span-2">Statut</div>
                <div class="col-span-2">Date</div>
                <div class="col-span-2 text-right">Actions</div>
            </div>
        </div>

        <!-- Corps du tableau -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($orders as $order)
                <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <!-- Numéro de commande -->
                        <div class="col-span-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</div>
                        </div>

                        <!-- Client -->
                        <div class="col-span-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $order->user?->email ?? $order->customer_email ?? 'Guest' }}
                            </div>
                            @if($order->user)
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->name }}</div>
                            @endif
                            @if($order->customer_phone)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-phone mr-1"></i>{{ $order->customer_phone }}
                                </div>
                            @endif
                            @if($order->order_number)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-hashtag mr-1"></i>{{ $order->order_number }}
                                </div>
                            @endif
                        </div>

                        <!-- Montant -->
                        <div class="col-span-2">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($order->total, 2) }} fcfa
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-span-2">
                            @php
                                $statusConfig = [
                                    'pending' => ['color' => 'gray', 'icon' => 'clock', 'label' => 'En attente'],
                                    'processing' => ['color' => 'yellow', 'icon' => 'cog', 'label' => 'En traitement'],
                                    'shipped' => ['color' => 'blue', 'icon' => 'shipping-fast', 'label' => 'Expédiée'],
                                    'delivered' => ['color' => 'green', 'icon' => 'check-circle', 'label' => 'Livrée'],
                                    'cancelled' => ['color' => 'red', 'icon' => 'times-circle', 'label' => 'Annulée'],
                                ];
                                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900 text-{{ $config['color'] }}-800 dark:text-{{ $config['color'] }}-200">
                                <i class="fas fa-{{ $config['icon'] }} text-xs"></i>
                                <span>{{ $config['label'] }}</span>
                            </span>
                        </div>

                        <!-- Date -->
                        <div class="col-span-2">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                        </div>

                        <!-- Actions -->
                        <div class="col-span-2">
                            <div class="flex items-center justify-end space-x-2">
                                <a 
                                    href="{{ route('admin.orders.edit', $order) }}" 
                                    class="flex items-center space-x-1 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition"
                                    title="Éditer la commande"
                                >
                                    <i class="fas fa-edit text-xs"></i>
                                    <span class="hidden sm:inline">Modifier</span>
                                </a>
                                
                                <a 
                                    href="{{ route('orders.show', $order) }}" 
                                    class="flex items-center space-x-1 px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                    title="Voir les détails"
                                >
                                    <i class="fas fa-eye text-xs"></i>
                                    <span class="hidden sm:inline">Voir</span>
                                </a>

                                <form action="{{ route('admin.orders.destroy', $order) }}"
                                      method="POST"
                                      data-confirm="Supprimer definitivement cette commande ?"
                                      data-confirm-title="Supprimer la commande"
                                      data-confirm-ok="Supprimer"
                                      data-confirm-variant="danger">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center space-x-1 px-3 py-2 text-sm bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition" title="Supprimer la commande">
                                        <i class="fas fa-trash text-xs"></i>
                                        <span class="hidden sm:inline">Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- État vide -->
                <div class="px-6 py-16 text-center">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-600 dark:text-gray-300 mb-4">Aucune commande</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">Aucune commande n'a été passée pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                {{ $orders->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif
</div>
@endsection
