<?php

use App\Models\AdCampaign;
use Carbon\Carbon;
use Tests\TestCase;

uses(TestCase::class);

it('marks campaigns as running when active and inside the diffusion window', function () {
    Carbon::setTestNow('2026-05-01 17:38:51');

    $campaign = new AdCampaign([
        'is_active' => true,
        'starts_at' => '2026-05-01 17:30:00',
        'ends_at' => '2026-05-01 18:30:00',
    ]);

    expect($campaign->diffusionStatus())->toBe('running')
        ->and($campaign->diffusionStatusLabel())->toBe('En diffusion');
});

it('marks campaigns as scheduled when the start date is in the future', function () {
    Carbon::setTestNow('2026-05-01 17:38:51');

    $campaign = new AdCampaign([
        'is_active' => true,
        'starts_at' => '2026-05-01 18:39:00',
        'ends_at' => '2026-05-10 18:40:00',
    ]);

    expect($campaign->diffusionStatus())->toBe('scheduled')
        ->and($campaign->diffusionStatusLabel())->toBe('Programmee');
});

it('marks campaigns as expired when the end date is in the past', function () {
    Carbon::setTestNow('2026-05-01 17:38:51');

    $campaign = new AdCampaign([
        'is_active' => true,
        'starts_at' => '2026-04-01 17:30:00',
        'ends_at' => '2026-05-01 17:00:00',
    ]);

    expect($campaign->diffusionStatus())->toBe('expired')
        ->and($campaign->diffusionStatusLabel())->toBe('Expiree');
});

it('marks campaigns as inactive when disabled even if dates are valid', function () {
    Carbon::setTestNow('2026-05-01 17:38:51');

    $campaign = new AdCampaign([
        'is_active' => false,
        'starts_at' => '2026-05-01 17:30:00',
        'ends_at' => '2026-05-01 18:30:00',
    ]);

    expect($campaign->diffusionStatus())->toBe('inactive')
        ->and($campaign->diffusionStatusLabel())->toBe('Desactivee');
});

afterEach(function () {
    Carbon::setTestNow();
});
