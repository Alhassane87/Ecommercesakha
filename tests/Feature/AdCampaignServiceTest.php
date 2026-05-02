<?php

use App\Models\AdCampaign;
use App\Services\AdCampaignService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('keeps an ad placement populated when the only campaign reached its session cap', function () {
    $campaign = AdCampaign::create([
        'title' => 'PROMO',
        'placement' => AdCampaign::PLACEMENT_HOME_HERO,
        'audience' => AdCampaign::AUDIENCE_ALL,
        'is_active' => true,
        'priority' => 10,
        'max_impressions_per_session' => 5,
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addDay(),
    ]);

    $request = Request::create('/');
    $session = app('session')->driver();
    $session->start();
    $session->put('ad_campaign_impressions', [$campaign->id => 5]);
    $request->setLaravelSession($session);
    app()->instance('request', $request);

    $visible = app(AdCampaignService::class)->campaignsForPlacement(AdCampaign::PLACEMENT_HOME_HERO, 1);

    expect($visible)->toHaveCount(1)
        ->and($visible->first()->is($campaign))->toBeTrue()
        ->and($request->session()->get('ad_campaign_impressions.' . $campaign->id))->toBe(5);
});

it('still prefers campaigns that are below the session cap when available', function () {
    $cappedCampaign = AdCampaign::create([
        'title' => 'Campagne cappee',
        'placement' => AdCampaign::PLACEMENT_HOME_HERO,
        'audience' => AdCampaign::AUDIENCE_ALL,
        'is_active' => true,
        'priority' => 1,
        'max_impressions_per_session' => 5,
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addDay(),
    ]);

    $availableCampaign = AdCampaign::create([
        'title' => 'Campagne visible',
        'placement' => AdCampaign::PLACEMENT_HOME_HERO,
        'audience' => AdCampaign::AUDIENCE_ALL,
        'is_active' => true,
        'priority' => 2,
        'max_impressions_per_session' => 5,
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addDay(),
    ]);

    $request = Request::create('/');
    $session = app('session')->driver();
    $session->start();
    $session->put('ad_campaign_impressions', [
        $cappedCampaign->id => 5,
        $availableCampaign->id => 2,
    ]);
    $request->setLaravelSession($session);
    app()->instance('request', $request);

    $visible = app(AdCampaignService::class)->campaignsForPlacement(AdCampaign::PLACEMENT_HOME_HERO, 1);

    expect($visible)->toHaveCount(1)
        ->and($visible->first()->is($availableCampaign))->toBeTrue()
        ->and($request->session()->get('ad_campaign_impressions.' . $availableCampaign->id))->toBe(3);
});
