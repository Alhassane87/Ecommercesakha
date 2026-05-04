@extends('layouts.admin')

@section('title', 'Tableau de Bord Admin')

@section('content')
@php
    use App\Models\Order;

    $productsCount = \App\Models\Product::count();
    $categoriesCount = \App\Models\Category::count();

    $ordersCount = Order::count();
    $usersCount = \App\Models\User::count();
    $recentProducts = \App\Models\Product::with('category')->latest()->take(5)->get();
    $recentOrders = Order::with('user')->latest()->take(5)->get();

    $revenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
    $ordersByStatus = Order::groupBy('status')
        ->selectRaw('status, count(*) as count')
        ->pluck('count', 'status')
        ->toArray();
    $statusData = [
        (int) ($ordersByStatus['pending'] ?? 0),
        (int) ($ordersByStatus['processing'] ?? 0),
        (int) ($ordersByStatus['shipped'] ?? 0),
        (int) ($ordersByStatus['delivered'] ?? 0),
        (int) ($ordersByStatus['cancelled'] ?? 0),
    ];

    $lowStock = \App\Models\Product::where('stock', '<', 5)->orderBy('stock')->take(5)->get();
    $recentUsers = \App\Models\User::latest()->take(5)->get();

    // Categories les plus actives (pour afficher leurs icones)
    $topCategories = \App\Models\Category::withCount('products')
        ->with('attributes')
        ->where('is_active', true)
        ->orderByDesc('products_count')
        ->take(6)
        ->get();

    // Statistiques jour / mois / annee
    $today = now()->startOfDay();
    $monthStart = now()->copy()->startOfMonth();
    $yearStart = now()->copy()->startOfYear();

    $todayRevenue = Order::whereNotIn('status', ['cancelled'])
        ->whereDate('created_at', $today)
        ->sum('total');
    $todayOrders = Order::whereDate('created_at', $today)->count();

    $monthRevenue = Order::whereNotIn('status', ['cancelled'])
        ->whereBetween('created_at', [$monthStart, now()])
        ->sum('total');
    $monthOrders = Order::whereBetween('created_at', [$monthStart, now()])->count();

    $yearRevenue = Order::whereNotIn('status', ['cancelled'])
        ->whereBetween('created_at', [$yearStart, now()])
        ->sum('total');
    $yearOrders = Order::whereBetween('created_at', [$yearStart, now()])->count();

    // Series pour graphiques (7 derniers jours, 6 derniers mois, 12 derniers mois)
    $dailySeries = Order::whereNotIn('status', ['cancelled'])
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupByRaw('DATE(created_at)')
        ->orderByRaw('DATE(created_at)')
        ->selectRaw('DATE(created_at) as d, SUM(total) as total')
        ->get();

    $monthlySeries = Order::whereNotIn('status', ['cancelled'])
        ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
        ->groupByRaw("strftime('%Y-%m', created_at)")
        ->orderByRaw("strftime('%Y-%m', created_at)")
        ->selectRaw("strftime('%Y-%m', created_at) as m, SUM(total) as total")
        ->get();

    $yearlySeries = Order::whereNotIn('status', ['cancelled'])
        ->where('created_at', '>=', now()->subYears(4)->startOfYear())
        ->groupByRaw("strftime('%Y', created_at)")
        ->orderByRaw("strftime('%Y', created_at)")
        ->selectRaw("strftime('%Y', created_at) as y, SUM(total) as total")
        ->get();
@endphp

<div class="space-y-8">
    <!-- Cartes de statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Produits -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Produits</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $productsCount }}</div>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center space-x-1 text-purple-600 dark:text-purple-400 text-sm hover:text-purple-700 dark:hover:text-purple-300 transition mt-3">
                <span>Gerer les produits</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Categories -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Categories</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $categoriesCount }}</div>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-tags text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center space-x-1 text-green-600 dark:text-green-400 text-sm hover:text-green-700 dark:hover:text-green-300 transition mt-3">
                <span>Gerer les categories</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Commandes -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Commandes</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $ordersCount }}</div>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center space-x-1 text-blue-600 dark:text-blue-400 text-sm hover:text-blue-700 dark:hover:text-blue-300 transition mt-3">
                <span>Gerer les commandes</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Utilisateurs -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Utilisateurs</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $usersCount }}</div>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-1 text-orange-600 dark:text-orange-400 text-sm hover:text-orange-700 dark:hover:text-orange-300 transition mt-3">
                <span>Gerer les utilisateurs</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Grille principale -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Derniers Produits -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Derniers Produits</h2>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center space-x-1 text-purple-600 dark:text-purple-400 text-sm hover:text-purple-700 dark:hover:text-purple-300 transition">
                    <span>Voir tout</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentProducts as $product)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <div class="flex items-center space-x-4">
                            @if($product->images->first())
                                <img src="{{ Storage::url($product->images->first()->path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 dark:text-gray-300"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? '-' }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-box-open text-3xl mb-3"></i>
                        <p>Aucun produit</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Statistiques Commandes -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Statistiques Commandes</h2>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-xl">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($revenue ?? 0, 0, ',', ' ') }} fcfa</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Chiffre d'affaires</div>
                </div>
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $ordersCount }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total commandes</div>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['pending' => 'En attente', 'processing' => 'En traitement', 'shipped' => 'Expediee', 'delivered' => 'Livree', 'cancelled' => 'Annulee'] as $status => $label)
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $ordersByStatus[$status] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistiques temporelles (jour / mois / annee) -->
    <div class="mt-10 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Statistiques de ventes</h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">Jour / Mois / Annee</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/40">
                <div class="text-xs uppercase text-gray-500 dark:text-gray-300 mb-1">Aujourd'hui</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ number_format($todayRevenue, 0, ',', ' ') }} fcfa</div>
                <div class="text-xs text-gray-600 dark:text-gray-300">{{ $todayOrders }} commande(s)</div>
            </div>
            <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/40">
                <div class="text-xs uppercase text-gray-500 dark:text-gray-300 mb-1">Ce mois-ci</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ number_format($monthRevenue, 0, ',', ' ') }} fcfa</div>
                <div class="text-xs text-gray-600 dark:text-gray-300">{{ $monthOrders }} commande(s)</div>
            </div>
            <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/40">
                <div class="text-xs uppercase text-gray-500 dark:text-gray-300 mb-1">Cette annee</div>
                <div class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ number_format($yearRevenue, 0, ',', ' ') }} fcfa</div>
                <div class="text-xs text-gray-600 dark:text-gray-300">{{ $yearOrders }} commande(s)</div>
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Evolution - 7 derniers jours</h3>
                <div class="h-64">
                    <canvas id="chartDaily"></canvas>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Histogramme - 6 derniers mois</h3>
                <div class="h-64">
                    <canvas id="chartMonthly"></canvas>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Histogramme - 5 dernieres annees</h3>
                <div class="h-64">
                    <canvas id="chartYearly"></canvas>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/30 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Repartition des commandes (diagramme en cercle)</h3>
                <div class="h-64">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille inferieure -->
    <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-4 gap-8 items-start">
        <!-- Utilisateurs Recents -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 h-full">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Utilisateurs Recents</h3>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 dark:text-white break-words">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 break-all">{{ $user->email }}</div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-300 shrink-0">{{ $user->created_at->format('d/m') }}</div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                        <p>Aucun utilisateur</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Produits Faible Stock -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 h-full">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Produits Faible Stock</h3>
            <div class="space-y-3">
                @forelse($lowStock as $product)
                    <div class="flex justify-between items-start gap-3 p-3 bg-red-50 dark:bg-red-900 rounded-lg">
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-gray-900 dark:text-white break-words">{{ $product->name }}</div>
                            <div class="text-xs text-red-600 dark:text-red-400">Stock: {{ $product->stock }}</div>
                        </div>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4 text-green-600 dark:text-green-400">
                        <i class="fas fa-check-circle mr-2"></i>Stock optimal
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Categories avec Icones Magiques -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-layer-group text-purple-600 dark:text-purple-400 mr-2"></i>
                    Categories Actives
                </h3>
                <a href="{{ route('admin.categories.index') }}" class="text-xs text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                    Voir tout
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($topCategories as $cat)
                    <a href="{{ route('admin.categories.edit', $cat) }}" 
                       class="group relative overflow-hidden rounded-xl p-4 border border-purple-100 dark:border-purple-800 hover:shadow-lg transition-all duration-300 transform hover:scale-105" style="background: var(--bg-gradient-light)">
                        <!-- Effet de brillance anime -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 via-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg transform group-hover:rotate-6 group-hover:scale-110 transition-all duration-300">
                                    <i class="{{ $cat->icon ?? 'fas fa-folder' }} text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white break-words group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                        {{ $cat->name }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center space-x-1">
                                        <i class="fas fa-box text-xs"></i>
                                        <span>{{ $cat->products_count }} produit(s)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Badge pour les attributs -->
                            @if($cat->attributes && $cat->attributes->count() > 0)
                                <div class="mt-2 flex items-center space-x-1">
                                    <span class="text-xs bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 px-2 py-1 rounded-full">
                                        <i class="fas fa-tags text-xs mr-1"></i>
                                        {{ $cat->attributes->count() }} attribut(s)
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Indicateur de survol -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-arrow-right text-purple-500 text-xs"></i>
                        </div>
                    </a>
                @empty
                    <div class="col-span-2 text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-folder-open text-3xl mb-3 opacity-50"></i>
                        <p>Aucune categorie</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 h-full">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Actions Rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.products.create') }}" 
                   class="flex items-center space-x-3 p-4 text-white rounded-xl transition shadow-lg" style="background: var(--action-primary)">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Creer un produit</span>
                </a>
                <a href="{{ route('admin.categories.create') }}" 
                   class="flex items-center space-x-3 p-4 text-white rounded-xl transition shadow-lg" style="background: var(--action-success)">
                    <i class="fas fa-tag"></i>
                    <span class="font-semibold">Creer une categorie</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center space-x-3 p-4 text-white rounded-xl transition shadow-lg" style="background: var(--action-info)">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="font-semibold">Gerer les commandes</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dailyLabels = @json($dailySeries->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m')));
            const dailyData = @json($dailySeries->pluck('total')->map(fn($v) => (float) $v));

            const monthlyLabels = @json($monthlySeries->pluck('m')->map(fn($m) => \Carbon\Carbon::parse($m.'-01')->format('m/Y')));
            const monthlyData = @json($monthlySeries->pluck('total')->map(fn($v) => (float) $v));

            const yearlyLabels = @json($yearlySeries->pluck('y'));
            const yearlyData = @json($yearlySeries->pluck('total')->map(fn($v) => (float) $v));

            const statusLabels = ['En attente', 'En traitement', 'Expediee', 'Livree', 'Annulee'];
            const statusData = @json($statusData);

            let charts = [];

            function amount(value) {
                return Number(value || 0).toLocaleString('fr-FR') + ' fcfa';
            }

            function commonCartesianOptions(ui) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: ui.tooltipBg,
                            titleColor: ui.tooltipTitle,
                            bodyColor: ui.tooltipBody,
                            borderColor: ui.tooltipBorder,
                            borderWidth: 1,
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: ui.axisText },
                            grid: { color: ui.gridColor },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: ui.axisText,
                                callback: (v) => amount(v),
                            },
                            grid: { color: ui.gridColor },
                        }
                    }
                };
            }

            function buildLineChart(ctxId, labels, data, ui) {
                const el = document.getElementById(ctxId);
                if (!el || !labels.length) return;

                charts.push(new Chart(el, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            data,
                            borderColor: 'rgb(59,130,246)',
                            backgroundColor: 'rgba(59,130,246,0.18)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.35,
                            fill: true,
                        }]
                    },
                    options: {
                        ...commonCartesianOptions(ui),
                        plugins: {
                            ...commonCartesianOptions(ui).plugins,
                            tooltip: {
                                ...commonCartesianOptions(ui).plugins.tooltip,
                                callbacks: {
                                    label: (context) => amount(context.parsed.y),
                                }
                            }
                        }
                    }
                }));
            }

            function buildBarChart(ctxId, labels, data, color, ui) {
                const el = document.getElementById(ctxId);
                if (!el || !labels.length) return;

                charts.push(new Chart(el, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            data,
                            backgroundColor: color,
                            borderRadius: 8,
                            borderSkipped: false,
                            maxBarThickness: 42,
                        }]
                    },
                    options: {
                        ...commonCartesianOptions(ui),
                        plugins: {
                            ...commonCartesianOptions(ui).plugins,
                            tooltip: {
                                ...commonCartesianOptions(ui).plugins.tooltip,
                                callbacks: {
                                    label: (context) => amount(context.parsed.y),
                                }
                            }
                        }
                    }
                }));
            }

            function buildDoughnutChart(ctxId, labels, data, ui) {
                const el = document.getElementById(ctxId);
                if (!el) return;

                const isDark = document.documentElement.classList.contains('dark');
                const palette = isDark
                    ? ['#fbbf24', '#60a5fa', '#a78bfa', '#34d399', '#f87171']
                    : ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'];

                charts.push(new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels,
                        datasets: [{
                            data,
                            backgroundColor: palette,
                            borderColor: ui.cardBg,
                            borderWidth: 2,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: ui.axisText,
                                    padding: 16,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                }
                            },
                            tooltip: {
                                backgroundColor: ui.tooltipBg,
                                titleColor: ui.tooltipTitle,
                                bodyColor: ui.tooltipBody,
                                borderColor: ui.tooltipBorder,
                                borderWidth: 1,
                                callbacks: {
                                    label: (context) => {
                                        const raw = Number(context.raw || 0);
                                        const total = context.dataset.data.reduce((sum, val) => sum + Number(val || 0), 0);
                                        const pct = total > 0 ? ((raw / total) * 100).toFixed(1) : '0.0';
                                        return raw.toLocaleString('fr-FR') + ' commande(s) - ' + pct + '%';
                                    }
                                }
                            }
                        }
                    }
                }));
            }

            function renderCharts() {
                charts.forEach((chart) => chart.destroy());
                charts = [];

                const isDark = document.documentElement.classList.contains('dark');
                const ui = {
                    axisText: isDark ? '#d1d5db' : '#475569',
                    gridColor: isDark ? 'rgba(148, 163, 184, 0.20)' : 'rgba(148, 163, 184, 0.25)',
                    tooltipBg: isDark ? '#0f172a' : '#ffffff',
                    tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
                    tooltipBody: isDark ? '#e2e8f0' : '#334155',
                    tooltipBorder: isDark ? '#334155' : '#e2e8f0',
                    cardBg: isDark ? '#111827' : '#f8fafc',
                };

                buildLineChart('chartDaily', dailyLabels, dailyData, ui);
                buildBarChart('chartMonthly', monthlyLabels, monthlyData, 'rgba(16,185,129,0.85)', ui);
                buildBarChart('chartYearly', yearlyLabels, yearlyData, 'rgba(139,92,246,0.85)', ui);
                buildDoughnutChart('chartStatus', statusLabels, statusData, ui);
            }

            renderCharts();

            document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    setTimeout(renderCharts, 10);
                });
            });
        });
    </script>
@endpush


