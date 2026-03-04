<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'category_id',
        'product_id',
        'starts_at',
        'ends_at',
        'is_active',
        'min_quantity'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($price, $quantity = 1)
    {
        if (!$this->isActive()) {
            return 0;
        }

        if ($this->min_quantity && $quantity < $this->min_quantity) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($price * $this->value) / 100;
        } else {
            return min($this->value, $price); // Ne pas dépasser le prix
        }
    }

    public function getDiscountedPrice($price, $quantity = 1)
    {
        $discount = $this->calculateDiscount($price, $quantity);
        return max(0, $price - $discount);
    }
}

