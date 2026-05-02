<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'description', 
        'usage_video_path',
        'sku', 
        'price', 
        'stock',
        'is_active',
        'discount_price',
        'promotion_id',
        'attributes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function promotion()
    {
        return $this->hasOne(Promotion::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function activeVariations()
    {
        return $this->hasMany(ProductVariation::class)->where('is_active', true);
    }

    // MÉTHODES POUR LES IMAGES - AJOUT
    public function getMainImage()
    {
        return $this->images()->first();
    }

    public function getMainImageUrl()
    {
        $mainImage = $this->getMainImage();
        
        if (!$mainImage) {
            return null;
        }

        return Storage::url($mainImage->path);
    }

    public function getUsageVideoUrl(): ?string
    {
        if (empty($this->usage_video_path)) {
            return null;
        }

        return Storage::url($this->usage_video_path);
    }

    public function hasUsageVideo(): bool
    {
        return !empty($this->usage_video_path);
    }

    public function hasImages()
    {
        return $this->images()->exists();
    }

    // MÉTHODES POUR LES VARIANTES - AJOUT
    public function hasVariants()
    {
        return $this->variations()->exists();
    }

    public function hasCategoryAttributes()
    {
        return $this->category && $this->category->attributes()->exists();
    }

    public function getCategoryAttributes()
    {
        if (!$this->category) {
            return collect();
        }
        return $this->category->attributes()->with('values')->get();
    }

    public function getVariationByAttributes(array $attributes)
    {
        return $this->variations()
            ->where('attributes', json_encode($attributes))
            ->first();
    }

    // MÉTHODES POUR LE STOCK - AJOUT
    public function inStock()
    {
        return $this->stock > 0;
    }

    public function isLowStock()
    {
        return $this->stock > 0 && $this->stock <= 10;
    }

    public function getStockStatus()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusText()
    {
        $status = $this->getStockStatus();
        
        return match($status) {
            'out_of_stock' => 'Rupture de stock',
            'low_stock' => 'Stock faible',
            'in_stock' => 'En stock',
            default => 'Indisponible'
        };
    }

    // MÉTHODES POUR LES PRIX - AJOUT
    public function getFormattedPrice()
    {
        return number_format($this->price, 2, ',', ' ') . ' fcfa';
    }

    public function getFormattedTotal($quantity = 1)
    {
        return number_format($this->getFinalPrice() * $quantity, 2, ',', ' ') . ' fcfa';
    }

    public function getFinalPrice($quantity = 1)
    {
        // Vérifier si une promotion active existe
        $promotion = $this->getActivePromotion();
        
        if ($promotion) {
            return $promotion->getDiscountedPrice($this->price, $quantity);
        }

        // Sinon utiliser discount_price si défini
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return $this->discount_price;
        }

        return $this->price;
    }

    public function getActivePromotion()
    {
        // Promotion spécifique au produit
        if ($this->promotion && $this->promotion->isActive()) {
            return $this->promotion;
        }

        // Promotion sur la catégorie
        if ($this->category) {
            $categoryPromo = Promotion::where('category_id', $this->category_id)
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
                })
                ->first();
            
            if ($categoryPromo) {
                return $categoryPromo;
            }
        }

        return null;
    }

    public function getDiscountPercentage()
    {
        if ((float) $this->price <= 0) {
            return 0;
        }

        $finalPrice = $this->getFinalPrice();
        if ($finalPrice >= $this->price) {
            return 0;
        }
        return round((($this->price - $finalPrice) / $this->price) * 100);
    }

    // MÉTHODES UTILITAIRES - AJOUT
    public function canBeAddedToCart($quantity = 1)
    {
        return $this->is_active && $this->inStock() && $this->stock >= $quantity;
    }

    public function getExcerpt($length = 150)
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->description), $length);
    }

    // SCOPES UTILES - AJOUT
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)->latest();
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId)->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0)->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)
                    ->where('stock', '<=', 10)
                    ->where('is_active', true);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('sku', 'like', "%{$searchTerm}%");
        })->where('is_active', true);
    }

    // ACCESSORS - AJOUT
    public function getDisplayPriceAttribute()
    {
        return $this->getFormattedPrice();
    }

    public function getIsInStockAttribute()
    {
        return $this->inStock();
    }

    public function getHasImagesAttribute()
    {
        return $this->hasImages();
    }

    // BOOT METHOD - AJOUT (optionnel)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PROD-' . strtoupper(\Illuminate\Support\Str::random(8));
            }
        });
    }
}
