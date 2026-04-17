@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');

.detail-wrap { max-width: 780px; margin: 56px auto 80px; padding: 0 24px; font-family: 'DM Sans', sans-serif; }
.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: rgba(11,42,74,0.45); text-decoration: none; margin-bottom: 24px; letter-spacing: 0.06em; transition: color 0.2s; }
.back-link:hover { color: #0b2a4a; }

.order-hero { background: #0b2a4a; border-radius: 14px; padding: 26px 28px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start; }
.hero-code { font-family: 'Cormorant Garamond', serif; font-size: 26px; color: #f0ebe0; margin-bottom: 4px; }
.hero-date { font-size: 11px; color: rgba(240,235,224,0.4); }
.status-badge { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 500; }

.card { background: white; border-radius: 14px; border: 0.5px solid rgba(11,42,74,0.08); padding: 24px; margin-bottom: 16px; }
.card-title { font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 16px; padding-bottom: 12px; border-bottom: 0.5px solid rgba(11,42,74,0.06); }

.timeline-h { display: flex; align-items: flex-start; margin-bottom: 4px; }
.tl-step { display: flex; flex-direction: column; align-items: center; flex: 1; }
.tl-dot { width: 10px; height: 10px; border-radius: 50%; margin-bottom: 6px; flex-shrink: 0; }
.tl-dot-done    { background: #27ae60; }
.tl-dot-current { background: #c9a96e; box-shadow: 0 0 0 3px rgba(201,169,110,0.2); }
.tl-dot-next    { background: rgba(11,42,74,0.1); border: 0.5px solid rgba(11,42,74,0.2); }
.tl-line { flex: 1; height: 0.5px; margin-top: 5px; }
.tl-line-done { background: #27ae60; }
.tl-line-next { background: rgba(11,42,74,0.1); }
.tl-label { font-size: 11px; color: rgba(11,42,74,0.4); text-align: center; line-height: 1.3; }
.tl-label-current { color: #0b2a4a; font-weight: 500; }

.info-row { display: flex; gap: 12px; margin-bottom: 10px; font-size: 13px; }
.info-row:last-child { margin-bottom: 0; }
.info-key { color: rgba(11,42,74,0.4); min-width: 72px; flex-shrink: 0; }
.info-val { color: #0b2a4a; }

.items-list { }
.item-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 0.5px solid rgba(11,42,74,0.04); }
.item-row:last-child { border-bottom: none; }
.item-img { width: 52px; height: 52px; border-radius: 8px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.08); flex-shrink: 0; }
.item-img-empty { width: 52px; height: 52px; border-radius: 8px; background: #f5f1e8; flex-shrink: 0; }
.item-name { font-size: 13px; font-weight: 500; color: #0b2a4a; }
.item-qty  { font-size: 11px; color: rgba(11,42,74,0.4); margin-top: 2px; }
.item-sub  { margin-left: auto; font-size: 13px; font-weight: 500; color: #0b2a4a; white-space: nowrap; }

.total-section { display: flex; justify-content: space-between; align-items: baseline; padding-top: 16px; border-top: 0.5px solid rgba(11,42,74,0.08); margin-top: 4px; }
.total-lbl { font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); }
.total-val { font-family: 'Cormorant Garamond', serif; font-size: 28px; color: #0b2a4a; }

.cancel-zone { border: 0.5px solid rgba(192,57,43,0.2); border-radius: 10px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; }
.cancel-note { font-size: 13px; color: rgba(11,42,74,0.5); }
.btn-cancel-lg { font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; color: #c0392b; border: 0.5px solid rgba(192,57,43,0.3); border-radius: 8px; padding: 10px 20px; background: none; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; white-space: nowrap; }
.btn-cancel-lg:hover { background: #c0392b; color: white; }

.flash-success { background: #f0f7f0; border: 0.5px solid #b2d9b2; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #2d6a2d; margin-bottom: 20px; }
.flash-error   { background: #fdf0f0; border: 0.5px solid #f5c0c0; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #c0392b; margin-bottom: 20px; }
</style>

@php
$statusOrder = ['pending','confirmed','shipped','completed'];
$currentIdx  = array_search($order->status, $statusOrder);
$s = $order->getStatusLabel();
$isCancelled = $order->status === 'cancelled';
@endphp

<div class="detail-wrap">
    <a href="{{ route('orders.index') }}" class="back-link">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Pesanan Saya
    </a>

    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    <div class="order-hero">
        <div>
            <p class="hero-code">{{ $order->order_code }}</p>
            <p class="hero-date">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="status-badge" style="background:{{ $s['color'] }}22; color:{{ $s['color'] }}; border:0.5px solid {{ $s['color'] }}44;">
            {{ $s['label'] }}
        </span>
    </div>

    @if(!$isCancelled)
    <div class="card">
        <p class="card-title">Status Pesanan</p>
        <div class="timeline-h">
            @foreach($statusOrder as $i => $step)
            @php
                $isDone    = $currentIdx !== false && $currentIdx > $i;
                $isCurrent = $currentIdx === $i;
            @endphp
            <div class="tl-step">
                <div class="tl-dot {{ $isDone ? 'tl-dot-done' : ($isCurrent ? 'tl-dot-current' : 'tl-dot-next') }}"></div>
                <span class="tl-label {{ $isCurrent ? 'tl-label-current' : '' }}">
                    {{ ['Pesanan Masuk','Dikonfirmasi','Sedang Dikirim','Selesai'][$i] }}
                </span>
            </div>
            @if($i < 3)
                <div class="tl-line {{ $isDone ? 'tl-line-done' : 'tl-line-next' }}" style="margin-top:5px;"></div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    <div class="card">
        <p class="card-title">Informasi Pengiriman</p>
        <div class="info-row"><span class="info-key">Nama</span><span class="info-val">{{ $order->name }}</span></div>
        <div class="info-row"><span class="info-key">No. WA</span><span class="info-val">{{ $order->phone }}</span></div>
        <div class="info-row"><span class="info-key">Alamat</span><span class="info-val">{{ $order->address }}</span></div>
        @if($order->note)
        <div class="info-row"><span class="info-key">Catatan</span><span class="info-val" style="font-style:italic;">{{ $order->note }}</span></div>
        @endif
    </div>

    <div class="card">
        <p class="card-title">Detail Produk</p>
        <div class="items-list">
            @foreach($order->items as $item)
            <div class="item-row">
                @if($item->product_image)
                    <img src="{{ asset($item->product_image) }}" class="item-img" alt="{{ $item->product_name }}">
                @else
                    <div class="item-img-empty"></div>
                @endif
                <div>
                    <p class="item-name">{{ $item->product_name }}</p>
                    <p class="item-qty">x{{ $item->qty }} · Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <p class="item-sub">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
        <div class="total-section">
            <span class="total-lbl">Total</span>
            <span class="total-val">{{ $order->getTotalFormatted() }}</span>
        </div>
    </div>

    @if($order->status === 'pending')
    <div class="cancel-zone">
        <p class="cancel-note">Pesanan masih bisa dibatalkan selama belum dikonfirmasi.</p>
        <form action="{{ route('orders.cancel', $order) }}" method="POST"
              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
            @csrf
            <button type="submit" class="btn-cancel-lg">Batalkan Pesanan</button>
        </form>
    </div>
    @endif

</div>

@endsection