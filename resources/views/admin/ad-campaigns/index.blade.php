@extends('layouts.admin')

@section('title', 'Campagnes pub - ' . config('app.name', 'Sakha'))
@section('header', 'Campagnes publicitaires')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des pubs</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Creez et planifiez vos campagnes publicitaires dynamiques.</p>
        </div>
        <a href="{{ route('admin.ad-campaigns.create') }}"
           class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-white font-semibold shadow"
           style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
            <i class="fas fa-plus"></i>
            Nouvelle campagne
        </a>
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Campagne</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Placement</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Audience</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Periode</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Stats</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Etat</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $campaign->title }}</div>
                                <div class="text-xs text-gray-500">Priorite: {{ $campaign->priority }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ \App\Models\AdCampaign::placementOptions()[$campaign->placement] ?? $campaign->placement }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ \App\Models\AdCampaign::audienceOptions()[$campaign->audience] ?? $campaign->audience }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <div>{{ $campaign->starts_at?->format('d/m/Y H:i') ?? 'Immediate' }}</div>
                                <div>{{ $campaign->ends_at?->format('d/m/Y H:i') ?? 'Sans fin' }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <div>Imp: {{ number_format($campaign->impressions) }}</div>
                                <div>Clics: {{ number_format($campaign->clicks) }}</div>
                                <div>CTR: {{ number_format($campaign->ctr, 2) }}%</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($campaign->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200 px-2 py-1 text-xs font-semibold">Active</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-2 py-1 text-xs font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.ad-campaigns.edit', $campaign) }}"
                                       class="inline-flex items-center rounded-lg px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.ad-campaigns.destroy', $campaign) }}" onsubmit="return confirm('Supprimer cette campagne ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg px-3 py-1.5 bg-red-600 text-white text-xs font-semibold hover:bg-red-700">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                Aucune campagne publicitaire pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
