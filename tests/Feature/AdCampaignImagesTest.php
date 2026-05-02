<?php

use App\Models\AdCampaign;
use App\Models\AdCampaignImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function fakePngUpload(string $name): UploadedFile
{
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4////fwAJ+wP9KobjigAAAABJRU5ErkJggg==');

    return UploadedFile::fake()->createWithContent($name, $png);
}

it('stores multiple images for an ad campaign and keeps the first one as primary', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.ad-campaigns.store'), [
        'title' => 'Campagne multi-images',
        'placement' => AdCampaign::PLACEMENT_HOME_HERO,
        'audience' => AdCampaign::AUDIENCE_ALL,
        'background_color' => '#0f172a',
        'text_color' => '#ffffff',
        'is_active' => '1',
        'images' => [
            fakePngUpload('ad-1.png'),
            fakePngUpload('ad-2.png'),
        ],
    ]);

    $campaign = AdCampaign::query()->with('images')->latest('id')->firstOrFail();

    $response->assertRedirect(route('admin.ad-campaigns.index'));

    expect($campaign->images)->toHaveCount(2)
        ->and($campaign->image_path)->toBe($campaign->images->sortBy('sort_order')->first()->path);

    foreach ($campaign->images as $image) {
        Storage::disk('public')->assertExists($image->path);
    }
});

it('can change the primary image of an ad campaign', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $campaign = AdCampaign::create([
        'title' => 'Campagne galerie',
        'placement' => AdCampaign::PLACEMENT_HOME_HERO,
        'audience' => AdCampaign::AUDIENCE_ALL,
        'background_color' => '#0f172a',
        'text_color' => '#ffffff',
        'is_active' => true,
        'image_path' => 'ad_campaigns/cover-a.png',
        'created_by' => $admin->id,
        'updated_by' => $admin->id,
    ]);

    $firstImage = $campaign->images()->create([
        'path' => 'ad_campaigns/cover-a.png',
        'sort_order' => 0,
    ]);

    $secondImage = $campaign->images()->create([
        'path' => 'ad_campaigns/cover-b.png',
        'sort_order' => 1,
    ]);

    $response = $this->actingAs($admin)->post(
        route('admin.ad-campaigns.images.primary', ['adCampaign' => $campaign->id, 'image' => $secondImage->id])
    );

    $response->assertRedirect();

    $campaign->refresh();
    $orderedImages = $campaign->images()->orderBy('sort_order')->get();

    expect($campaign->image_path)->toBe($secondImage->path)
        ->and($orderedImages->first()->id)->toBe($secondImage->id)
        ->and($orderedImages->first()->sort_order)->toBe(0)
        ->and($orderedImages->last()->id)->toBe($firstImage->id);
});

it('uses every ad image in the home slideshow placement', function () {
    $campaign = AdCampaign::create([
        'title' => 'Diaporama multi',
        'placement' => AdCampaign::PLACEMENT_HOME_SLIDESHOW,
        'audience' => AdCampaign::AUDIENCE_ALL,
        'background_color' => '#0f172a',
        'text_color' => '#ffffff',
        'is_active' => true,
        'image_path' => 'ad_campaigns/slide-1.png',
    ]);

    $campaign->images()->createMany([
        ['path' => 'ad_campaigns/slide-1.png', 'sort_order' => 0],
        ['path' => 'ad_campaigns/slide-2.png', 'sort_order' => 1],
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('ad_campaigns/slide-1.png')
        ->assertSee('ad_campaigns/slide-2.png');
});
