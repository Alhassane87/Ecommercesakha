<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 
        'name', 
        'slug',
        'icon',
        'description',
        'is_active' // AJOUT
    ];

    protected $casts = [
        'is_active' => 'boolean', // AJOUT
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // SCOPES UTILES - AJOUT
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithActiveProductsCount($query)
    {
        return $query->withCount(['products' => function($query) {
            $query->where('is_active', true);
        }]);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    public function attributes()
    {
        return $this->hasMany(CategoryAttribute::class)->orderBy('sort_order');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function activePromotions()
    {
        return $this->hasMany(Promotion::class)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
    }
}