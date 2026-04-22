<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'variant_label',
        'price',
        'qty',
        'subtotal',
    ];

    protected $casts = [
        'price'    => 'integer',
        'qty'      => 'integer',
        'subtotal' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalFormatted(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
