<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'height',
        'height_unit',
        'diameter',
        'diameter_unit',
        'price',
        'stock',
        'discount_percent',
        'sort',
    ];

    protected $casts = [
        'height'           => 'decimal:2',
        'diameter'         => 'decimal:2',
        'price'            => 'integer',
        'stock'            => 'integer',
        'discount_percent' => 'integer',
        'sort'             => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Label ringkas: "Tinggi 30 cm · Ø 15 cm"
     */
    public function getLabel(): string
    {
        $parts = [];
        if ($this->height) {
            $h       = $this->height == (int) $this->height ? (int) $this->height : $this->height;
            $parts[] = 'Tinggi ' . $h . ' ' . $this->height_unit;
        }
        if ($this->diameter) {
            $d       = $this->diameter == (int) $this->diameter ? (int) $this->diameter : $this->diameter;
            $parts[] = 'Ø ' . $d . ' ' . $this->diameter_unit;
        }
        return implode(' · ', $parts) ?: 'Standar';
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function hasDiscount(): bool
    {
        return $this->discount_percent > 0;
    }

    public function getFinalPrice(): int
    {
        if ($this->discount_percent > 0) {
            return (int) round($this->price * (1 - $this->discount_percent / 100));
        }
        return $this->price;
    }

    public function getPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFinalPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->getFinalPrice(), 0, ',', '.');
    }
}
