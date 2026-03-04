<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variation_id',
        'selected_attributes',
        'qty',
        'unit_price',
    ];

    protected $casts = [
        'selected_attributes' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    public function getDisplayAttributesAttribute(): array
    {
        $attributes = $this->selected_attributes;

        if (!is_array($attributes) || empty($attributes)) {
            return [];
        }

        $labels = [];
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', array_filter(array_map('strval', $value)));
            } else {
                $value = trim((string) $value);
            }

            if ($value === '') {
                continue;
            }

            $labels[] = Str::title(str_replace('_', ' ', (string) $key)) . ': ' . $value;
        }

        return $labels;
    }
}
