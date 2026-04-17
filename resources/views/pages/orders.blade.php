@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');

.orders-wrap { max-width: 780px; margin: 56px auto 80px; padding: 0 24px; font-family: 'DM Sans', sans-serif; }
.page-label { font-size: 10px; letter-spacing: 0.22em; text-transform: uppercase; color: #c9a96e; margin-bottom: 6px; }
.page-title { font-family: 'Cormorant Garamond', serif; font-weight: 400; font-size: 34px; color: #0b2a4a; margin-bottom: 28px; }

.tab-bar { display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 24px; }
.tab-btn {
    padding: 7px 16px; border-radius: 8px; font-size: 11px;
    letter-spacing: 0.1em; text-transform: uppercase;
    text-decoration: none; color: rgba(11,42,74,0.5);
    border: 0.5px solid rgba(11,42,74,0.12);
    background: white; transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 7px;
}
.tab-btn:hover { color: #0b2a4a; border-color: rgba(11,42,74,0.25); }
.tab-btn.active { background: #0b2a4a; color: #f0ebe0; border-color: #0b2a4a; }
.tab-count {
    font-size: 10px; padding: 1px 6px; border-radius: 100px;
    background: rgba(255,255,255,0.15); color: rgba(240,235,224,0.8);
}
.tab-btn:not(.active) .tab-count { background: rgba(11,42,74,0.07); color: rgba(11,42,74,0.45); }

.flash-success { background: #f0f7f0; border: 0.5px solid #b2d9b2; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #2d6a2d; margin-bottom: 20px; }
.flash-error   { background: #fdf0f0; border: 0.5px solid #f5c0c0; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #c0392b; margin-bottom: 20px; }

.order-card {
    background: white; border-radius: 14px;
    border: 0.5px solid rgba(11,42,74,0.08);
    padding: 22px 24px; margin-bottom: 14px;
    transition: border-color 0.2s;
}
.order-card:hover { border-color: rgba(11,42,74,0.15); }

.order-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.order-code { font-size: 13px; font-weight: 500; color: #0b2a4a; }
.order-date { font-size: 11px; color: rgba(11,42,74,0.4); margin-top: 3px; }

.status-badge { display: inline-block; padding: 3px 10px; border-radius: 100px; font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 500; }

.timeline { display: flex; align-items: flex-start; margin-bottom: 18px; }
.tl-step { display: flex; flex-direction: column; align-items: center; flex: 1; }
.tl-dot { width: 8px; height: 8px; border-radius: 50%; margin-bottom: 5px; flex-shrink: 0; }
.tl-dot-done    { background: #27ae60; }
.tl-dot-current { background: #c9a96e; }
.tl-dot-next    { background: rgba(11,42,74,0.12); }
.tl-dot-cancel  { background: #c0392b; }
.tl-line { flex: 1; height: 0.5px; background: rgba(11,42,74,0.1); margin-top: 4px; }
.tl-label { font-size: 10px; color: rgba(11,42,74,0.4); letter-spacing: 0.05em; text-align: center; line-height: 1.3; }
.tl-label-current { color: #0b2a4a; font-weight: 500; }

.order-items-preview { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
.item-thumb-wrap { position: relative; }
.item-thumb { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.08); display: block; }
.item-thumb-empty { width: 48px; height: 48px; border-radius: 8px; background: #f5f1e8; border: 0.5px solid rgba(11,42,74,0.08); }
.item-more { width: 48px; height: 48px; border-radius: 8px; background: rgba(11,42,74,0.05); border: 0.5px solid rgba(11,42,74,0.08); display: flex; align-items: center; justify-content: center; font-size: 11px; color: rgba(11,42,74,0.4); font-weight: 500; }

.order-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 0.5px solid rgba(11,42,74,0.06); }
.total-label { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 3px; }
.total-amount { font-family: 'Cormorant Garamond', serif; font-size: 22px; color: #0b2a4a; }

.btn-group { display: flex; gap: 8px; align-items: center; }
.btn-detail {
    font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase;
    color: #0b2a4a; text-decoration: none;
    border: 0.5px solid rgba(11,42,74,0.2); border-radius: 7px;
    padding: 8px 16px; transition: all 0.2s; background: none;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
}
.btn-detail:hover { border-color: #0b2a4a; }
.btn-cancel {
    font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase;
    color: #c0392b; border: 0.5px solid rgba(192,57,43,0.25); border-radius: 7px;
    padding: 8px 16px; background: none; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: all 0.2s;
}
.btn-cancel:hover { background: #c0392b; color: white; border-color: #c0392b; }

.empty-wrap { text-align: center; padding: 80px 0; }
.empty-title { font-family: 'Cormorant Garamond', serif; font-size: 28px; color: rgba(11,42,74,0.25); margin-bottom: 8px; }
.empty-sub { font-size: 13px; color: rgba(11,42,74,0.35); margin-bottom: 24px; }
.btn-shop { display: inline-block; padding: 12px 28px; background: #0b2a4a; color: #f0ebe0; text-decoration: none; border-radius: 8px; font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; font-weight: 500; }
</style>

<div class="orders-wrap">
    <p class="page-label">Taku</p>
    <h1 class="page-title">Pesanan Saya</h1>

    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    @php
    $tabs = [
        'all'       => 'Semua',
        'pending'   => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'shipped'   => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
    $statusOrder = ['pending','confirmed','shipped','completed'];
    @endphp

    <div class="tab-bar">
        @foreach($tabs as $key => $label)
        <a href="{{ route('orders.index', ['status' => $key]) }}"
           class="tab-btn {{ $status === $key ? 'active' : '' }}">
            {{ $label }}
            @if($counts[$key] > 0)
                <span class="tab-count">{{ $counts[$key] }}</span>
            @endif
        </a>
        @endforeach
    </div>

    @forelse($orders as $order)
    @php
        $s = $order->getStatusLabel();
        $currentIdx = array_search($order->status, $statusOrder);
        $isCancelled = $order->status === 'cancelled';
        $previewItems = $order->items->take(3);
        $extraCount = $order->items->count() - 3;
    @endphp

    <div class="order-card">
        <div class="order-head">
            <div>
                <p class="order-code">{{ $order->order_code }}</p>
                <p class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="status-badge" style="background:{{ $s['color'] }}18; color:{{ $s['color'] }};">
                {{ $s['label'] }}
            </span>
        </div>

        @if(!$isCancelled)
        <div class="timeline">
            @foreach($statusOrder as $i => $step)
            @php
                $isDone    = $currentIdx !== false && $currentIdx > $i;
                $isCurrent = $currentIdx === $i;
            @endphp
            <div class="tl-step">
                <div class="tl-dot {{ $isDone ? 'tl-dot-done' : ($isCurrent ? 'tl-dot-current' : 'tl-dot-next') }}"></div>
                <span class="tl-label {{ $isCurrent ? 'tl-label-current' : '' }}">
                    {{ ['Masuk','Konfirmasi','Dikirim','Selesai'][$i] }}
                </span>
            </div>
            @if($i < 3)
                <div class="tl-line" style="margin-top:4px;"></div>
            @endif
            @endforeach
        </div>
        @endif

        <div class="order-items-preview">
            @foreach($previewItems as $item)
                @if($item->product_image)
                    <img src="{{ asset($item->product_image) }}" class="item-thumb" alt="{{ $item->product_name }}">
                @else
                    <div class="item-thumb-empty"></div>
                @endif
            @endforeach
            @if($extraCount > 0)
                <div class="item-more">+{{ $extraCount }}</div>
            @endif
        </div>

        <div class="order-footer">
            <div>
                <p class="total-label">Total</p>
                <p class="total-amount">{{ $order->getTotalFormatted() }}</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('orders.show', $order) }}" class="btn-detail">Lihat Detail</a>
                @if($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST"
                      onsubmit="return confirm('Batalkan pesanan {{ $order->order_code }}?')">
                    @csrf
                    <button type="submit" class="btn-cancel">Batalkan</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-wrap">
        <p class="empty-title">Belum ada pesanan</p>
        <p class="empty-sub">
            @if($status === 'all') Kamu belum pernah melakukan pemesanan.
            @elseif($status === 'pending') Tidak ada pesanan yang menunggu konfirmasi.
            @elseif($status === 'shipped') Tidak ada pesanan yang sedang dikirim.
            @elseif($status === 'completed') Belum ada pesanan yang selesai.
            @elseif($status === 'cancelled') Tidak ada pesanan yang dibatalkan.
            @else Tidak ada pesanan di kategori ini.
            @endif
        </p>
        <a href="{{ route('products') }}" class="btn-shop">Mulai Belanja</a>
    </div>
    @endforelse
</div>

@endsection