@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
.success-wrap { max-width: 520px; margin: 80px auto; padding: 0 24px; font-family: 'DM Sans', sans-serif; text-align: center; }
.success-icon { width: 64px; height: 64px; background: #f0f7f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; border: 0.5px solid #b2d9b2; }
.success-title { font-family: 'Cormorant Garamond', serif; font-size: 32px; color: #0b2a4a; margin-bottom: 8px; }
.success-sub { font-size: 14px; color: rgba(11,42,74,0.5); margin-bottom: 32px; line-height: 1.7; }
.order-codes { background: #f8f6f2; border-radius: 10px; padding: 16px 20px; margin-bottom: 28px; text-align: left; }
.order-codes-label { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 10px; }
.order-code-item { font-size: 14px; font-weight: 500; color: #0b2a4a; padding: 6px 0; border-bottom: 0.5px solid rgba(11,42,74,0.06); }
.order-code-item:last-child { border-bottom: none; }
.wa-buttons { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
.wa-btn { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 14px; background: #25d366; color: white; border-radius: 8px; font-size: 12px; letter-spacing: 0.12em; text-transform: uppercase; font-weight: 500; text-decoration: none; transition: background 0.2s; }
.wa-btn:hover { background: #1ebe5d; }
.btn-orders { display: inline-block; padding: 12px 28px; border: 0.5px solid rgba(11,42,74,0.2); border-radius: 8px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.6); text-decoration: none; transition: all 0.2s; }
.btn-orders:hover { color: #0b2a4a; border-color: rgba(11,42,74,0.4); }
</style>

<div class="success-wrap">
    <div class="success-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
    </div>

    <h1 class="success-title">Pesanan Berhasil</h1>
    <p class="success-sub">
        Pesanan kamu sudah tersimpan. Selesaikan dengan menghubungi toko via WhatsApp di bawah.
    </p>

    @if(count($orderCodes) > 0)
    <div class="order-codes">
        <p class="order-codes-label">Kode Pesanan</p>
        @foreach($orderCodes as $code)
        <p class="order-code-item">{{ $code }}</p>
        @endforeach
    </div>
    @endif

    @if(count($waUrls) > 0)
    <div class="wa-buttons">
        @foreach($waUrls as $i => $url)
        <a href="{{ $url }}" target="_blank" class="wa-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
            </svg>
            Hubungi {{ count($waUrls) > 1 ? 'Toko ' . ($i + 1) : 'Toko' }} via WhatsApp
        </a>
        @endforeach
    </div>
    @endif

    <a href="{{ route('orders.index') }}" class="btn-orders">Lihat Semua Pesanan</a>
</div>

@endsection
