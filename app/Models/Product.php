<?php

namespace App\Models;

use App\Support\ImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'category_id', 'brand_id', 'price', 'sale_price',
        'sale_from', 'sale_to', 'sku', 'stock_quantity',
        'thumbnail', 'images', 'weight', 'attributes',
        'is_featured', 'is_active', 'meta_title', 'meta_description',
        'views_count', 'sold_count',
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'sale_from' => 'datetime',
        'sale_to' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'sold_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPriceAttribute(): float
    {
        if ($this->sale_price && $this->isOnSale()) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function isOnSale(): bool
    {
        if (!$this->sale_price) return false;
        if ($this->sale_from && now()->lt($this->sale_from)) return false;
        if ($this->sale_to && now()->gt($this->sale_to)) return false;
        return true;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->isOnSale() || !$this->sale_price) return null;
        return (int) round((1 - $this->sale_price / $this->price) * 100);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function displayThumbnail(): string
    {
        return ImageUrl::resolve($this->attributes['thumbnail'] ?? null);
    }

    /** @return array<int, string> */
    public function galleryImages(): array
    {
        $paths = [];
        if ($this->attributes['thumbnail'] ?? null) {
            $paths[] = $this->attributes['thumbnail'];
        }
        if (is_array($this->images)) {
            foreach ($this->images as $img) {
                if ($img) {
                    $paths[] = $img;
                }
            }
        }

        return ImageUrl::resolveMany($paths);
    }

    public function getAvgRatingAttribute(): float
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->where(function ($q) {
                $q->whereNull('sale_from')->orWhere('sale_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('sale_to')->orWhere('sale_to', '>=', now());
            });
    }
}
