@extends('layouts.app')
@section('title', $product->name . ' — Taku')
@section('meta_description', Str::limit(strip_tags($product->description ?? $product->name . ' — tanaman premium pilihan di Taku. Estetik, berkualitas, cocok untuk ruang modern kamu.'), 155))
@section('og_image', asset($product->image ?? 'images/og-taku.jpg'))
@section('content')
@php
    app()->setLocale(session('lang', 'id'));
    $locale = session('lang', 'id');
    $product->load('variants');
    $hasVariants = $product->hasVariants();
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
:root{
    --navy:#0b2a4a;--navy-mid:rgba(11,42,74,.55);--navy-soft:rgba(11,42,74,.08);
    --gold:#c9a96e;--gold-soft:rgba(201,169,110,.12);--gold-border:rgba(201,169,110,.3);
    --beige:#f5efe6;--beige-mid:#ede5d8;--olive:#4a5240;--olive-soft:rgba(74,82,64,.08);
    --sand:#d4c5a9;--danger:#c0392b;--success:#1a7a3c;
}
.pd-wrap{max-width:1100px;margin:56px auto 80px;padding:0 32px;font-family:'DM Sans',sans-serif;}
@media(max-width:700px){.pd-wrap{padding:0 16px;margin-top:28px;}}
.pd-flash{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:12px 20px;font-size:13px;color:#2d6a2d;margin-bottom:32px;display:flex;align-items:center;gap:10px;}
.pd-flash-dot{width:6px;height:6px;border-radius:50%;background:#2d6a2d;flex-shrink:0;}
.pd-main{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:start;}
@media(max-width:700px){.pd-main{grid-template-columns:1fr;gap:24px;}}
.pd-images{position:sticky;top:84px;}
@media(max-width:700px){.pd-images{position:static !important;}}
.pd-main-img-wrap{width:100%;aspect-ratio:1/1;border-radius:16px;overflow:hidden;background:#f7f4ef;margin-bottom:14px;}
.pd-main-img{width:100%;height:100%;object-fit:cover;display:block;transition:opacity .25s;}
.pd-thumbnails{display:flex;gap:10px;flex-wrap:wrap;}
.pd-thumb{width:64px;height:64px;object-fit:cover;border-radius:8px;cursor:pointer;border:1.5px solid transparent;opacity:.6;transition:opacity .2s,border-color .2s;flex-shrink:0;}
.pd-thumb:hover{opacity:.9;}
.pd-thumb.active{border-color:var(--gold);opacity:1;}
.pd-info{position:relative;}
.pd-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:10px;}
.pd-name{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:36px;color:var(--navy);letter-spacing:.02em;line-height:1.1;margin-bottom:10px;}
.pd-price{font-size:22px;font-weight:500;color:var(--gold);}
.pd-divider{height:.5px;background:var(--navy-soft);margin:22px 0;}
.pd-detail-label{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--navy-mid);margin-bottom:10px;}
.pd-detail-text{font-size:14px;color:#555;line-height:1.8;white-space:pre-line;}

/* ── VARIANT SELECTOR ─────────────────────────────────────────────── */
.vs-label{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--navy-mid);margin-bottom:10px;}
.vs-grid{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:4px;}
.vs-chip{
    padding:8px 14px;
    border:.5px solid rgba(11,42,74,.2);
    border-radius:8px;font-size:13px;color:var(--navy);background:white;cursor:pointer;
    font-family:'DM Sans',sans-serif;transition:all .2s;
    display:flex;flex-direction:column;align-items:flex-start;gap:2px;min-width:90px;
    position:relative;
}
.vs-chip:hover:not(.vs-chip-out){border-color:var(--gold);background:var(--gold-soft);}
.vs-chip.active{border-color:var(--gold);background:var(--gold-soft);color:var(--navy);}
.vs-chip-out{opacity:.4;cursor:not-allowed;background:#f5f5f5;}
.vs-chip-size{font-size:12px;font-weight:500;line-height:1.3;}

/* Harga chip — final price */
.vs-chip-price{font-size:11px;color:var(--danger);font-weight:500;}
.vs-chip-price.no-disc{color:var(--gold);}

/* Harga coret di chip */
.vs-chip-original{font-size:10px;color:rgba(11,42,74,.3);text-decoration:line-through;}

/* Badge diskon di pojok chip */
.vs-chip-disc-badge{
    position:absolute;top:-7px;right:-7px;
    background:var(--danger);color:white;
    font-size:9px;font-weight:600;padding:1px 5px;border-radius:100px;
    letter-spacing:.04em;
}

.vs-chip-stock{font-size:10px;color:rgba(11,42,74,.4);}
.vs-chip-out .vs-chip-price{color:rgba(11,42,74,.3);}
.vs-selected-info{margin-top:6px;font-size:12px;color:rgba(11,42,74,.45);min-height:18px;}

/* ── HARGA BLOCK ─────────────────────────────────────────────────── */
.price-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.price-final-tag{font-size:22px;font-weight:500;color:var(--danger);}
.price-original-tag{font-size:16px;color:rgba(11,42,74,.35);text-decoration:line-through;}
.price-disc-badge{background:rgba(192,57,43,.1);color:var(--danger);font-size:10px;padding:3px 8px;border-radius:100px;font-weight:500;letter-spacing:.06em;}

/* ── SIZE BADGE (tunggal) ─────────────────────────────────────────── */
.size-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(74,82,64,.07);border:.5px solid rgba(74,82,64,.15);border-radius:6px;padding:5px 10px;font-size:12px;color:var(--olive);margin-right:6px;margin-bottom:6px;}
.size-badge svg{opacity:.5;}

/* ── REST ────────────────────────────────────────────────────────── */
.pd-qty-label{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--navy-mid);margin-bottom:10px;}
.pd-qty-wrap{display:inline-flex;align-items:center;border:.5px solid rgba(11,42,74,.2);border-radius:8px;overflow:hidden;}
.pd-qty-btn{background:none;border:none;cursor:pointer;width:40px;height:40px;font-size:18px;color:var(--navy);display:flex;align-items:center;justify-content:center;transition:background .15s;flex-shrink:0;}
.pd-qty-btn:hover{background:var(--navy-soft);}
.pd-qty-btn:disabled{opacity:.3;cursor:not-allowed;}
.pd-qty-input{width:48px;text-align:center;border:none;border-left:.5px solid rgba(11,42,74,.12);border-right:.5px solid rgba(11,42,74,.12);outline:none;font-size:14px;font-weight:500;color:var(--navy);height:40px;font-family:'DM Sans',sans-serif;background:white;}
.pd-actions{display:flex;gap:10px;margin-top:24px;flex-wrap:wrap;align-items:center;}
.pd-btn-primary{flex:1;min-width:120px;padding:13px 16px;background:var(--gold);color:#f0ebe0;border:none;border-radius:8px;cursor:pointer;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;font-family:'DM Sans',sans-serif;transition:all .2s;}
.pd-btn-primary:hover{background:var(--navy);transform:translateY(-1px);box-shadow:0 6px 20px rgba(11,42,74,.2);}
.pd-btn-secondary{flex:1;min-width:120px;padding:13px 16px;background:none;color:var(--navy);border:.5px solid var(--navy);border-radius:8px;cursor:pointer;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;font-family:'DM Sans',sans-serif;transition:all .2s;}
.pd-btn-secondary:hover{background:var(--navy-soft);}
.stock-ok{font-size:12px;color:#27ae60;margin-top:6px;}
.stock-low{font-size:12px;color:#e67e22;margin-top:6px;}
.stock-out{font-size:12px;color:var(--danger);margin-top:6px;}
.out-of-stock-box{background:var(--navy-soft);border:.5px solid rgba(11,42,74,.1);border-radius:8px;padding:13px 16px;font-size:12px;color:var(--navy-mid);text-align:center;width:100%;}
.wishlist-form{position:absolute;top:10px;right:10px;}
.wishlist-btn{width:42px;height:42px;border-radius:50%;border:.5px solid rgba(11,42,74,.15);background:white;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .25s ease;backdrop-filter:blur(6px);}
.wishlist-btn svg{width:18px;height:18px;stroke:var(--navy);fill:none;stroke-width:1.6;transition:all .25s ease;}
.wishlist-btn:hover{border-color:var(--gold);background:var(--gold-soft);transform:translateY(-2px) scale(1.05);box-shadow:0 6px 18px rgba(201,169,110,.2);}
.wishlist-btn:hover svg{stroke:var(--gold);}
.wishlist-btn.active{border-color:var(--gold);background:var(--gold-soft);}
.wishlist-btn.active svg{stroke:var(--gold);fill:var(--gold);}
.wishlist-btn.animate{animation:heartPop .4s ease;}
@keyframes heartPop{0%{transform:scale(1)}30%{transform:scale(1.3)}60%{transform:scale(.9)}100%{transform:scale(1)}}
.heart-burst{position:absolute;top:50%;left:50%;width:6px;height:6px;background:var(--gold);border-radius:50%;pointer-events:none;animation:burst .6s ease-out forwards;}
@keyframes burst{0%{opacity:1;transform:translate(-50%,-50%) scale(1)}100%{opacity:0;transform:translate(-50%,-50%) scale(8)}}
.login-notice{background:#f8f6f2;border:.5px solid var(--gold-border);border-radius:10px;padding:16px 20px;margin-top:24px;font-size:13px;color:var(--navy-mid);display:flex;align-items:center;gap:12px;}
.login-notice a{color:var(--navy);font-weight:500;}
.pd-section{margin-top:72px;padding-top:48px;border-top:.5px solid var(--navy-soft);}
.pd-section-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:6px;}
.pd-section-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:30px;color:var(--navy);margin-bottom:24px;}
.slider-outer{position:relative;}
.slider-outer::after{content:'';position:absolute;top:0;right:0;bottom:0;width:60px;background:linear-gradient(to right,transparent,var(--beige,#f5efe6));pointer-events:none;z-index:2;border-radius:0 12px 12px 0;}
.slider-track{display:flex;gap:16px;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;padding-bottom:10px;cursor:grab;user-select:none;scrollbar-width:none;-ms-overflow-style:none;}
.slider-track::-webkit-scrollbar{display:none;}
.slider-track.is-dragging{cursor:grabbing;}
.slider-card{flex:0 0 180px;scroll-snap-align:start;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.1);text-decoration:none;color:inherit;display:block;transition:transform .25s,box-shadow .25s;background:white;min-width:180px;}
.slider-card:hover{transform:translateY(-5px);box-shadow:0 12px 32px rgba(11,42,74,.1);}
.slider-card-img{width:100%;aspect-ratio:1/1;object-fit:cover;display:block;background:#f7f4ef;transition:opacity .2s;}
.slider-card:hover .slider-card-img{opacity:.9;}
.slider-card-body{padding:10px 12px 14px;}
.slider-card-name{font-size:13px;font-weight:500;color:var(--navy);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.4;}
.slider-card-store{font-size:10px;color:var(--navy-mid);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.slider-price-row{display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.slider-price-final{font-size:13px;color:var(--gold);font-weight:500;white-space:nowrap;}
.slider-price-original{font-size:11px;color:rgba(11,42,74,.3);text-decoration:line-through;white-space:nowrap;}
.slider-stock-out{position:absolute;top:8px;left:8px;font-size:10px;background:rgba(0,0,0,.55);color:white;padding:2px 8px;border-radius:100px;backdrop-filter:blur(4px);}
.slider-card-img-wrap{position:relative;}
@media(max-width:640px){.slider-card{flex:0 0 150px;min-width:150px;}.pd-section-title{font-size:24px;}}
</style>

<div id="toast" style="position:fixed;bottom:30px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--navy,#0b2a4a);color:white;padding:12px 20px;border-radius:999px;font-size:12px;opacity:0;pointer-events:none;transition:all .4s ease;z-index:9999;font-family:'DM Sans',sans-serif;"></div>

{{-- Pass variant data ke JS --}}
@if($hasVariants)
<script>
const VARIANTS_DATA = {
    @foreach($product->variants as $v)
    "{{ $v->id }}": {
        id:               {{ $v->id }},
        label:            "{{ addslashes($v->getLabel()) }}",
        price:            {{ $v->price }},
        finalPrice:       {{ $v->getFinalPrice() }},
        discountPercent:  {{ $v->discount_percent ?? 0 }},
        hasDiscount:      {{ $v->hasDiscount() ? 'true' : 'false' }},
        stock:            {{ $v->stock }},
    },
    @endforeach
};
</script>
@endif

<div class="pd-wrap">
    @if(session('success'))
        <div class="pd-flash"><div class="pd-flash-dot"></div>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="pd-flash" style="background:#fdf0f0;border-color:#f5c0c0;color:var(--danger);">
            <div class="pd-flash-dot" style="background:var(--danger);"></div>{{ session('error') }}
        </div>
    @endif

    <div class="pd-main">

        {{-- ── GAMBAR ─────────────────────────────────────────────────── --}}
        <div class="pd-images">
            @php
                $allImages = [];
                if($product->images->count() > 0) $allImages = $product->images->pluck('image')->toArray();
                if($product->image) array_unshift($allImages, $product->image);
                $allImages = array_unique($allImages);
                $mainImg   = $allImages[0] ?? null;
            @endphp
            <div class="pd-main-img-wrap">
                @if($mainImg)
                    <img id="mainImage" src="{{ asset($mainImg) }}" class="pd-main-img" alt="{{ $product->name }}">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:rgba(11,42,74,.15);font-size:64px;background:#f7f4ef;">🌿</div>
                @endif
            </div>
            @if(count($allImages) > 1)
            <div class="pd-thumbnails">
                @foreach($allImages as $i => $img)
                <img src="{{ asset($img) }}" class="pd-thumb {{ $i===0?'active':'' }}"
                     onclick="changeImage('{{ asset($img) }}', this)" alt="Foto {{ $i+1 }}">
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── INFO ──────────────────────────────────────────────────── --}}
        <div class="pd-info">
            <p class="pd-label">Taku</p>

            @if($product->store)
            <a href="{{ route('store.show', $product->store->slug) }}"
               style="display:inline-flex;align-items:center;gap:6px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.45);text-decoration:none;margin-bottom:8px;transition:color .2s;"
               onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='rgba(11,42,74,0.45)'">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                {{ $product->store->name }}
            </a>
            @endif

            @if($product->category)
            <span style="display:inline-block;padding:2px 10px;background:rgba(11,42,74,.05);border-radius:100px;font-size:10px;color:rgba(11,42,74,.45);letter-spacing:.08em;margin-bottom:8px;margin-left:4px;">
                {{ $product->category->icon }} {{ $product->category->name }}
            </span>
            @endif

            <h1 class="pd-name">{{ $product->name }}</h1>

            {{-- ── HARGA (diupdate JS saat variant dipilih) ─────────── --}}
            <div style="margin-bottom:20px;" id="priceBlock">
                @if($hasVariants)
                    @php
                        $minFinal = $product->variants->min(fn($v) => $v->getFinalPrice());
                        $maxFinal = $product->variants->max(fn($v) => $v->getFinalPrice());
                        $anyDisc  = $product->variants->contains(fn($v) => $v->hasDiscount());
                    @endphp
                    <p class="pd-price" id="pdPrice" style="{{ $anyDisc ? 'color:var(--danger);' : '' }}">
                        @if($minFinal === $maxFinal)
                            Rp {{ number_format($minFinal, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($minFinal, 0, ',', '.') }}
                            <span style="font-size:14px;color:rgba(11,42,74,.3);font-weight:400;">
                                — Rp {{ number_format($maxFinal, 0, ',', '.') }}
                            </span>
                        @endif
                    </p>
                    <p style="font-size:11px;color:rgba(11,42,74,.4);margin-top:4px;" id="pdPriceHint">Pilih ukuran untuk melihat harga spesifik</p>
                    <p style="display:none;font-size:13px;color:rgba(11,42,74,.3);text-decoration:line-through;margin-top:2px;" id="pdPriceOriginal"></p>
                    <span style="display:none;" id="pdDiscBadge" class="price-disc-badge"></span>
                @else
                    @if($product->hasDiscount())
                        <div class="price-row">
                            <p class="price-final-tag" id="pdPrice">{{ $product->getFinalPriceFormatted() }}</p>
                            <p class="price-original-tag">{{ $product->getPriceFormatted() }}</p>
                            <span class="price-disc-badge">-{{ $product->discount_percent }}%</span>
                        </div>
                    @else
                        <p class="pd-price" id="pdPrice">{{ $product->getPriceFormatted() }}</p>
                    @endif
                @endif
            </div>

            {{-- ── UKURAN TUNGGAL (hanya tampil kalau tidak ada variant) ── --}}
            @if(!$hasVariants && ($product->getHeightLabel() || $product->getDiameterLabel()))
            <div style="margin-bottom:16px;display:flex;flex-wrap:wrap;gap:4px;">
                @if($product->getHeightLabel())
                <span class="size-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="2" x2="12" y2="22"/><polyline points="17 7 12 2 7 7"/><polyline points="7 17 12 22 17 17"/></svg>
                    Tinggi {{ $product->getHeightLabel() }}
                </span>
                @endif
                @if($product->getDiameterLabel())
                <span class="size-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                    Ø {{ $product->getDiameterLabel() }}
                </span>
                @endif
            </div>
            @endif

            {{-- ── STOK (diupdate JS) ───────────────────────────────── --}}
            <div id="stockBlock">
                @if(!$hasVariants)
                    @if($product->stock > 0)
                        @if($product->isLowStock())
                            <p class="stock-low">⚠ Stok tersisa {{ $product->stock }}</p>
                        @else
                            <p class="stock-ok">Stok tersedia ({{ $product->stock }})</p>
                        @endif
                    @else
                        <p class="stock-out">Stok habis</p>
                    @endif
                @endif
            </div>

            @if($product->getDesc($locale))
            <div class="pd-divider"></div>
            <p class="pd-detail-label">{{ $locale==='en'?'About this product':'Tentang produk ini' }}</p>
            <p class="pd-detail-text" style="color:rgba(11,42,74,.55);font-size:13px;">{{ $product->getDesc($locale) }}</p>
            @endif

            @if($product->getDetail($locale))
            <div class="pd-divider"></div>
            <p class="pd-detail-label">{{ __('app.product_details') }}</p>
            <p class="pd-detail-text">{{ $product->getDetail($locale) }}</p>
            @endif

            <div class="pd-divider"></div>

            @auth
            @php $outOfStock = !$hasVariants && $product->stock === 0; @endphp

            {{-- ── VARIANT SELECTOR ──────────────────────────────────── --}}
            @if($hasVariants)
            <div style="margin-bottom:20px;">
                <p class="vs-label">Pilih Ukuran</p>
                <div class="vs-grid" id="variantGrid">
                    @foreach($product->variants as $v)
                    @php
                        $vOut       = $v->stock === 0;
                        $vFinal     = $v->getFinalPrice();
                        $vHasDisc   = $v->hasDiscount();
                    @endphp
                    <button type="button"
                            class="vs-chip {{ $vOut ? 'vs-chip-out' : '' }}"
                            data-variant-id="{{ $v->id }}"
                            {{ $vOut ? 'disabled' : '' }}
                            onclick="selectVariant(this)">

                        @if($vHasDisc)
                            <span class="vs-chip-disc-badge">-{{ $v->discount_percent }}%</span>
                        @endif

                        <span class="vs-chip-size">{{ $v->getLabel() }}</span>

                        <span class="vs-chip-price {{ !$vHasDisc ? 'no-disc' : '' }}">
                            Rp {{ number_format($vFinal, 0, ',', '.') }}
                        </span>

                        @if($vHasDisc)
                            <span class="vs-chip-original">Rp {{ number_format($v->price, 0, ',', '.') }}</span>
                        @endif

                        @if($vOut)
                            <span class="vs-chip-stock">Habis</span>
                        @elseif($v->stock <= 5)
                            <span class="vs-chip-stock">Sisa {{ $v->stock }}</span>
                        @endif
                    </button>
                    @endforeach
                </div>
                <p class="vs-selected-info" id="vsSelectedInfo">— Belum ada ukuran dipilih</p>
            </div>
            @endif

            @if(!$outOfStock)
            <p class="pd-qty-label">{{ __('app.quantity') }}</p>
            <div class="pd-qty-wrap">
                <button class="pd-qty-btn" type="button" onclick="decrease()" id="btnMinus">−</button>
                <input type="text" id="qty" value="1" class="pd-qty-input"
                       data-max="{{ !$hasVariants && $product->stock > 0 ? $product->stock : 999 }}" readonly>
                <button class="pd-qty-btn" type="button" onclick="increase()">+</button>
            </div>
            @endif

            <form id="cartForm" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="formVariantId" value="">
                <input type="hidden" name="qty" id="formQty" value="1">
                <input type="hidden" name="action" id="formAction" value="add_to_cart">

                <div class="pd-actions" id="actionArea">
                    @if($outOfStock)
                        <div class="out-of-stock-box">Stok habis — tidak bisa dipesan</div>
                    @elseif($hasVariants)
                        <button type="button" class="pd-btn-primary" id="btnBuyNow"
                                onclick="submitCart('buy_now')" disabled style="opacity:.4;cursor:not-allowed;">
                            {{ __('app.buy_now') }}
                        </button>
                        <button type="button" class="pd-btn-secondary" id="btnAddCart"
                                onclick="submitCart('add_to_cart')" disabled style="opacity:.4;cursor:not-allowed;">
                            {{ __('app.add_to_cart') }}
                        </button>
                    @else
                        <button type="button" class="pd-btn-primary" onclick="submitCart('buy_now')">
                            {{ __('app.buy_now') }}
                        </button>
                        <button type="button" class="pd-btn-secondary" onclick="submitCart('add_to_cart')">
                            {{ __('app.add_to_cart') }}
                        </button>
                    @endif
                </div>
            </form>

            <form action="{{ route('wishlist.toggle', $product) }}"
                  method="POST" class="wishlist-form js-wishlist-form" data-id="{{ $product->id }}">
                @csrf
                <button type="submit" class="wishlist-btn js-wishlist-btn {{ $isWishlisted ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </button>
            </form>

            @else
            <div class="login-notice">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $locale==='en'?'Please':'Silakan' }}
                <a href="{{ route('login') }}">login</a>
                {{ $locale==='en'?'to add this item to cart.':'untuk menambahkan ke keranjang.' }}
            </div>
            @endauth
        </div>
    </div>

    {{-- Slider: produk lain dari toko --}}
    @if(isset($storeProducts) && $storeProducts->count() > 0)
    <div class="pd-section">
        <p class="pd-section-label">Taku</p>
        <h2 class="pd-section-title">
            @if($product->store) Lainnya dari {{ $product->store->name }}
            @else Lainnya dari Taku Official @endif
        </h2>
        <div class="slider-outer">
            <div class="slider-track" id="sliderStore">
                @foreach($storeProducts as $item)
                @php $itemImg = $item->image ?? ($item->images->first()->image ?? null); $stockOut = $item->stock === 0; @endphp
                <a href="{{ route('product.show', $item->slug) }}" class="slider-card">
                    <div class="slider-card-img-wrap">
                        @if($itemImg)
                            <img src="{{ asset($itemImg) }}" class="slider-card-img" alt="{{ $item->name }}"
                                 style="{{ $stockOut ? 'opacity:.5;filter:grayscale(.4);' : '' }}"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        @else
                            <div class="slider-card-img" style="display:flex;align-items:center;justify-content:center;color:rgba(11,42,74,.15);font-size:36px;">🌿</div>
                        @endif
                        @if($stockOut) <span class="slider-stock-out">Habis</span>
                        @elseif($item->hasDiscount()) <span style="position:absolute;top:8px;left:8px;font-size:9px;background:rgba(192,57,43,.9);color:white;padding:2px 7px;border-radius:100px;font-weight:500;">-{{ $item->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="slider-card-body">
                        <p class="slider-card-name">{{ $item->name }}</p>
                        <div class="slider-price-row">
                            @if($item->hasVariants())
                                <span class="slider-price-final" style="font-size:11px;">Mulai Rp {{ number_format($item->getMinVariantPrice(), 0, ',', '.') }}</span>
                            @elseif($item->hasDiscount())
                                <span class="slider-price-final" style="color:var(--danger);">{{ $item->getFinalPriceFormatted() }}</span>
                                <span class="slider-price-original">{{ $item->getPriceFormatted() }}</span>
                            @else
                                <span class="slider-price-final">{{ $item->getFinalPriceFormatted() }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Slider: produk lain --}}

    @if(isset($others) && $others->count() > 0)
    <div class="pd-section">
        <p class="pd-section-label">Taku</p>
        <h2 class="pd-section-title">{{ __('app.other_products') }}</h2>
        <div class="slider-outer">
            <div class="slider-track" id="sliderOthers">
                @foreach($others as $item)
                @php $itemImg = $item->image ?? ($item->images->first()->image ?? null); $stockOut = $item->stock === 0; @endphp
                <a href="{{ route('product.show', $item->slug) }}" class="slider-card">
                    <div class="slider-card-img-wrap">
                        @if($itemImg)
                            <img src="{{ asset($itemImg) }}" class="slider-card-img" alt="{{ $item->name }}"
                                 style="{{ $stockOut ? 'opacity:.5;filter:grayscale(.4);' : '' }}"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        @else
                            <div class="slider-card-img" style="display:flex;align-items:center;justify-content:center;color:rgba(11,42,74,.15);font-size:36px;">🌿</div>
                        @endif
                        @if($stockOut) <span class="slider-stock-out">Habis</span>
                        @elseif($item->hasDiscount()) <span style="position:absolute;top:8px;left:8px;font-size:9px;background:rgba(192,57,43,.9);color:white;padding:2px 7px;border-radius:100px;font-weight:500;">-{{ $item->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="slider-card-body">
                        <p class="slider-card-name">{{ $item->name }}</p>
                        @if($item->store)
                        <p class="slider-card-store">
                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:inline;vertical-align:middle;margin-right:2px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                            {{ $item->store->name }}
                        </p>
                        @endif
                        <div class="slider-price-row">
                            @if($item->hasVariants())
                                <span class="slider-price-final" style="font-size:11px;">Mulai Rp {{ number_format($item->getMinVariantPrice(), 0, ',', '.') }}</span>
                            @elseif($item->hasDiscount())
                                <span class="slider-price-final" style="color:var(--danger);">{{ $item->getFinalPriceFormatted() }}</span>
                                <span class="slider-price-original">{{ $item->getPriceFormatted() }}</span>
                            @else
                                <span class="slider-price-final">{{ $item->getFinalPriceFormatted() }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<a href="https://wa.link/qf1hte" target="_blank"
   style="position:fixed;bottom:24px;right:24px;background:#25d366;color:white;width:52px;height:52px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,.15);transition:transform .2s;z-index:100;"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
</a>

<script>
function changeImage(src, el) {
    const img = document.getElementById('mainImage');
    if(img){ img.style.opacity='.5'; img.src=src; img.onload=()=>{ img.style.opacity='1'; }; }
    document.querySelectorAll('.pd-thumb').forEach(t=>t.classList.remove('active'));
    el.classList.add('active');
}
function increase(){
    const q=document.getElementById('qty'); if(!q) return;
    const max=parseInt(q.dataset.max)||999;
    if(parseInt(q.value)<max) q.value=parseInt(q.value)+1;
    document.getElementById('btnMinus').disabled=false;
}
function decrease(){
    const q=document.getElementById('qty'); if(!q) return;
    if(parseInt(q.value)>1) q.value=parseInt(q.value)-1;
}
function submitCart(action){
    @if($hasVariants)
    if(!document.getElementById('formVariantId').value){
        showToast('Pilih ukuran terlebih dahulu 🌿');
        document.getElementById('variantGrid')?.scrollIntoView({behavior:'smooth',block:'nearest'});
        return;
    }
    @endif
    document.getElementById('formQty').value=document.getElementById('qty')?.value||1;
    document.getElementById('formAction').value=action;
    document.getElementById('cartForm').submit();
}

// ── Variant selector — update harga + stok + tampilan coret ───────────
function selectVariant(btn) {
    document.querySelectorAll('.vs-chip').forEach(c=>c.classList.remove('active'));
    btn.classList.add('active');

    const v = VARIANTS_DATA[btn.dataset.variantId];
    if (!v) return;

    // Hidden input
    document.getElementById('formVariantId').value = v.id;

    // Update price block
    const priceEl    = document.getElementById('pdPrice');
    const origEl     = document.getElementById('pdPriceOriginal');
    const badgeEl    = document.getElementById('pdDiscBadge');
    const hintEl     = document.getElementById('pdPriceHint');

    if (priceEl) {
        priceEl.textContent = 'Rp ' + v.finalPrice.toLocaleString('id-ID');
        priceEl.style.color = v.hasDiscount ? 'var(--danger)' : 'var(--gold)';
    }
    if (hintEl) hintEl.style.display = 'none';

    if (origEl) {
        if (v.hasDiscount) {
            origEl.textContent = 'Rp ' + v.price.toLocaleString('id-ID');
            origEl.style.display = 'block';
        } else {
            origEl.style.display = 'none';
        }
    }
    if (badgeEl) {
        if (v.hasDiscount) {
            badgeEl.textContent = '-' + v.discountPercent + '%';
            badgeEl.style.display = 'inline-block';
        } else {
            badgeEl.style.display = 'none';
        }
    }

    // Update stok
    const stockBlock = document.getElementById('stockBlock');
    if(stockBlock){
        if(v.stock===0) stockBlock.innerHTML='<p class="stock-out">Stok habis</p>';
        else if(v.stock<=5) stockBlock.innerHTML='<p class="stock-low">⚠ Stok tersisa '+v.stock+'</p>';
        else stockBlock.innerHTML='<p class="stock-ok">Stok tersedia ('+v.stock+')</p>';
    }

    // Update qty max
    const qtyEl = document.getElementById('qty');
    if(qtyEl){
        qtyEl.dataset.max = v.stock > 0 ? v.stock : 999;
        if(parseInt(qtyEl.value) > v.stock && v.stock > 0) qtyEl.value = v.stock;
    }

    // Info teks
    const infoEl = document.getElementById('vsSelectedInfo');
    if(infoEl) infoEl.textContent = '✓ ' + v.label + ' dipilih';

    // Aktifkan/nonaktifkan tombol
    const enabled = v.stock > 0;
    ['btnBuyNow','btnAddCart'].forEach(id => {
        const b = document.getElementById(id);
        if(b){ b.disabled=!enabled; b.style.opacity=enabled?'1':'.4'; b.style.cursor=enabled?'pointer':'not-allowed'; }
    });
}

function showToast(text){
    const t=document.getElementById('toast');
    t.innerText=text; t.style.opacity='1'; t.style.transform='translateX(-50%) translateY(0)';
    setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateX(-50%) translateY(20px)'; },2200);
}

document.querySelectorAll('.js-wishlist-form').forEach(form=>{
    form.addEventListener('submit', async function(e){
        e.preventDefault();
        const btn=form.querySelector('.js-wishlist-btn');
        btn.classList.remove('animate'); void btn.offsetWidth; btn.classList.add('animate');
        try{
            const res=await fetch(form.action,{method:'POST',headers:{'X-CSRF-TOKEN':form.querySelector('input[name=_token]').value,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
            const data=await res.json();
            if(data.status==='added'){ btn.classList.add('active'); createBurst(btn); showToast('❤️ Ditambahkan ke wishlist'); }
            else{ btn.classList.remove('active'); showToast('💔 Dihapus dari wishlist'); }
            if(data.count!==undefined) document.querySelectorAll('.wishlist-count').forEach(el=>el.innerText=data.count);
        }catch(err){ console.error(err); }
    });
});
function createBurst(el){ const b=document.createElement('span'); b.classList.add('heart-burst'); el.appendChild(b); setTimeout(()=>b.remove(),600); }

function initDragScroll(el){
    if(!el) return;
    let isDown=false,startX=0,scrollLeft=0,moved=false;
    el.addEventListener('mousedown',e=>{isDown=true;moved=false;startX=e.pageX-el.offsetLeft;scrollLeft=el.scrollLeft;el.classList.add('is-dragging');});
    el.addEventListener('mouseleave',()=>{isDown=false;el.classList.remove('is-dragging');});
    el.addEventListener('mouseup',()=>{isDown=false;el.classList.remove('is-dragging');});
    el.addEventListener('mousemove',e=>{if(!isDown)return;e.preventDefault();const x=e.pageX-el.offsetLeft;const walk=(x-startX)*1.4;if(Math.abs(walk)>4)moved=true;el.scrollLeft=scrollLeft-walk;});
    el.addEventListener('click',e=>{if(moved){e.preventDefault();e.stopPropagation();}},true);
}
document.addEventListener('DOMContentLoaded',()=>{
    initDragScroll(document.getElementById('sliderStore'));
    initDragScroll(document.getElementById('sliderOthers'));
});
</script>
@endsection
