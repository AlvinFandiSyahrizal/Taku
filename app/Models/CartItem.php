<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 
        'product_id', 
        'store_id', 
        'qty', 
        'is_selected'
        ];

    protected $casts = ['is_selected' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
