<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 
        'product_id', 
        'product_variation_id',
        'selected_attributes',
        'qty', 
        'unit_price'
    ];

    protected $casts = [
        'selected_attributes' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function getProductNameAttribute()
    {
        $name = $this->product->name;
        if ($this->variation && $this->variation->attributes) {
            $attrs = [];
            foreach ($this->variation->attributes as $key => $value) {
                $attrs[] = ucfirst($key) . ': ' . $value;
            }
            if (!empty($attrs)) {
                $name .= ' (' . implode(', ', $attrs) . ')';
            }
        }
        return $name;
    }
}
