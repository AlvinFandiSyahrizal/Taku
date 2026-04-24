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
        'height',
        'height_unit',
        'diameter',
        'diameter_unit',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'price'            => 'integer',
        'is_featured'      => 'boolean',
        'stock'            => 'integer',
        'discount_percent' => 'integer',
        'height'           => 'decimal:2',
        'diameter'         => 'decimal:2',
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

    // ── Relasi ────────────────────────────────────────────────

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ── Helpers ───────────────────────────────────────────────

    public function isMerchantProduct(): bool
    {
        return $this->store_id !== null;
    }

    public function hasVariants(): bool
    {
        if ($this->relationLoaded('variants')) {
            return $this->variants->isNotEmpty();
        }
        return $this->variants()->exists();
    }

    public function getDesc($locale = 'id')
    {
        return $locale === 'en' ? $this->desc_en : $this->desc_id;
    }

    public function getDetail($locale = 'id')
    {
        return $locale === 'en' ? $this->detail_en : $this->detail_id;
    }

    public function getPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isInStock(): bool
    {
        if ($this->hasVariants()) {
            return $this->variants->sum('stock') > 0;
        }
        return $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        if ($this->hasVariants()) {
            $total = $this->variants->sum('stock');
            return $total > 0 && $total <= 5;
        }
        return $this->stock > 0 && $this->stock <= 5;
    }

    // ── Harga produk tanpa variasi ─────────────────────────────

    public function getFinalPrice(): int
    {
        if ($this->discount_percent > 0) {
            return (int) round($this->price * (1 - $this->discount_percent / 100));
        }
        return $this->price;
    }

    public function getFinalPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->getFinalPrice(), 0, ',', '.');
    }

    public function hasDiscount(): bool
    {
        return $this->discount_percent > 0;
    }

    /**
     * Harga terendah dari semua variant (pakai final price setelah diskon variant).
     * Dipakai di listing produk: "Mulai dari Rp X"
     */
    public function getMinVariantPrice(): int
    {
        if ($this->hasVariants()) {
            // Pakai final price tiap variant (sudah dipotong diskon per variant)
            return (int) $this->variants->min(fn($v) => $v->getFinalPrice());
        }
        return $this->getFinalPrice();
    }

    public function getMinVariantPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->getMinVariantPrice(), 0, ',', '.');
    }

    // ── Ukuran tunggal ─────────────────────────────────────────

    public function getHeightLabel(): ?string
    {
        if (!$this->height) return null;
        $val = $this->height == (int) $this->height ? (int) $this->height : $this->height;
        return $val . ' ' . ($this->height_unit ?? 'cm');
    }

    public function getDiameterLabel(): ?string
    {
        if (!$this->diameter) return null;
        $val = $this->diameter == (int) $this->diameter ? (int) $this->diameter : $this->diameter;
        return $val . ' ' . ($this->diameter_unit ?? 'cm');
    }
}
