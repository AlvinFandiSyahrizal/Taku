<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSection extends Model {
    protected $fillable = [
        'store_id','title','subtitle','rows','auto_slide','is_active','sort'
    ];
    protected $casts = ['is_active' => 'boolean', 'auto_slide' => 'boolean'];

    public function store() { return $this->belongsTo(Store::class); }

    public function products() {
        return $this->belongsToMany(Product::class, 'store_section_products')
            ->withPivot('sort')
            ->orderBy('store_section_products.sort');
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}