<?php

namespace App\View\Components;

use App\Services\AdCampaignService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdSlot extends Component
{
    public function __construct(
        public string $placement,
        public int $limit = 1
    ) {
    }

    public function render(): View
    {
        $campaigns = app(AdCampaignService::class)->campaignsForPlacement($this->placement, $this->limit);

        return view('components.ad-slot', [
            'campaigns' => $campaigns,
            'placement' => $this->placement,
        ]);
    }
}
