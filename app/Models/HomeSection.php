<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model {
    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'rows',
        'auto_slide',
        'is_active',
        'sort'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'auto_slide' => 'boolean'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'home_section_products')
            ->withPivot('sort')->orderBy('home_section_products.sort');
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
