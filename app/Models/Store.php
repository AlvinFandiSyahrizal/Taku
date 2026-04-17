<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'phone', 'logo',
        'status', 'reject_reason', 'approved_at',
        'rejection_count', 'rejected_at', 'resubmitted_at',
        'city', 'agreed_terms',
    ];

    protected $casts = [
        'approved_at'     => 'datetime',
        'rejected_at'     => 'datetime',
        'resubmitted_at'  => 'datetime',
        'agreed_terms'    => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($store) {
            if (empty($store->slug)) {
                $base = \Illuminate\Support\Str::slug($store->name);
                $slug = $base;
                $i    = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $store->slug = $slug;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function isPending()  { return $this->status === 'pending'; }
    public function isActive()   { return $this->status === 'active'; }
    public function isBanned()   { return $this->status === 'banned'; }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => ['label' => 'Menunggu Approval', 'color' => '#e67e22'],
            'active'  => ['label' => 'Aktif',             'color' => '#27ae60'],
            'banned'  => ['label' => 'Dibanned',          'color' => '#c0392b'],
            default   => ['label' => $this->status,       'color' => '#888'],
        };
    }

    public function banners()
    {
        return $this->hasMany(StoreBanner::class)->orderBy('sort');
    }

    public function sections()
    {
        return $this->hasMany(StoreSection::class)->orderBy('sort');
    }

    public function canResubmit(): bool
    {
        if ($this->status !== 'pending') return false;
        if (!$this->reject_reason) return false;
        if ($this->rejected_at && $this->rejected_at->diffInDays(now()) < 7) return false;
        return true;
    }

    public function isRejectionBanned(): bool
    {
        return false;
    }

    public function daysUntilResubmit(): int
    {
        if (!$this->rejected_at) return 0;
        $diff = 7 - $this->rejected_at->diffInDays(now());
        return max(0, $diff);
    }

}