@extends('layouts.app')
@section('title', 'Koleksi Tanaman Premium — Taku')
@section('meta_description', 'Jelajahi koleksi lengkap tanaman hias premium di Taku. Filter berdasarkan kategori, ukuran, dan harga.')
@section('content')
@php app()->setLocale(session('lang','id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}

:root{
    --navy:#0b2a4a;
    --gold:#c9a96e;
    --beige:#f5f1e8;
    --beige2:#faf8f5;
    --danger:#c0392b;
    --navy-soft:rgba(11,42,74,.08);
    --navy-mid:rgba(11,42,74,.45);
}

/* ── Breadcrumb ───────────────────────────────── */
.shop-banner{
    background:var(--beige);padding:14px 40px;display:flex;align-items:center;
    gap:8px;font-family:'DM Sans',sans-serif;border-bottom:.5px solid rgba(11,42,74,.08);
}
.shop-banner-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);text-decoration:none;}
.shop-banner-sep{color:rgba(11,42,74,.25);font-size:12px;}
.shop-banner-current{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(11,42,74,.5);}

/* ── Layout ───────────────────────────────────── */
.shop-body{display:flex;max-width:1300px;margin:0 auto;padding:40px 32px;font-family:'DM Sans',sans-serif;align-items:flex-start;gap:0;}

/* ── Sidebar ──────────────────────────────────── */
.shop-sidebar{
    width:220px;flex-shrink:0;position:sticky;top:64px;
    max-height:calc(100vh - 64px);overflow-y:auto;
    padding-right:32px;padding-top:8px;padding-bottom:24px;
    border-right:.5px solid rgba(11,42,74,.08);align-self:flex-start;
    scrollbar-width:thin;scrollbar-color:rgba(11,42,74,.1) transparent;
}
.sidebar-brand{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:4px;}
.sidebar-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:28px;color:var(--navy);margin-bottom:24px;line-height:1.1;}

/* Search */
.sidebar-search{display:flex;border:.5px solid rgba(11,42,74,.15);border-radius:8px;overflow:hidden;margin-bottom:24px;}
.sidebar-search input{flex:1;padding:9px 12px;border:none;outline:none;font-size:13px;color:var(--navy);font-family:'DM Sans',sans-serif;background:white;}
.sidebar-search input::placeholder{color:rgba(11,42,74,.3);}
.sidebar-search button{background:var(--navy);border:none;cursor:pointer;padding:0 12px;color:#f0ebe0;display:flex;align-items:center;justify-content:center;transition:background .2s;}
.sidebar-search button:hover{background:#0d3459;}

/* Filter accordion */
.filter-section{margin-bottom:6px;border:.5px solid rgba(11,42,74,.08);border-radius:10px;overflow:hidden;}
.filter-toggle{
    width:100%;display:flex;align-items:center;justify-content:space-between;
    padding:11px 14px;background:white;border:none;cursor:pointer;
    font-size:11px;letter-spacing:.12em;text-transform:uppercase;
    color:rgba(11,42,74,.5);font-family:'DM Sans',sans-serif;
    transition:background .2s;
}
.filter-toggle:hover{background:rgba(11,42,74,.02);}
.filter-toggle.open{color:var(--navy);}
.filter-toggle-arrow{width:14px;height:14px;transition:transform .2s;flex-shrink:0;}
.filter-toggle.open .filter-toggle-arrow{transform:rotate(180deg);}
.filter-body{display:none;padding:0 14px 12px;background:white;border-top:.5px solid rgba(11,42,74,.05);}
.filter-body.open{display:block;}

/* Active badge di toggle */
.filter-active-dot{width:6px;height:6px;border-radius:50%;background:var(--gold);flex-shrink:0;margin-left:4px;}

/* Filter links */
.filter-link{
    display:flex;align-items:center;justify-content:space-between;
    padding:7px 0;font-size:12px;text-decoration:none;
    border-bottom:.5px solid rgba(11,42,74,.04);transition:color .2s;
}
.filter-link:last-child{border-bottom:none;}
.filter-link:hover{color:var(--navy);}
.filter-link.active{color:var(--navy);font-weight:600;}
.filter-link:not(.active){color:rgba(11,42,74,.5);}
.filter-check{width:14px;height:14px;accent-color:var(--navy);flex-shrink:0;margin-right:6px;cursor:pointer;}
.filter-count{font-size:10px;color:rgba(11,42,74,.3);margin-left:auto;}

/* Harga */
.price-inputs{display:flex;gap:6px;align-items:center;margin:10px 0 8px;}
.price-input{flex:1;padding:7px 9px;border:.5px solid rgba(11,42,74,.15);border-radius:7px;font-size:12px;color:var(--navy);font-family:'DM Sans',sans-serif;outline:none;width:100%;}
.price-input:focus{border-color:var(--gold);}
.price-sep{font-size:11px;color:rgba(11,42,74,.4);flex-shrink:0;}
.sidebar-apply-btn{width:100%;padding:9px;background:var(--navy);color:#f0ebe0;border:none;border-radius:8px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.sidebar-apply-btn:hover{background:#0d3459;}
.sidebar-reset{display:block;text-align:center;font-size:11px;color:rgba(11,42,74,.35);text-decoration:none;margin-top:12px;letter-spacing:.08em;text-transform:uppercase;transition:color .2s;}
.sidebar-reset:hover{color:var(--gold);}

/* ── Main ─────────────────────────────────────── */
.shop-main{flex:1;padding-left:36px;min-width:0;}

/* Sort chips (ganti dropdown) */
.sort-chips{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px;}
.sort-chip{
    padding:6px 14px;border-radius:100px;font-size:11px;
    letter-spacing:.08em;text-transform:uppercase;
    border:.5px solid rgba(11,42,74,.15);background:white;
    color:rgba(11,42,74,.55);cursor:pointer;font-family:'DM Sans',sans-serif;
    text-decoration:none;transition:all .2s;white-space:nowrap;
}
.sort-chip:hover{border-color:rgba(11,42,74,.3);color:var(--navy);}
.sort-chip.active{background:var(--navy);color:#f0ebe0;border-color:var(--navy);}

.shop-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:14px;border-bottom:.5px solid rgba(11,42,74,.08);}
.shop-count{font-size:12px;color:rgba(11,42,74,.45);}
.shop-count span{color:var(--navy);font-weight:500;}

/* Active filters bar */
.active-filters{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px;}
.af-chip{
    display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
    background:rgba(11,42,74,.06);border:.5px solid rgba(11,42,74,.12);
    border-radius:100px;font-size:11px;color:var(--navy);
    text-decoration:none;transition:all .2s;
}
.af-chip:hover{background:rgba(192,57,43,.08);border-color:rgba(192,57,43,.2);color:var(--danger);}
.af-chip svg{opacity:.5;}

/* Grid */
.shop-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;}
@media(max-width:1100px){.shop-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:800px){.shop-grid{grid-template-columns:repeat(2,1fr);gap:10px;}}

/* Product card */
.product-card{border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);text-decoration:none;color:inherit;display:block;transition:transform .25s,box-shadow .25s;background:white;}
.product-card:hover{transform:translateY(-5px);box-shadow:0 12px 32px rgba(11,42,74,.1);}
.product-card-img-wrap{position:relative;width:100%;aspect-ratio:1;overflow:hidden;background:#f5f1e8;}
.product-card-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s;}
.product-card:hover .product-card-img-wrap img{transform:scale(1.03);}

/* Badge diskon */
.badge-discount{
    position:absolute;top:8px;left:8px;
    background:var(--danger);color:white;
    font-size:10px;font-weight:600;padding:3px 8px;border-radius:100px;
    letter-spacing:.04em;font-family:'DM Sans',sans-serif;z-index:2;
}
/* Badge stok habis */
.badge-out{
    position:absolute;inset:0;background:rgba(0,0,0,.3);
    display:flex;align-items:center;justify-content:center;z-index:2;
}
.badge-out span{
    background:rgba(0,0,0,.6);color:white;font-size:10px;
    padding:4px 10px;border-radius:100px;font-family:'DM Sans',sans-serif;letter-spacing:.06em;
}

.product-card-info{padding:10px 12px 12px;}
.product-card-store{font-size:10px;color:var(--gold);letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-card-name{font-size:13px;font-weight:500;color:var(--navy);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-card-desc{font-size:11px;color:rgba(11,42,74,.4);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-card-footer{display:flex;justify-content:space-between;align-items:center;gap:6px;}
.price-wrap{display:flex;flex-direction:column;gap:1px;}
.product-card-price{font-size:13px;font-weight:500;color:var(--gold);}
.product-card-price.discounted{color:var(--danger);}
.product-card-original{font-size:10px;color:rgba(11,42,74,.3);text-decoration:line-through;}
.product-card-btn{width:28px;height:28px;border-radius:50%;background:var(--navy);display:flex;align-items:center;justify-content:center;color:#f0ebe0;flex-shrink:0;transition:background .2s;}
.product-card-btn:hover{background:var(--gold);}

.shop-empty{text-align:center;padding:60px 0;color:rgba(11,42,74,.35);grid-column:1/-1;}

/* ── Mobile ───────────────────────────────────── */
@media(max-width:640px){
    .shop-body{flex-direction:column;padding:20px 16px;}
    .shop-sidebar{width:100%;position:static;max-height:none;padding-right:0;border-right:none;border-bottom:.5px solid rgba(11,42,74,.08);padding-bottom:20px;margin-bottom:20px;overflow:visible;}
    .shop-main{padding-left:0;}
    .shop-banner{padding:14px 16px;}
}
</style>

{{-- Breadcrumb --}}
<div class="shop-banner">
    <a href="{{ route('home') }}" class="shop-banner-label">Taku</a>
    <span class="shop-banner-sep">/</span>
    <span class="shop-banner-current">Semua Produk</span>
</div>

<div class="shop-body">

    {{-- ── SIDEBAR ──────────────────────────────────────────── --}}
    <aside class="shop-sidebar">
        <p class="sidebar-brand">Taku</p>
        <h1 class="sidebar-title">Semua Produk</h1>

        {{-- Search --}}
        <form method="GET" action="{{ route('products') }}" class="sidebar-search">
            @if(request('sort'))     <input type="hidden" name="sort"     value="{{ request('sort') }}">     @endif
            @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('store'))   <input type="hidden" name="store"    value="{{ request('store') }}">    @endif
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari produk...">
            <button type="submit">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>

        {{-- Filter form --}}
        <form method="GET" action="{{ route('products') }}" id="filterForm">
            @if($q)              <input type="hidden" name="q"    value="{{ $q }}">              @endif
            @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

            {{-- ── KATEGORI (accordion) ──── --}}
            @if(isset($categories) && $categories->count() > 0)
            @php $activeCat = request('category'); @endphp
            <div class="filter-section">
                <button type="button" class="filter-toggle {{ $activeCat ? 'open' : '' }}"
                        onclick="toggleFilter('cat')">
                    <span style="display:flex;align-items:center;gap:4px;">
                        Kategori
                        @if($activeCat)<span class="filter-active-dot"></span>@endif
                    </span>
                    <svg class="filter-toggle-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="filter-body {{ $activeCat ? 'open' : '' }}" id="filter-cat">
                    <a href="{{ route('products', array_merge(request()->except('category'), [])) }}"
                       class="filter-link {{ !$activeCat ? 'active' : '' }}" style="padding-top:10px;">
                        Semua Kategori
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                       class="filter-link {{ $activeCat === $cat->slug ? 'active' : '' }}">
                        <span>{{ $cat->icon ? $cat->icon.' ' : '' }}{{ $cat->name }}</span>
                        <span class="filter-count">{{ $cat->products_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── TOKO (accordion) ──── --}}
            @if(isset($stores) && $stores->count() > 0)
            @php $activeStore = request('store'); @endphp
            <div class="filter-section">
                <button type="button" class="filter-toggle {{ $activeStore ? 'open' : '' }}"
                        onclick="toggleFilter('store')">
                    <span style="display:flex;align-items:center;gap:4px;">
                        Toko
                        @if($activeStore)<span class="filter-active-dot"></span>@endif
                    </span>
                    <svg class="filter-toggle-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="filter-body {{ $activeStore ? 'open' : '' }}" id="filter-store">
                    <a href="{{ route('products', array_merge(request()->except('store'), [])) }}"
                       class="filter-link {{ !$activeStore ? 'active' : '' }}" style="padding-top:10px;">
                        Semua Toko
                    </a>
                    @foreach($stores as $st)
                    <a href="{{ route('products', array_merge(request()->except('store'), ['store' => $st->slug])) }}"
                       class="filter-link {{ $activeStore === $st->slug ? 'active' : '' }}">
                        <span>{{ $st->name }}</span>
                        <span class="filter-count">{{ $st->products_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── HARGA (accordion) ──── --}}
            @php
                $priceActive = (request('min_price') && request('min_price') != $minPossible)
                            || (request('max_price') && request('max_price') != $maxPossible);
            @endphp
            <div class="filter-section">
                <button type="button" class="filter-toggle {{ $priceActive ? 'open' : '' }}"
                        onclick="toggleFilter('price')">
                    <span style="display:flex;align-items:center;gap:4px;">
                        Rentang Harga
                        @if($priceActive)<span class="filter-active-dot"></span>@endif
                    </span>
                    <svg class="filter-toggle-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="filter-body {{ $priceActive ? 'open' : '' }}" id="filter-price">
                    <div class="price-inputs">
                        <input type="number" name="min_price" class="price-input" placeholder="Min"
                               value="{{ $priceActive && request('min_price') ? request('min_price') : '' }}">
                        <span class="price-sep">—</span>
                        <input type="number" name="max_price" class="price-input" placeholder="Max"
                               value="{{ $priceActive && request('max_price') ? request('max_price') : '' }}">
                    </div>
                    <button type="submit" class="sidebar-apply-btn">Terapkan</button>
                </div>
            </div>

            <a href="{{ route('products') }}" class="sidebar-reset">Reset Semua Filter</a>
        </form>
    </aside>

    {{-- ── MAIN ─────────────────────────────────────────────── --}}
    <main class="shop-main">

        {{-- Sort chips — horizontal, bukan dropdown --}}
        <div class="sort-chips">
            @php
                $sorts = [
                    'default'  => 'Terbaru',
                    'name_az'  => 'A–Z',
                    'name_za'  => 'Z–A',
                    'price_lo' => 'Harga ↑',
                    'price_hi' => 'Harga ↓',
                ];
            @endphp
            @foreach($sorts as $val => $label)
            <a href="{{ route('products', array_merge(request()->except('sort'), ['sort' => $val])) }}"
               class="sort-chip {{ $sort === $val ? 'active' : '' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Active filter chips --}}
        @php
            $hasFilters = request('category') || request('store') || request('q')
                       || (request('min_price') && request('min_price') != $minPossible)
                       || (request('max_price') && request('max_price') != $maxPossible);
        @endphp
        @if($hasFilters)
        <div class="active-filters">
            @if(request('q'))
            <a href="{{ route('products', array_merge(request()->except('q'), [])) }}" class="af-chip">
                "{{ request('q') }}"
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
            @if(request('category'))
            @php $activeCatName = $categories->firstWhere('slug', request('category'))?->name ?? request('category'); @endphp
            <a href="{{ route('products', array_merge(request()->except('category'), [])) }}" class="af-chip">
                {{ $activeCatName }}
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
            @if(request('store'))
            @php $activeStoreName = $stores->firstWhere('slug', request('store'))?->name ?? request('store'); @endphp
            <a href="{{ route('products', array_merge(request()->except('store'), [])) }}" class="af-chip">
                {{ $activeStoreName }}
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
            @if(request('min_price') || request('max_price'))
            <a href="{{ route('products', array_merge(request()->except(['min_price','max_price']), [])) }}" class="af-chip">
                Harga: Rp{{ number_format(request('min_price',0),0,',','.') }} – Rp{{ number_format(request('max_price',999999999),0,',','.') }}
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
            @endif
        </div>
        @endif

        <div class="shop-topbar">
            <p class="shop-count">Menampilkan <span>{{ $total }}</span> produk</p>
        </div>

        {{-- ── GRID PRODUK ──────────────────────────── --}}
        <div class="shop-grid">
            @forelse($products as $product)
            @php
                $prodImg     = $product->image ?? ($product->images->first()->image ?? null);
                $hasDisc     = $product->hasDiscount();
                $hasVariants = $product->relationLoaded('variants') && $product->variants->isNotEmpty();
                $stockOut    = !$hasVariants && $product->stock === 0;
            @endphp
            <a href="{{ route('product.show', $product->slug) }}" class="product-card">
                <div class="product-card-img-wrap">
                    @if($prodImg)
                        <img src="{{ asset($prodImg) }}"
                             alt="{{ $product->name }}"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:40px;color:rgba(11,42,74,.1);">🌿</div>
                    @endif

                    {{-- Badge diskon --}}
                    @if($hasDisc)
                    <span class="badge-discount">-{{ $product->discount_percent }}%</span>
                    @endif

                    {{-- Badge stok habis --}}
                    @if($stockOut)
                    <div class="badge-out"><span>Stok Habis</span></div>
                    @endif
                </div>

                <div class="product-card-info">
                    <p class="product-card-store">
                        {{ $product->store?->name ?? 'Taku Official' }}
                    </p>
                    <p class="product-card-name">{{ $product->name }}</p>
                    @if($product->getDesc(session('lang','id')))
                    <p class="product-card-desc">{{ $product->getDesc(session('lang','id')) }}</p>
                    @endif
                    <div class="product-card-footer">
                        <div class="price-wrap">
                            @if($hasVariants)
                                <span class="product-card-price" style="font-size:11px;">
                                    Mulai Rp {{ number_format($product->variants->min('price'),0,',','.') }}
                                </span>
                            @elseif($hasDisc)
                                <span class="product-card-price discounted">{{ $product->getFinalPriceFormatted() }}</span>
                                <span class="product-card-original">{{ $product->getPriceFormatted() }}</span>
                            @else
                                <span class="product-card-price">{{ $product->getFinalPriceFormatted() }}</span>
                            @endif
                        </div>
                        <span class="product-card-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="shop-empty">
                <p style="font-size:14px;margin-bottom:12px;">Tidak ada produk yang sesuai filter.</p>
                <a href="{{ route('products') }}"
                   style="display:inline-block;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.5);text-decoration:none;border:.5px solid rgba(11,42,74,.15);border-radius:6px;padding:8px 16px;">
                    Reset Filter
                </a>
            </div>
            @endforelse
        </div>
    </main>
</div>

<a href="https://wa.link/qf1hte" target="_blank"
   style="position:fixed;bottom:24px;right:24px;background:#25d366;color:white;width:52px;height:52px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,.15);transition:transform .2s;z-index:100;"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
</a>

<script>
// ── Filter accordion ───────────────────────────────────────────────────────
function toggleFilter(id) {
    const body   = document.getElementById('filter-' + id);
    const toggle = body.previousElementSibling;
    const isOpen = body.classList.contains('open');

    body.classList.toggle('open', !isOpen);
    toggle.classList.toggle('open', !isOpen);
}
</script>

@endsection
