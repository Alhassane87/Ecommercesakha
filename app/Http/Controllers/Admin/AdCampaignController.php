<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdCampaign;
use App\Models\AdCampaignImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdCampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = AdCampaign::query()
            ->withCount('images')
            ->latest()
            ->paginate(20);

        return view('admin.ad-campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('admin.ad-campaigns.create', [
            'placementOptions' => AdCampaign::placementOptions(),
            'audienceOptions' => AdCampaign::audienceOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateCampaign($request);
        $this->normalizeCampaignPayload($data);
        $storedPaths = $this->storeUploadedImageFiles($request);

        $data['is_active'] = $request->boolean('is_active');
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        DB::transaction(function () use (&$data, $storedPaths): void {
            if (!empty($storedPaths)) {
                $data['image_path'] = $storedPaths[0];
            }

            $campaign = AdCampaign::create($data);
            $this->attachStoredImages($campaign, $storedPaths);
            $this->syncCampaignPrimaryImage($campaign);
        });

        return redirect()
            ->route('admin.ad-campaigns.index')
            ->with('success', 'Campagne publicitaire creee avec succes.');
    }

    public function edit(AdCampaign $adCampaign): View
    {
        $adCampaign->load('images');

        return view('admin.ad-campaigns.edit', [
            'campaign' => $adCampaign,
            'placementOptions' => AdCampaign::placementOptions(),
            'audienceOptions' => AdCampaign::audienceOptions(),
        ]);
    }

    public function update(Request $request, AdCampaign $adCampaign): RedirectResponse
    {
        $data = $this->validateCampaign($request);
        $this->normalizeCampaignPayload($data);
        $storedPaths = $this->storeUploadedImageFiles($request);

        $data['is_active'] = $request->boolean('is_active');
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['updated_by'] = auth()->id();

        DB::transaction(function () use ($adCampaign, $data, $storedPaths): void {
            $adCampaign->update($data);
            $this->attachStoredImages($adCampaign, $storedPaths);
            $this->syncCampaignPrimaryImage($adCampaign);
        });

        return redirect()
            ->route('admin.ad-campaigns.index')
            ->with('success', 'Campagne publicitaire mise a jour avec succes.');
    }

    public function destroy(AdCampaign $adCampaign): RedirectResponse
    {
        $adCampaign->load('images');
        $this->deleteCampaignImagesFromStorage($adCampaign);

        $adCampaign->delete();

        return redirect()
            ->route('admin.ad-campaigns.index')
            ->with('success', 'Campagne publicitaire supprimee.');
    }

    public function destroyImage(AdCampaign $adCampaign, AdCampaignImage $image): RedirectResponse
    {
        if ($image->ad_campaign_id !== $adCampaign->id) {
            abort(404);
        }

        if ($image->path) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();
        $this->syncCampaignPrimaryImage($adCampaign);

        return back()->with('success', 'Image publicitaire supprimee.');
    }

    public function setPrimaryImage(AdCampaign $adCampaign, AdCampaignImage $image): RedirectResponse
    {
        if ($image->ad_campaign_id !== $adCampaign->id) {
            abort(404);
        }

        DB::transaction(function () use ($adCampaign, $image): void {
            $selectedImageId = $image->id;

            $orderedImages = $adCampaign->images()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->partition(fn (AdCampaignImage $campaignImage) => $campaignImage->id === $selectedImageId);

            $orderedImages = $orderedImages[0]->concat($orderedImages[1])->values();

            foreach ($orderedImages as $index => $campaignImage) {
                if ((int) $campaignImage->sort_order !== $index) {
                    $campaignImage->update(['sort_order' => $index]);
                }
            }

            $adCampaign->forceFill(['image_path' => $image->path])->save();
        });

        return back()->with('success', 'Image principale mise a jour.');
    }

    private function validateCampaign(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'placement' => ['required', 'in:' . implode(',', array_keys(AdCampaign::placementOptions()))],
            'audience' => ['required', 'in:' . implode(',', array_keys(AdCampaign::audienceOptions()))],
            'target_url' => [
                'nullable',
                'string',
                'max:2048',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || trim((string) $value) === '') {
                        return;
                    }

                    $target = trim((string) $value);
                    $isAbsolute = filter_var($target, FILTER_VALIDATE_URL) !== false;
                    $isRelative = str_starts_with($target, '/');

                    if (!$isAbsolute && !$isRelative) {
                        $fail("Le champ {$attribute} doit etre une URL complete (https://...) ou un chemin interne (/page).");
                    }
                },
            ],
            'button_text' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:1200'],
            'image' => ['nullable', 'image', 'max:4096'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'max_impressions_per_session' => ['nullable', 'integer', 'min:1', 'max:100'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ], [
            'background_color.regex' => 'La couleur de fond doit etre au format hex (#RRGGBB).',
            'text_color.regex' => 'La couleur du texte doit etre au format hex (#RRGGBB).',
        ]);
    }

    private function normalizeCampaignPayload(array &$data): void
    {
        $data['priority'] = isset($data['priority']) ? (int) $data['priority'] : 100;
        $data['target_url'] = isset($data['target_url']) && trim((string) $data['target_url']) !== ''
            ? trim((string) $data['target_url'])
            : null;
    }

    private function storeUploadedImageFiles(Request $request): array
    {
        $storedPaths = [];

        foreach ($this->collectUploadedImageFiles($request) as $file) {
            $storedPaths[] = $file->store('ad_campaigns', 'public');
        }

        return $storedPaths;
    }

    private function collectUploadedImageFiles(Request $request): array
    {
        $files = [];

        if ($request->hasFile('image')) {
            $files[] = $request->file('image');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file !== null) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    private function attachStoredImages(AdCampaign $campaign, array $storedPaths): void
    {
        if (empty($storedPaths)) {
            return;
        }

        $sortOrder = ((int) $campaign->images()->max('sort_order')) + 1;

        foreach ($storedPaths as $path) {
            $campaign->images()->create([
                'path' => $path,
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    private function syncCampaignPrimaryImage(AdCampaign $campaign): void
    {
        $orderedImages = $campaign->images()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->values();

        foreach ($orderedImages as $index => $image) {
            if ((int) $image->sort_order !== $index) {
                $image->update(['sort_order' => $index]);
            }
        }

        $primaryPath = $orderedImages->first()?->path;

        if ($campaign->image_path !== $primaryPath) {
            $campaign->forceFill(['image_path' => $primaryPath])->save();
        }
    }

    private function deleteCampaignImagesFromStorage(AdCampaign $campaign): void
    {
        $paths = $campaign->galleryImagePaths();

        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}
