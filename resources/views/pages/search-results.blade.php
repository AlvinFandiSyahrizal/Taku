@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang','id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.sr-wrap{max-width:1200px;margin:48px auto 80px;padding:0 32px;font-family:'DM Sans',sans-serif;}
.sr-header{margin-bottom:36px;}
.sr-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:6px;}
.sr-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:32px;color:#0b2a4a;}
.sr-query{color:#c9a96e;}
.sr-section-title{font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:16px;padding-bottom:10px;border-bottom:.5px solid rgba(11,42,74,.08);}
.sr-empty{text-align:center;padding:40px 0;color:rgba(11,42,74,.35);font-size:13px;}

.sr-prod-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:48px;}
.sr-prod-card{border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);text-decoration:none;color:inherit;display:block;transition:transform .25s,box-shadow .25s;background:white;}
.sr-prod-card:hover{transform:translateY(-4px);box-shadow:0 10px 28px rgba(11,42,74,.1);}
.sr-prod-img{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;}
.sr-prod-info{padding:10px 12px 12px;}
.sr-prod-store{font-size:10px;color:#c9a96e;letter-spacing:.06em;text-transform:uppercase;margin-bottom:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.sr-prod-name{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.sr-prod-price{font-size:13px;color:#c9a96e;font-weight:500;}
.sr-prod-original{font-size:10px;color:rgba(11,42,74,.3);text-decoration:line-through;margin-left:4px;}

.sr-store-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:48px;}
.sr-store-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:16px;text-decoration:none;color:inherit;display:flex;align-items:center;gap:12px;transition:all .2s;}
.sr-store-card:hover{border-color:rgba(11,42,74,.2);transform:translateY(-2px);box-shadow:0 6px 20px rgba(11,42,74,.08);}
.sr-store-avatar{width:44px;height:44px;border-radius:50%;background:rgba(11,42,74,.06);border:.5px solid rgba(11,42,74,.1);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:20px;color:#0b2a4a;flex-shrink:0;object-fit:cover;}
.sr-store-name{font-size:13px;font-weight:500;color:#0b2a4a;}
.sr-store-meta{font-size:11px;color:rgba(11,42,74,.4);}

@media(max-width:600px){
    .sr-wrap{padding:0 16px;margin-top:32px;}
    .sr-prod-grid{grid-template-columns:repeat(2,1fr);gap:12px;}
    .sr-store-grid{grid-template-columns:1fr 1fr;}
}
</style>

<div class="sr-wrap">
    <div class="sr-header">
        <p class="sr-label">Hasil Pencarian</p>
        <h1 class="sr-title">
            Menampilkan hasil untuk <span class="sr-query">"{{ $searchQuery }}"</span>
        </h1>
    </div>

    <p class="sr-section-title">Produk</p>
    @if($searchProducts->count() > 0)
    <div class="sr-prod-grid">
        @foreach($searchProducts as $product)
        <a href="{{ route('product.show', $product->id) }}" class="sr-prod-card">
            <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}"
                 class="sr-prod-img" alt="{{ $product->name }}"
                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
            <div class="sr-prod-info">
                <p class="sr-prod-store">{{ $product->store?->name ?? 'Taku Official' }}</p>
                <p class="sr-prod-name">{{ $product->name }}</p>
                <div style="display:flex;align-items:center;flex-wrap:wrap;">
                    <span class="sr-prod-price">{{ $product->getFinalPriceFormatted() }}</span>
                    @if($product->hasDiscount())
                        <span class="sr-prod-original">{{ $product->getPriceFormatted() }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="sr-empty" style="margin-bottom:48px;">Tidak ada produk yang cocok.</div>
    @endif

    <p class="sr-section-title">Toko</p>
    @if($searchStores->count() > 0)
    <div class="sr-store-grid">
        @foreach($searchStores as $store)
        <a href="{{ route('store.show', $store->slug) }}" class="sr-store-card">
            @if($store->logo)
                <img src="{{ asset($store->logo) }}" class="sr-store-avatar" alt="{{ $store->name }}">
            @else
                <div class="sr-store-avatar">{{ strtoupper(substr($store->name,0,1)) }}</div>
            @endif
            <div>
                <p class="sr-store-name">{{ $store->name }}</p>
                <p class="sr-store-meta">Toko Aktif</p>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="sr-empty">Tidak ada toko yang cocok.</div>
    @endif

    @if($searchProducts->count() === 0 && $searchStores->count() === 0)
    <div style="text-align:center;padding:60px 0;">
        <p style="font-size:32px;margin-bottom:16px;">🔍</p>
        <p style="font-size:15px;color:#0b2a4a;font-family:'Cormorant Garamond',serif;margin-bottom:8px;">Tidak ada hasil untuk "{{ $searchQuery }}"</p>
        <p style="font-size:13px;color:rgba(11,42,74,.4);margin-bottom:24px;">Coba kata kunci yang berbeda atau jelajahi semua produk.</p>
        <a href="{{ route('products') }}" style="display:inline-block;padding:10px 24px;background:#0b2a4a;color:#f0ebe0;border-radius:8px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;text-decoration:none;font-family:'DM Sans',sans-serif;">
            Lihat Semua Produk
        </a>
    </div>
    @endif
</div>

<a href="https://wa.me/6281324683769" target="_blank"
   style="position:fixed;bottom:24px;right:24px;background:#25d366;color:white;width:52px;height:52px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,0.15);transition:transform 0.2s;z-index:100;"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
</a>

@endsection
