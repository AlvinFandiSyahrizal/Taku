<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'store_id',
        'order_code',
        'name',
        'phone',
        'address',
        'note',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'integer',
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getTotalFormatted(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getStatusLabel(): array
    {
        return match($this->status) {
            'pending'   => ['label' => 'Menunggu',     'color' => '#e67e22'],
            'confirmed' => ['label' => 'Dikonfirmasi', 'color' => '#2980b9'],
            'shipped'   => ['label' => 'Dikirim',      'color' => '#8e44ad'],
            'completed' => ['label' => 'Selesai',      'color' => '#27ae60'],
            'cancelled' => ['label' => 'Dibatalkan',   'color' => '#c0392b'],
            default     => ['label' => $this->status,  'color' => '#888'],
        };
    }

    public static function generateCode(): string
    {
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', today())->count() + 1;
        return 'TK-' . $date . '-' . str_pad($last, 3, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (!$order->order_code) {
                $order->order_code = self::generateCode();
            }
        });
    }
}