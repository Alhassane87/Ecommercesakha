<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'attributes',
        'price',
        'stock',
        'image_path',
        'is_active'
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        return $this->price ?? $this->product->price;
    }

    public function inStock()
    {
        return $this->stock > 0;
    }

    public function getAttributesDisplayAttribute()
    {
        $display = [];
        foreach ($this->attributes as $key => $value) {
            $display[] = ucfirst($key) . ': ' . $value;
        }
        return implode(', ', $display);
    }
}

