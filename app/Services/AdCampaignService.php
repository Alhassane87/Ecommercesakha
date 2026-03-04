<?php

namespace App\Services;

use App\Models\AdCampaign;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AdCampaignService
{
    private const SESSION_IMPRESSIONS_KEY = 'ad_campaign_impressions';

    public function campaignsForPlacement(string $placement, int $limit = 1): Collection
    {
        $safeLimit = max(1, $limit);

        $query = AdCampaign::query()
            ->forPlacement($placement)
            ->active()
            ->forAudience(Auth::check())
            ->orderBy('priority')
            ->orderByDesc('id');

        $campaigns = $query->get();
        if ($campaigns->isEmpty()) {
            return collect();
        }

        $request = request();
        $session = $request && method_exists($request, 'hasSession') && $request->hasSession()
            ? $request->session()
            : null;

        $sessionImpressions = $session ? $session->get(self::SESSION_IMPRESSIONS_KEY, []) : [];

        $visible = $campaigns->filter(function (AdCampaign $campaign) use ($sessionImpressions) {
            if ($campaign->max_impressions_per_session === null) {
                return true;
            }

            $count = (int) ($sessionImpressions[$campaign->id] ?? 0);
            return $count < (int) $campaign->max_impressions_per_session;
        })->take($safeLimit)->values();

        if ($visible->isEmpty()) {
            return $visible;
        }

        foreach ($visible as $campaign) {
            $campaign->increment('impressions');
            $sessionImpressions[$campaign->id] = (int) ($sessionImpressions[$campaign->id] ?? 0) + 1;
        }

        if ($session) {
            $session->put(self::SESSION_IMPRESSIONS_KEY, $sessionImpressions);
        }

        return $visible;
    }

    public function registerClick(AdCampaign $campaign): void
    {
        $campaign->increment('clicks');
    }
}
