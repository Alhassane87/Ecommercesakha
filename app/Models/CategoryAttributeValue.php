<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_attribute_id',
        'value',
        'display_value',
        'color_code',
        'sort_order'
    ];

    public function attribute()
    {
        return $this->belongsTo(CategoryAttribute::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->display_value ?? $this->value;
    }
}

