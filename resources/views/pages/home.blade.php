@extends('layouts.app')
@section('title', 'Taku — Bringing Life Into Modern Spaces')
@section('meta_description', 'Temukan tanaman premium untuk ruang hidup kamu. Koleksi plants estetik, berkualitas, dan mudah dirawat — dari merchant terpilih di seluruh Indonesia.')
@section('content')
@php
    app()->setLocale(session('lang','id'));
    $locale = session('lang','id');
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}

.hero{
    background:linear-gradient(160deg,#F5F1E8 0%,#F5F1E8AA 60%,#F5F1E8D3 100%);
    height:400px;
    position:relative;
    overflow:hidden;
    border-bottom:.5px solid rgba(11,42,74,0.08);
}

.hero-glow{position:absolute;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(201,169,110,.08) 0%,transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;}
.hero-logo{
    position:absolute;
    top:40%; /* posisi logo */
    left:50%;
    transform:translate(-50%,-50%);
    max-height:950px; /* bebas mau gede */
    width:auto;
    opacity:.92;
    z-index:1;
}

.hero-search{
    position:absolute;
    bottom:40px;
    left:50%;
    transform:translateX(-50%);
    z-index:2;
    display:flex;
    align-items:center;
    background:white;
    border:.5px solid rgba(11,42,74,.12);
    border-radius:100px;
    padding:10px 10px 10px 20px;
    gap:10px;
    width:440px;
    max-width:92%;
}

.hero-search input{background:none;border:none;outline:none;color:#0b2a4a;font-size:13px;font-family:'DM Sans',sans-serif;flex:1;}
.hero-search input::placeholder{color:rgba(11,42,74,.35);}
.hero-search button{background:#c9a96e;border:none;border-radius:100px;color:#0b2a4a;font-size:10px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;padding:8px 20px;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;transition:background .2s;}
.hero-search button:hover{background:#b8955a;}

.banner-wrap{position:relative;overflow:hidden;max-height:420px;background:#0b2a4a;}
.banner-track{display:flex;transition:transform .5s ease;}
.banner-slide{min-width:100%;position:relative;flex-shrink:0;}
.banner-slide img{width:100%;max-height:420px;object-fit:cover;display:block;}
.banner-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(11,42,74,.6) 0%,rgba(11,42,74,.1) 60%,transparent 100%);display:flex;align-items:center;padding:0 60px;}
.banner-content{max-width:480px;}
.banner-title-text{font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:300;color:#f0ebe0;line-height:1.1;margin-bottom:8px;}
.banner-subtitle-text{font-size:14px;color:rgba(240,235,224,.7);margin-bottom:20px;font-family:'DM Sans',sans-serif;}
.banner-cta{display:inline-block;padding:10px 28px;background:#c9a96e;color:#0b2a4a;border-radius:8px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;text-decoration:none;transition:background .2s;}
.banner-cta:hover{background:#b8955a;}
.banner-dots{position:absolute;bottom:14px;left:50%;transform:translateX(-50%);display:flex;gap:6px;z-index:10;}
.banner-dot{width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.4);cursor:pointer;transition:background .2s;}
.banner-dot.active{background:white;}
.banner-prev,.banner-next{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:none;cursor:pointer;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;z-index:10;transition:background .2s;}
.banner-prev:hover,.banner-next:hover{background:rgba(255,255,255,.3);}
.banner-prev{left:16px;}
.banner-next{right:16px;}

.section{max-width:1200px;margin:0 auto;padding:52px 32px;}
.section-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px;}
.section-label{font-size:9px;letter-spacing:.28em;text-transform:uppercase;color:#c9a96e;font-family:'DM Sans',sans-serif;margin-bottom:5px;}
.section-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:30px;color:#0b2a4a;}
.view-all{display:inline-flex;align-items:center;gap:5px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.45);text-decoration:none;border:.5px solid rgba(11,42,74,.12);border-radius:6px;padding:7px 14px;transition:all .2s;font-family:'DM Sans',sans-serif;white-space:nowrap;}
.view-all:hover{color:#0b2a4a;border-color:rgba(11,42,74,.25);}

.slider-wrap{position:relative;}
.slider-viewport{overflow:hidden;}
.slider-track{display:flex;gap:16px;transition:transform .35s ease;}
.prod-card{border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);background:white;text-decoration:none;color:inherit;flex-shrink:0;transition:transform .25s,box-shadow .25s;}
.prod-card:hover{transform:translateY(-4px);box-shadow:0 10px 28px rgba(11,42,74,.1);}
.prod-card-img{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;}
.prod-card-info{padding:12px 14px 14px;}
.prod-card-store{font-size:10px;color:rgba(11,42,74,.4);letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.prod-card-name{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.prod-card-price-wrap{display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.prod-card-price{font-size:13px;color:#c9a96e;font-weight:500;}
.prod-card-original{font-size:11px;color:rgba(11,42,74,.3);text-decoration:line-through;}
.prod-card-badge{font-size:9px;background:rgba(192,57,43,.1);color:#c0392b;padding:2px 6px;border-radius:100px;letter-spacing:.06em;font-weight:500;}

.slider-btn{position:absolute;top:50%;transform:translateY(-50%);background:white;border:.5px solid rgba(11,42,74,.12);cursor:pointer;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#0b2a4a;z-index:5;transition:all .2s;box-shadow:0 2px 8px rgba(11,42,74,.08);}
.slider-btn:hover{background:#0b2a4a;color:#f0ebe0;border-color:#0b2a4a;}
.slider-prev{left:-18px;}
.slider-next{right:-18px;}
@media(max-width:700px){.slider-prev{left:0;}.slider-next{right:0;}}

.prod-grid-2{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;}

.stores-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:16px;}
.store-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:20px;text-decoration:none;color:inherit;transition:all .2s;display:flex;flex-direction:column;gap:10px;}
.store-card:hover{border-color:rgba(11,42,74,.2);transform:translateY(-3px);box-shadow:0 8px 24px rgba(11,42,74,.08);}
.store-avatar-img{width:44px;height:44px;border-radius:50%;object-fit:cover;border:.5px solid rgba(11,42,74,.1);}
.store-avatar-init{width:44px;height:44px;border-radius:50%;background:rgba(11,42,74,.06);border:.5px solid rgba(11,42,74,.1);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:20px;color:#0b2a4a;flex-shrink:0;}
.store-name{font-size:14px;font-weight:500;color:#0b2a4a;}
.store-meta{font-size:11px;color:rgba(11,42,74,.4);}
.store-badge{display:inline-block;padding:2px 10px;border-radius:100px;background:rgba(39,174,96,.08);color:#1a7a3c;font-size:10px;letter-spacing:.08em;text-transform:uppercase;border:.5px solid rgba(39,174,96,.2);width:fit-content;}

.cat-grid{display:flex;gap:10px;flex-wrap:wrap;}
.cat-chip{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:white;border:.5px solid rgba(11,42,74,.1);border-radius:100px;text-decoration:none;font-family:'DM Sans',sans-serif;font-size:12px;color:#0b2a4a;letter-spacing:.05em;transition:all .2s;}
.cat-chip:hover{background:#0b2a4a;color:#f0ebe0;border-color:#0b2a4a;}
.cat-chip-count{font-size:10px;color:rgba(11,42,74,.35);}
.cat-chip:hover .cat-chip-count{color:rgba(240,235,224,.5);}

.cta-strip{background:#f5f1e8;border-top:.5px solid rgba(11,42,74,.07);border-bottom:.5px solid rgba(11,42,74,.07);padding:48px 32px;text-align:center;}
.cta-title{font-family:'Cormorant Garamond',serif;font-size:28px;color:#0b2a4a;margin-bottom:8px;}
.cta-sub{font-size:13px;color:rgba(11,42,74,.5);margin-bottom:24px;font-family:'DM Sans',sans-serif;}
.cta-btn{display:inline-block;padding:12px 32px;background:#0b2a4a;color:#f0ebe0;text-decoration:none;border-radius:8px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;font-family:'DM Sans',sans-serif;transition:background .2s;}
.cta-btn:hover{background:#0d3459;}

@media(max-width:600px){
    .section{padding:36px 16px;}
    .prod-grid-2{grid-template-columns:repeat(2,1fr);gap:12px;}
}

.snap-slider { -webkit-overflow-scrolling:touch; scrollbar-width:none; cursor:grab; }
.snap-slider::-webkit-scrollbar { display:none; }
.snap-slider.dragging { cursor:grabbing; user-select:none; }

@media(max-width:600px){
    .section { padding:32px 16px; }
    .hero { min-height:280px; padding:40px 16px 32px; }
    .hero-logo { max-height:100px; }
}

</style>

<section class="hero">
    <div class="hero-glow"></div>
    <img src="{{ asset('images/logotaku.png') }}" class="hero-logo" alt="Taku"
         onerror="this.src='{{ asset('images/logotaku.jpg') }}';this.onerror=null;">
    <form action="{{ route('home') }}" method="GET" class="hero-search">
        <input type="text" name="q" placeholder="Cari produk atau nama toko...">
        <button type="submit">Cari</button>
    </form>
</section>

{{-- BANNER --}}
@if($banners->count() > 0)
<div class="banner-wrap" id="bannerWrap">
    <div class="banner-track" id="bannerTrack">
        @foreach($banners as $b)
        <div class="banner-slide">
            @if($b->image)
                <img src="{{ asset($b->image) }}" alt="{{ $b->title }}">
            @endif
            @if($b->title || $b->button_text)
            <div class="banner-overlay">
                <div class="banner-content">
                    @if($b->title)<h2 class="banner-title-text">{{ $b->title }}</h2>@endif
                    @if($b->subtitle)<p class="banner-subtitle-text">{{ $b->subtitle }}</p>@endif
                    @if($b->link && $b->button_text)
                    <a href="{{ $b->link }}" class="banner-cta">{{ $b->button_text }}</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($banners->count() > 1)
    <button class="banner-prev" onclick="bannerMove(-1)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="banner-next" onclick="bannerMove(1)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="banner-dots" id="bannerDots">
        @foreach($banners as $i => $b)
        <div class="banner-dot {{ $i===0 ? 'active' : '' }}" onclick="bannerGo({{ $i }})"></div>
        @endforeach
    </div>
    @endif
</div>
@endif

{{-- KATEGORI --}}
@if($categories->count() > 0)
<section class="section" style="padding-bottom:0;">
    <div class="section-header">
        <div>
            <p class="section-label">Jelajahi</p>
            <h2 class="section-title">Kategori</h2>
        </div>
    </div>
    <div class="cat-grid">
        @foreach($categories as $cat)
        <a href="{{ route('products', ['category' => $cat->slug]) }}" class="cat-chip">
            @if($cat->icon)<span>{{ $cat->icon }}</span>@endif
            {{ $cat->name }}
            <span class="cat-chip-count">({{ $cat->products_count }})</span>
        </a>
        @endforeach
        <a href="{{ route('products') }}" class="cat-chip" style="border-style:dashed;">Semua →</a>
    </div>
</section>
@endif

{{-- PRODUK TERLARIS --}}
@if($bestSellers->count() > 0)
<section class="section">
    <div class="section-header">
        <div><p class="section-label">Populer</p><h2 class="section-title">Produk Terlaris</h2></div>
        <a href="{{ route('products') }}" class="view-all">Lihat Semua →</a>
    </div>
    <div style="overflow-x:auto;scroll-snap-type:x mandatory;display:flex;gap:16px;padding-bottom:12px;scrollbar-width:none;-ms-overflow-style:none;cursor:grab;"
     class="snap-slider"
     onmousedown="sliderDrag(this,event)">
    @foreach($bestSellers as $product)
    <a href="{{ route('product.show', $product->id) }}"
       style="scroll-snap-align:start;flex-shrink:0;width:200px;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);background:white;text-decoration:none;color:inherit;transition:transform .25s;">
        <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}"
             style="width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;"
             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
        <div style="padding:12px 14px 14px;">
            <p style="font-size:10px;color:rgba(11,42,74,.4);letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $product->store?->name ?? 'Taku Official' }}
            </p>
            <p style="font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $product->name }}
            </p>
            <div style="margin-bottom:28px;">
                @if($product->hasDiscount())
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <p class="pd-price" style="color:#c0392b;">{{ $product->getFinalPriceFormatted() }}</p>
                        <p style="font-size:16px;color:#c9a96e;text-decoration:line-through;">{{ $product->getPriceFormatted() }}</p>
                        <span style="background:rgba(192,57,43,0.1);color:#c0392b;font-size:10px;padding:3px 8px;border-radius:100px;font-weight:500;letter-spacing:0.06em;">
                            -{{ $product->discount_percent }}%
                        </span>
                    </div>

                @else
                    <p style="font-size:13px;color:#c9a96e;font-weight:500;">{{ $product->getFinalPriceFormatted() }}</p>
                @endif
            </div>

        </div>
    </a>
    @endforeach
</div>
</section>
@endif

{{-- HOME SECTIONS CUSTOM --}}
@foreach($homeSections as $hs)
@if($hs->products->count() > 0)
<section class="section" style="{{ $loop->even ? 'background:#faf8f5;max-width:100%;' : '' }}">
<div style="{{ $loop->even ? 'max-width:1200px;margin:0 auto;' : '' }}">
    <div class="section-header">
        <div>
            <p class="section-label">{{ $hs->subtitle ?? 'Koleksi' }}</p>
            <h2 class="section-title">{{ $hs->title }}</h2>
        </div>
    </div>
    @if($hs->rows === 1)
        <div style="overflow-x:auto;scroll-snap-type:x mandatory;display:flex;gap:16px;padding-bottom:12px;scrollbar-width:none;-ms-overflow-style:none;cursor:grab;"
     class="snap-slider"
     onmousedown="sliderDrag(this,event)">
    @foreach($bestSellers as $product)
    <a href="{{ route('product.show', $product->id) }}"
       style="scroll-snap-align:start;flex-shrink:0;width:200px;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);background:white;text-decoration:none;color:inherit;transition:transform .25s;">
        <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}"
             style="width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;"
             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
        <div style="padding:12px 14px 14px;">
            <p style="font-size:10px;color:rgba(11,42,74,.4);letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $product->store?->name ?? 'Taku Official' }}
            </p>
            <p style="font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $product->name }}
            </p>
            <p style="font-size:13px;color:#c9a96e;font-weight:500;">{{ $product->getFinalPriceFormatted() }}</p>
        </div>
    </a>
    @endforeach
</div>
    @else
        <div class="prod-grid-2">
            @foreach($hs->products as $product)
            @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</div>
</section>
@endif
@endforeach


{{-- TOKO PILIHAN --}}
@if($topStores->count() > 0)
<section class="section" style="background:#faf8f5;max-width:100%;border-top:.5px solid rgba(11,42,74,.06);border-bottom:.5px solid rgba(11,42,74,.06);padding-top:48px;padding-bottom:48px;">
    <div style="max-width:1200px;margin:0 auto;">
        <div class="section-header">
            <div><p class="section-label">Marketplace</p><h2 class="section-title">Toko Pilihan</h2></div>
        </div>

        {{-- SLIDER TOKO --}}
        <div style="overflow-x:auto;scroll-snap-type:x mandatory;display:flex;gap:14px;padding-bottom:12px;scrollbar-width:none;cursor:grab;"
             class="snap-slider" onmousedown="sliderDrag(this,event)">
            @foreach($topStores as $store)
                <a href="{{ isset($store->is_official) ? route('store.official') : route('store.show', $store->slug) }}"
                class="store-card">

                <div style="display:flex;align-items:center;gap:12px;">
                    @if($store->logo)
                        <img src="{{ asset($store->logo) }}"
                             style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:.5px solid rgba(11,42,74,.1);"
                             alt="{{ $store->name }}">
                    @else
                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(11,42,74,.06);border:.5px solid rgba(11,42,74,.1);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:20px;color:#0b2a4a;flex-shrink:0;">
                            {{ strtoupper(substr($store->name,0,1)) }}
                        </div>
                    @endif
                    <div style="min-width:0;">
                        <p style="font-size:14px;font-weight:500;color:#0b2a4a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $store->name }}</p>
                        <p style="font-size:11px;color:rgba(11,42,74,.4);">{{ $store->products_count }} produk</p>
                    </div>
                </div>

                @if($store->description)
                <p style="font-size:12px;color:rgba(11,42,74,.45);line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $store->description }}
                </p>
                @endif

                <span style="display:inline-block;padding:3px 10px;border-radius:100px;background:rgba(39,174,96,.08);color:#1a7a3c;font-size:10px;letter-spacing:.08em;text-transform:uppercase;border:.5px solid rgba(39,174,96,.2);width:fit-content;">
                    ● Toko Aktif
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- PRODUK TERBARU --}}
@if($featuredProducts->count() > 0)
<section class="section">
    <div class="section-header">
        <div><p class="section-label">Baru Masuk</p><h2 class="section-title">Produk Terbaru</h2></div>
        <a href="{{ route('products') }}" class="view-all">Lihat Semua →</a>
    </div>
    <div style="overflow-x:auto;scroll-snap-type:x mandatory;display:flex;gap:16px;padding-bottom:12px;scrollbar-width:none;-ms-overflow-style:none;cursor:grab;"
     class="snap-slider"
     onmousedown="sliderDrag(this,event)">
    @foreach($bestSellers as $product)
    <a href="{{ route('product.show', $product->id) }}"
       style="scroll-snap-align:start;flex-shrink:0;width:200px;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);background:white;text-decoration:none;color:inherit;transition:transform .25s;">
        <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}"
             style="width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;"
             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
        <div style="padding:12px 14px 14px;">
            <p style="font-size:10px;color:rgba(11,42,74,.4);letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $product->store?->name ?? 'Taku Official' }}
            </p>
            <p style="font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $product->name }}
            </p>

            {{-- Harga dengan diskon --}}
            <div style="margin-bottom:28px;">
                @if($product->hasDiscount())
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <p class="pd-price" style="color:#c0392b;">{{ $product->getFinalPriceFormatted() }}</p>
                        <p style="font-size:16px;color:#c9a96e;text-decoration:line-through;">{{ $product->getPriceFormatted() }}</p>
                        <span style="background:rgba(192,57,43,0.1);color:#c0392b;font-size:10px;padding:3px 8px;border-radius:100px;font-weight:500;letter-spacing:0.06em;">
                            -{{ $product->discount_percent }}%
                        </span>
                    </div>

                @else
                    <p style="font-size:13px;color:#c9a96e;font-weight:500;">{{ $product->getFinalPriceFormatted() }}</p>
                @endif
            </div>

        </div>
    </a>
    @endforeach
</div>
</section>
@endif

@guest
<div class="cta-strip">
    <h2 class="cta-title">Mulai Berjualan di Taku</h2>
    <p class="cta-sub">Buka toko kamu dan jangkau lebih banyak pembeli.</p>
    <a href="{{ route('register') }}" class="cta-btn">Daftar Sekarang</a>
</div>
@endguest
@auth
@if(Auth::user()->isUser() && !Auth::user()->store)
<div class="cta-strip">
    <h2 class="cta-title">Punya Produk untuk Dijual?</h2>
    <p class="cta-sub">Buka toko merchant kamu di Taku sekarang.</p>
    <a href="{{ route('store.register') }}" class="cta-btn">Buka Toko</a>
</div>
@endif
@endauth

<a href="https://wa.link/qf1hte" target="_blank"
   style="position:fixed;bottom:24px;right:24px;background:#25d366;color:white;width:52px;height:52px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,0.15);transition:transform 0.2s;z-index:100;"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white">...</svg>
</a>

<script>
let bannerIdx = 0;
const bannerCount = {{ $banners->count() }};
const autoSlide = {{ $banners->where('auto_slide',true)->count() > 0 ? 'true' : 'false' }};

function bannerGo(idx) {
    bannerIdx = idx;
    document.getElementById('bannerTrack').style.transform = `translateX(-${idx*100}%)`;
    document.querySelectorAll('.banner-dot').forEach((d,i) => d.classList.toggle('active', i===idx));
}
function bannerMove(dir) {
    bannerGo((bannerIdx + dir + bannerCount) % bannerCount);
}
if(autoSlide && bannerCount > 1) {
    setInterval(() => bannerMove(1), 4000);
}
</script>


<style>
.snap-slider::-webkit-scrollbar { display: none; }
.snap-slider.dragging { cursor: grabbing; user-select: none; }
</style>

<script>
function sliderDrag(el, e) {
    if(e.button !== 0) return;
    let startX = e.pageX;
    let scrollLeft = el.scrollLeft;
    let dragged = false;
    el.style.cursor = 'grabbing';
    el.style.userSelect = 'none';

    const onMove = (e) => {
        dragged = true;
        el.scrollLeft = scrollLeft - (e.pageX - startX);
    };
    const onUp = () => {
        el.style.cursor = 'grab';
        el.style.userSelect = '';
        document.removeEventListener('mousemove', onMove);
        document.removeEventListener('mouseup', onUp);
    };
    document.addEventListener('mousemove', onMove);
    document.addEventListener('mouseup', onUp);
}
</script>

@endsection
