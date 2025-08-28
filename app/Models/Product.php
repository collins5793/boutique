<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock_quantity',
        'barcode',
        'image',
        'gallery',
        'status'
    ];

    protected $casts = [
        'gallery' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

}