<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBanner extends Model {
    protected $fillable = [
        'store_id','title','subtitle','image',
        'link','button_text','is_active','auto_slide','sort'
    ];
    protected $casts = ['is_active' => 'boolean', 'auto_slide' => 'boolean'];

    public function store() { return $this->belongsTo(Store::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}