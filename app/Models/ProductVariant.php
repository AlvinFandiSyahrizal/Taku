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
        'sort',
    ];

    protected $casts = [
        'height'   => 'decimal:2',
        'diameter' => 'decimal:2',
        'price'    => 'integer',
        'stock'    => 'integer',
        'sort'     => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    //  Label ringkas untuk ditampilkan di cart & order history.
    //  Contoh: "Tinggi 30 cm · Ø 15 cm"

    public function getLabel(): string
    {
        $parts = [];

        if ($this->height) {
            $h    = $this->height == (int) $this->height ? (int) $this->height : $this->height;
            $parts[] = 'Tinggi ' . $h . ' ' . $this->height_unit;
        }

        if ($this->diameter) {
            $d    = $this->diameter == (int) $this->diameter ? (int) $this->diameter : $this->diameter;
            $parts[] = 'Ø ' . $d . ' ' . $this->diameter_unit;
        }

        return implode(' · ', $parts) ?: 'Standar';
    }


    //  Apakah stok masih tersedia?

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }


    //  Harga format rupiah

    public function getPriceFormatted(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
