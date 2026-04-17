@extends('merchant.layouts.sidebar')
@section('page-title', 'Detail Pesanan')
@section('content')

<style>
.back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: rgba(11,42,74,0.45); text-decoration: none; margin-bottom: 24px; letter-spacing: 0.06em; transition: color 0.2s; }
.back-link:hover { color: #0b2a4a; }

.order-layout { display: grid; grid-template-columns: 1fr 300px; gap: 24px; }
@media(max-width:800px){ .order-layout { grid-template-columns: 1fr; } }

.card { background: white; border-radius: 14px; border: 0.5px solid rgba(11,42,74,0.08); padding: 28px; margin-bottom: 20px; }
.card:last-child { margin-bottom: 0; }
.card-title { font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 20px; padding-bottom: 14px; border-bottom: 0.5px solid rgba(11,42,74,0.06); }

.order-code-big { font-family: 'Cormorant Garamond', serif; font-size: 26px; color: #0b2a4a; margin-bottom: 4px; }
.status-badge { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; font-weight: 500; }

.info-row { display: flex; gap: 8px; margin-bottom: 10px; font-size: 13px; }
.info-label { color: rgba(11,42,74,0.4); min-width: 80px; flex-shrink: 0; }
.info-value { color: #0b2a4a; }

.items-table { width: 100%; border-collapse: collapse; }
.items-table th { font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.35); font-weight: 400; padding: 0 0 12px; text-align: left; border-bottom: 0.5px solid rgba(11,42,74,0.06); }
.items-table td { padding: 12px 0; border-bottom: 0.5px solid rgba(11,42,74,0.04); font-size: 13px; color: #0b2a4a; vertical-align: middle; }
.items-table tr:last-child td { border-bottom: none; }
.item-thumb { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.08); margin-right: 12px; }

.total-row { display: flex; justify-content: space-between; align-items: baseline; padding-top: 16px; border-top: 0.5px solid rgba(11,42,74,0.08); margin-top: 4px; }
.total-label { font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.4); }
.total-value { font-family: 'Cormorant Garamond', serif; font-size: 26px; color: #0b2a4a; }

.action-card { background: white; border-radius: 14px; border: 0.5px solid rgba(11,42,74,0.08); padding: 24px; position: sticky; top: 80px; }
.action-title { font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 16px; }

.btn-action { width: 100%; padding: 13px; border: none; border-radius: 8px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; margin-bottom: 10px; transition: all 0.2s; }
.btn-action:last-child { margin-bottom: 0; }
.btn-confirm  { background: #0b2a4a; color: #f0ebe0; }
.btn-confirm:hover  { background: #0d3459; }
.btn-ship     { background: #8e44ad; color: white; }
.btn-ship:hover     { background: #7d3c98; }
.btn-complete { background: #27ae60; color: white; }
.btn-complete:hover { background: #219a52; }
.btn-cancel   { background: none; color: #c0392b; border: 0.5px solid rgba(192,57,43,0.3); }
.btn-cancel:hover   { background: #c0392b; color: white; }

.timeline { margin-top: 20px; padding-top: 20px; border-top: 0.5px solid rgba(11,42,74,0.06); }
.timeline-title { font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.35); margin-bottom: 14px; }
.timeline-item { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 12px; }
.timeline-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.timeline-dot-done { background: #27ae60; }
.timeline-dot-current { background: #c9a96e; }
.timeline-dot-next { background: rgba(11,42,74,0.12); }
.timeline-text-done { color: rgba(11,42,74,0.5); }
.timeline-text-current { color: #0b2a4a; font-weight: 500; }
.timeline-text-next { color: rgba(11,42,74,0.3); }
</style>

<a href="{{ route('merchant.orders.index') }}" class="back-link">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Pesanan Masuk
</a>

@php
$statusOrder = ['pending','confirmed','shipped','completed'];
$currentIdx  = array_search($order->status, $statusOrder);
$s = $order->getStatusLabel();
@endphp

<div class="order-layout">

    <div>
        {{-- Header --}}
        <div class="card">
            <p class="order-code-big">{{ $order->order_code }}</p>
            <span class="status-badge" style="background:{{ $s['color'] }}18; color:{{ $s['color'] }};">
                {{ $s['label'] }}
            </span>
            <p style="font-size:11px; color:rgba(11,42,74,0.35); margin-top:8px;">
                {{ $order->created_at->format('d M Y, H:i') }}
            </p>
        </div>

        <div class="card">
            <p class="card-title">Informasi Pelanggan</p>
            <div class="info-row"><span class="info-label">Nama</span><span class="info-value">{{ $order->name }}</span></div>
            <div class="info-row"><span class="info-label">No. WA</span>
                <span class="info-value">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}"
                       target="_blank" style="color:#25d366; text-decoration:none;">
                        {{ $order->phone }}
                    </a>
                </span>
            </div>
            <div class="info-row"><span class="info-label">Alamat</span><span class="info-value">{{ $order->address }}</span></div>
            @if($order->note)
            <div class="info-row"><span class="info-label">Catatan</span><span class="info-value" style="font-style:italic;">{{ $order->note }}</span></div>
            @endif
        </div>

        <div class="card">
            <p class="card-title">Detail Produk</p>
            <table class="items-table">
                <thead>
                    <tr>
                        <th colspan="2">Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="width:52px;">
                            @if($item->product_image)
                                <img src="{{ asset($item->product_image) }}" class="item-thumb" alt="{{ $item->product_name }}">
                            @endif
                        </td>
                        <td style="font-weight:500;">{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>x{{ $item->qty }}</td>
                        <td style="text-align:right; font-weight:500;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-value">{{ $order->getTotalFormatted() }}</span>
            </div>
        </div>
    </div>

    <div>
        <div class="action-card">
            <p class="action-title">Update Status</p>

            @if($order->status === 'pending')
            <form action="{{ route('merchant.orders.status', $order) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="btn-action btn-confirm">✓ Terima Pesanan</button>
            </form>
            @endif

            @if($order->status === 'confirmed')
            <form action="{{ route('merchant.orders.status', $order) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="shipped">
                <button type="submit" class="btn-action btn-ship">↑ Tandai Dikirim</button>
            </form>
            @endif

            @if($order->status === 'shipped')
            <form action="{{ route('merchant.orders.status', $order) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn-action btn-complete">✓ Pesanan Selesai</button>
            </form>
            @endif

            @if(!in_array($order->status, ['completed','cancelled']))
            <form action="{{ route('merchant.orders.status', $order) }}" method="POST"
                  onsubmit="return confirm('Batalkan pesanan ini?')">
                @csrf
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn-action btn-cancel">Batalkan</button>
            </form>
            @endif

            @if(in_array($order->status, ['completed','cancelled']))
            <p style="font-size:12px; color:rgba(11,42,74,0.4); text-align:center;">
                Pesanan sudah {{ $order->status === 'completed' ? 'selesai' : 'dibatalkan' }}.
            </p>
            @endif

            <div class="timeline">
                <p class="timeline-title">Alur Pesanan</p>
                @php
                $timelineSteps = [
                    'pending'   => 'Pesanan masuk',
                    'confirmed' => 'Dikonfirmasi',
                    'shipped'   => 'Dikirim',
                    'completed' => 'Selesai',
                ];
                @endphp
                @foreach($timelineSteps as $key => $label)
                @php
                $idx = array_search($key, $statusOrder);
                $isDone    = $currentIdx > $idx;
                $isCurrent = $currentIdx === $idx;
                $isNext    = $currentIdx < $idx;
                @endphp
                <div class="timeline-item">
                    <span class="timeline-dot {{ $isDone ? 'timeline-dot-done' : ($isCurrent ? 'timeline-dot-current' : 'timeline-dot-next') }}"></span>
                    <span class="{{ $isDone ? 'timeline-text-done' : ($isCurrent ? 'timeline-text-current' : 'timeline-text-next') }}">
                        {{ $label }}
                    </span>
                </div>
                @endforeach
            </div>

            <div style="margin-top:20px; padding-top:16px; border-top:0.5px solid rgba(11,42,74,0.06);">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}"
                   target="_blank"
                   style="display:flex; align-items:center; justify-content:center; gap:8px; padding:10px; background:rgba(37,211,102,0.1); border:0.5px solid rgba(37,211,102,0.3); border-radius:8px; color:#1a8a3d; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; text-decoration:none; transition:background 0.2s;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                    </svg>
                    Hubungi Pelanggan
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
