<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model {
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'button_text',
        'is_active',
        'auto_slide',
        'sort'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_slide' => 'boolean'
    ];

    public function scopeActive($q) { return $q->where('is_active', true); }
}
