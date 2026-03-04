<?php

namespace App\Http\Controllers;

use App\Models\AdCampaign;
use App\Services\AdCampaignService;
use Illuminate\Http\RedirectResponse;

class AdController extends Controller
{
    public function click(AdCampaign $adCampaign, AdCampaignService $service): RedirectResponse
    {
        $service->registerClick($adCampaign);

        $target = trim((string) $adCampaign->target_url);
        if ($target === '') {
            return redirect()->route('home');
        }

        if (preg_match('/^https?:\/\//i', $target) === 1) {
            return redirect()->away($target);
        }

        if (str_starts_with($target, '/')) {
            return redirect($target);
        }

        return redirect('/' . ltrim($target, '/'));
    }
}
