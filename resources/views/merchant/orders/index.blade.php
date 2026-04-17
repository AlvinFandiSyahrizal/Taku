@extends('merchant.layouts.sidebar')
@section('page-title', 'Pesanan Masuk')
@section('content')

<style>
.tab-bar { display: flex; gap: 4px; background: white; border-radius: 12px; border: 0.5px solid rgba(11,42,74,0.08); padding: 6px; margin-bottom: 24px; width: fit-content; }
.tab-btn { padding: 8px 18px; border-radius: 8px; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; text-decoration: none; color: rgba(11,42,74,0.45); transition: all 0.2s; display: flex; align-items: center; gap: 7px; }
.tab-btn:hover { color: #0b2a4a; background: rgba(11,42,74,0.04); }
.tab-btn.active { background: #0b2a4a; color: #f0ebe0; }
.tab-count { font-size: 10px; background: rgba(255,255,255,0.2); padding: 1px 6px; border-radius: 100px; }
.tab-btn:not(.active) .tab-count { background: rgba(11,42,74,0.07); color: rgba(11,42,74,0.5); }
.tab-btn.active.has-pending .tab-count { background: #c9a96e; color: #0b2a4a; }

.orders-table { width: 100%; border-collapse: collapse; background: white; border-radius: 14px; overflow: hidden; border: 0.5px solid rgba(11,42,74,0.08); }
.orders-table th { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); font-weight: 400; padding: 14px 20px; text-align: left; border-bottom: 0.5px solid rgba(11,42,74,0.06); }
.orders-table td { padding: 14px 20px; font-size: 13px; color: #0b2a4a; border-bottom: 0.5px solid rgba(11,42,74,0.04); vertical-align: middle; }
.orders-table tr:last-child td { border-bottom: none; }
.orders-table tbody tr { transition: background 0.15s; }
.orders-table tbody tr:hover { background: rgba(11,42,74,0.02); }

.order-code { font-weight: 500; font-size: 13px; }
.order-customer { font-size: 13px; }
.order-phone { font-size: 11px; color: rgba(11,42,74,0.4); margin-top: 2px; }

.status-badge { display: inline-block; padding: 3px 10px; border-radius: 100px; font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 500; }

.btn-detail { font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: #0b2a4a; text-decoration: none; border: 0.5px solid rgba(11,42,74,0.15); border-radius: 6px; padding: 6px 14px; transition: all 0.2s; }
.btn-detail:hover { border-color: #0b2a4a; }

.empty-state { text-align: center; padding: 60px 40px; }
.empty-title { font-family: 'Cormorant Garamond', serif; font-size: 22px; color: rgba(11,42,74,0.25); margin-bottom: 8px; }
.empty-sub { font-size: 13px; color: rgba(11,42,74,0.3); }
</style>

@php
$tabs = [
    'pending'   => ['label' => 'Pending',    'color' => '#e67e22'],
    'confirmed' => ['label' => 'Dikonfirmasi','color' => '#2980b9'],
    'shipped'   => ['label' => 'Dikirim',    'color' => '#8e44ad'],
    'completed' => ['label' => 'Selesai',    'color' => '#27ae60'],
    'cancelled' => ['label' => 'Dibatalkan', 'color' => '#c0392b'],
];
@endphp

<div class="tab-bar">
    @foreach($tabs as $key => $tab)
    <a href="{{ route('merchant.orders.index', ['status' => $key]) }}"
       class="tab-btn {{ $status === $key ? 'active' : '' }} {{ $key === 'pending' && $counts['pending'] > 0 ? 'has-pending' : '' }}">
        {{ $tab['label'] }}
        @if($counts[$key] > 0)
        <span class="tab-count">{{ $counts[$key] }}</span>
        @endif
    </a>
    @endforeach
</div>

<table class="orders-table">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Items</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        @php $s = $order->getStatusLabel(); @endphp
        <tr>
            <td class="order-code">{{ $order->order_code }}</td>
            <td>
                <p class="order-customer">{{ $order->name }}</p>
                <p class="order-phone">{{ $order->phone }}</p>
            </td>
            <td style="color:rgba(11,42,74,0.5);">{{ $order->items->count() }} item</td>
            <td style="font-weight:500;">{{ $order->getTotalFormatted() }}</td>
            <td style="color:rgba(11,42,74,0.45);">{{ $order->created_at->format('d M Y, H:i') }}</td>
            <td>
                <a href="{{ route('merchant.orders.show', $order) }}" class="btn-detail">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">
                <div class="empty-state">
                    <p class="empty-title">Tidak ada pesanan</p>
                    <p class="empty-sub">Belum ada pesanan dengan status "{{ $tabs[$status]['label'] }}"</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
