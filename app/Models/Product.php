<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'slug',
        'desc_id',
        'desc_en',
        'detail_id',
        'detail_en',
        'price',
        'image',
        'is_active',
        'category_id',
        'stock',
        'is_featured',
        'discount_percent',

    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'integer',
        'is_featured' => 'boolean',
        'stock'       => 'integer',
        'discount_percent' => 'integer',

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function isMerchantProduct()
    {
        return $this->store_id !== null;
    }

    public function getDesc($locale = 'id')
    {
        return $locale === 'en' ? $this->desc_en : $this->desc_id;
    }

    public function getDetail($locale = 'id')
    {
        return $locale === 'en' ? $this->detail_en : $this->detail_id;
    }

    public function getPriceFormatted()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isInStock(): bool {
        return $this->stock > 0;

        }

    public function isLowStock(): bool {
        return $this->stock > 0 && $this->stock <= 5;

        }

        public function getFinalPrice(): int {
            if ($this->discount_percent > 0) {
                return (int) round($this->price * (1 - $this->discount_percent / 100));
            }
            return $this->price;
        }

        public function getFinalPriceFormatted(): string {
            return 'Rp ' . number_format($this->getFinalPrice(), 0, ',', '.');
        }

        public function hasDiscount(): bool {
            return $this->discount_percent > 0;
        }

}
