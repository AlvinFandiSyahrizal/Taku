<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'is_active', 'sort', 'parent_id', 'store_id'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $base = Str::slug($cat->name);
                // Tambah store_id ke slug supaya tidak tabrakan antara merchant
                $cat->slug = $cat->store_id
                    ? $base . '-s' . $cat->store_id
                    : $base;
            }
        });
    }

    // ── Relasi ────────────────────────────────────────────────

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    /** Hanya kategori utama (bukan sub) */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /** Hanya yang aktif */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Kategori global (milik admin) */
    public function scopeGlobal($query)
    {
        return $query->whereNull('store_id');
    }

    /** Kategori milik store tertentu */
    public function scopeForStore($query, int $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    // ── Helpers ───────────────────────────────────────────────

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function isGlobal(): bool
    {
        return $this->store_id === null;
    }
}
