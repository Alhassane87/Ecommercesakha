<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdCampaign extends Model
{
    use HasFactory;

    public const AUDIENCE_ALL = 'all';
    public const AUDIENCE_GUESTS = 'guests';
    public const AUDIENCE_AUTHENTICATED = 'authenticated';

    public const PLACEMENT_GLOBAL_TOP = 'global_top';
    public const PLACEMENT_GLOBAL_BOTTOM = 'global_bottom';
    public const PLACEMENT_HOME_HERO = 'home_hero';
    public const PLACEMENT_HOME_SLIDESHOW = 'home_slideshow';
    public const PLACEMENT_HOME_MID = 'home_mid';
    public const PLACEMENT_SHOP_TOP = 'shop_top';
    public const PLACEMENT_SHOP_BOTTOM = 'shop_bottom';

    protected $fillable = [
        'title',
        'placement',
        'audience',
        'target_url',
        'button_text',
        'description',
        'image_path',
        'background_color',
        'text_color',
        'open_in_new_tab',
        'is_active',
        'priority',
        'max_impressions_per_session',
        'starts_at',
        'ends_at',
        'impressions',
        'clicks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'max_impressions_per_session' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'impressions' => 'integer',
        'clicks' => 'integer',
    ];

    public static function placementOptions(): array
    {
        return [
            self::PLACEMENT_GLOBAL_TOP => 'Global - haut de page',
            self::PLACEMENT_GLOBAL_BOTTOM => 'Global - bas de page',
            self::PLACEMENT_HOME_HERO => 'Accueil - apres hero',
            self::PLACEMENT_HOME_SLIDESHOW => 'Accueil - diaporama',
            self::PLACEMENT_HOME_MID => 'Accueil - milieu',
            self::PLACEMENT_SHOP_TOP => 'Boutique - haut',
            self::PLACEMENT_SHOP_BOTTOM => 'Boutique - bas',
        ];
    }

    public static function audienceOptions(): array
    {
        return [
            self::AUDIENCE_ALL => 'Tout le monde',
            self::AUDIENCE_GUESTS => 'Visiteurs non connectes',
            self::AUDIENCE_AUTHENTICATED => 'Utilisateurs connectes',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeForAudience($query, bool $isAuthenticated)
    {
        $audience = $isAuthenticated ? self::AUDIENCE_AUTHENTICATED : self::AUDIENCE_GUESTS;

        return $query->where(function ($q) use ($audience) {
            $q->where('audience', self::AUDIENCE_ALL)
                ->orWhere('audience', $audience);
        });
    }

    public function scopeForPlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }

    public function getCtrAttribute(): float
    {
        if ((int) $this->impressions <= 0) {
            return 0.0;
        }

        return round(((int) $this->clicks / (int) $this->impressions) * 100, 2);
    }
}
