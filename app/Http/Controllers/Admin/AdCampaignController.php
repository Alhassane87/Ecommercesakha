<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdCampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = AdCampaign::query()
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

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ad_campaigns', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        AdCampaign::create($data);

        return redirect()
            ->route('admin.ad-campaigns.index')
            ->with('success', 'Campagne publicitaire creee avec succes.');
    }

    public function edit(AdCampaign $adCampaign): View
    {
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

        if ($request->hasFile('image')) {
            if ($adCampaign->image_path) {
                Storage::disk('public')->delete($adCampaign->image_path);
            }
            $data['image_path'] = $request->file('image')->store('ad_campaigns', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $data['updated_by'] = auth()->id();

        $adCampaign->update($data);

        return redirect()
            ->route('admin.ad-campaigns.index')
            ->with('success', 'Campagne publicitaire mise a jour avec succes.');
    }

    public function destroy(AdCampaign $adCampaign): RedirectResponse
    {
        if ($adCampaign->image_path) {
            Storage::disk('public')->delete($adCampaign->image_path);
        }

        $adCampaign->delete();

        return redirect()
            ->route('admin.ad-campaigns.index')
            ->with('success', 'Campagne publicitaire supprimee.');
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
}
