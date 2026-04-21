<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBanner extends Model
{
    protected $fillable = [
        'store_id', 'title', 'subtitle', 'image',
        'link', 'button_text', 'is_active', 'auto_slide', 'sort', 'position'
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'auto_slide' => 'boolean',
    ];

    // position values: 'top', 'after_sections', 'bottom'
    const POSITION_TOP            = 'top';
    const POSITION_AFTER_SECTIONS = 'after_sections';
    const POSITION_BOTTOM         = 'bottom';

    public static function positions(): array
    {
        return [
            self::POSITION_TOP            => 'Atas (Hero Banner)',
            self::POSITION_AFTER_SECTIONS => 'Setelah Sections',
            self::POSITION_BOTTOM         => 'Bawah (Setelah Katalog)',
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopePosition($q, string $position)
    {
        return $q->where('position', $position);
    }
}