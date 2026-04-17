@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang','id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}

.shop-banner{background:#f5f1e8;padding:16px 40px;display:flex;align-items:center;gap:8px;font-family:'DM Sans',sans-serif;border-bottom:.5px solid rgba(11,42,74,.08);}
.shop-banner-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;text-decoration:none;}
.shop-banner-sep{color:rgba(11,42,74,.25);font-size:12px;}
.shop-banner-current{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(11,42,74,.5);}

.shop-body{display:flex;max-width:1300px;margin:0 auto;padding:40px 32px;font-family:'DM Sans',sans-serif;align-items:flex-start;gap:0;}

.shop-sidebar{
    width:220px;
    flex-shrink:0;
    position:sticky;
    top:64px;          
    max-height:calc(100vh - 64px);
    overflow-y:auto;
    padding-right:32px;
    padding-top:8px;
    padding-bottom:24px;
    border-right:.5px solid rgba(11,42,74,.08);
    scrollbar-width:thin;
    scrollbar-color:rgba(11,42,74,.1) transparent;
    align-self:flex-start;  
}
.sidebar-brand{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:4px;}
.sidebar-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:28px;color:#0b2a4a;margin-bottom:24px;line-height:1.1;}
.sidebar-search{display:flex;border:.5px solid rgba(11,42,74,.15);border-radius:8px;overflow:hidden;margin-bottom:24px;}
.sidebar-search input{flex:1;padding:9px 12px;border:none;outline:none;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;background:white;}
.sidebar-search input::placeholder{color:rgba(11,42,74,.3);}
.sidebar-search button{background:#0b2a4a;border:none;cursor:pointer;padding:0 12px;color:#f0ebe0;display:flex;align-items:center;justify-content:center;}
.sidebar-section{margin-bottom:22px;}
.sidebar-label{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:10px;}
.sidebar-select{width:100%;padding:8px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:12px;color:#0b2a4a;background:white;font-family:'DM Sans',sans-serif;outline:none;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%230b2a4a' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:28px;}
.price-inputs{display:flex;gap:6px;align-items:center;margin-bottom:8px;}
.price-input{flex:1;padding:7px 9px;border:.5px solid rgba(11,42,74,.15);border-radius:7px;font-size:12px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;width:100%;}
.price-input:focus{border-color:#c9a96e;}
.price-sep{font-size:11px;color:rgba(11,42,74,.4);flex-shrink:0;}
.sidebar-apply-btn{width:100%;padding:9px;background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;}
.sidebar-apply-btn:hover{background:#0d3459;}
.sidebar-reset{display:block;text-align:center;font-size:11px;color:rgba(11,42,74,.35);text-decoration:none;margin-top:8px;letter-spacing:.08em;text-transform:uppercase;transition:color .2s;}
.sidebar-reset:hover{color:#c9a96e;}

.filter-link{display:flex;align-items:center;justify-content:space-between;padding:7px 0;font-size:12px;text-decoration:none;border-bottom:.5px solid rgba(11,42,74,.04);transition:color .2s;}
.filter-link:last-child{border-bottom:none;}
.filter-link:hover{color:#0b2a4a;}
.filter-link.active{color:#0b2a4a;font-weight:500;}
.filter-link:not(.active){color:rgba(11,42,74,.45);}
.filter-count{font-size:10px;color:rgba(11,42,74,.3);}

.shop-main{flex:1;padding-left:36px;min-width:0;}
.shop-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:14px;border-bottom:.5px solid rgba(11,42,74,.08);}
.shop-count{font-size:12px;color:rgba(11,42,74,.45);}
.shop-count span{color:#0b2a4a;font-weight:500;}

.shop-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;}
@media(max-width:1100px){.shop-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:640px){.shop-grid{grid-template-columns:repeat(2,1fr);gap:10px;}}

@media(max-width:1100px){.shop-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:640px){
    .shop-body{flex-direction:column;padding:20px 16px;}
    .shop-sidebar{width:100%;position:static;max-height:none;padding-right:0;border-right:none;border-bottom:.5px solid rgba(11,42,74,.08);padding-bottom:20px;margin-bottom:20px;overflow:visible;}
    .shop-main{padding-left:0;}
    .shop-grid{grid-template-columns:repeat(2,1fr);gap:12px;}
}

.product-card{border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);text-decoration:none;color:inherit;display:block;transition:transform .25s,box-shadow .25s;background:white;}
.product-card:hover{transform:translateY(-5px);box-shadow:0 12px 32px rgba(11,42,74,.1);}
.product-card-img{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#f5f1e8;}
.product-card-info{padding:10px 12px 12px;}
.product-card-store{font-size:10px;color:#c9a96e;letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-card-name{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-card-desc{font-size:11px;color:rgba(11,42,74,.4);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-card-footer{display:flex;justify-content:space-between;align-items:center;gap:6px;}
.price-wrap{display:flex;flex-direction:column;gap:1px;}
.product-card-price{font-size:13px;font-weight:500;color:#c9a96e;}
.product-card-original{font-size:10px;color:rgba(11,42,74,.3);text-decoration:line-through;}
.discount-badge{display:inline-block;font-size:9px;background:rgba(192,57,43,.1);color:#c0392b;padding:2px 6px;border-radius:100px;letter-spacing:.04em;font-weight:500;white-space:nowrap;}
.product-card-btn{width:28px;height:28px;border-radius:50%;background:#0b2a4a;display:flex;align-items:center;justify-content:center;color:#f0ebe0;flex-shrink:0;transition:background .2s;}
.product-card-btn:hover{background:#c9a96e;}

.shop-empty{text-align:center;padding:60px 0;color:rgba(11,42,74,.35);grid-column:1/-1;}
</style>

<div class="shop-banner">
    <a href="{{ route('home') }}" class="shop-banner-label">Taku</a>
    <span class="shop-banner-sep">/</span>
    <span class="shop-banner-current">{{ __('app.shop_title') }}</span>
</div>

<div class="shop-body">
    <aside class="shop-sidebar">
        <p class="sidebar-brand">Taku</p>
        <h1 class="sidebar-title">{{ __('app.shop_title') }}</h1>

        {{-- Search --}}
        <form method="GET" action="{{ route('products') }}" class="sidebar-search">
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('store')) <input type="hidden" name="store" value="{{ request('store') }}"> @endif
            <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('app.shop_search') }}">
            <button type="submit">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>

        {{-- Filter form --}}
        <form method="GET" action="{{ route('products') }}" id="filterForm">
            @if($q) <input type="hidden" name="q" value="{{ $q }}"> @endif
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('store')) <input type="hidden" name="store" value="{{ request('store') }}"> @endif

            {{-- Kategori --}}
            @if(isset($categories) && $categories->count() > 0)
            <div class="sidebar-section">
                <p class="sidebar-label">Kategori</p>
                <a href="{{ route('products', array_merge(request()->except('category'), [])) }}"
                   class="filter-link {{ !request('category') ? 'active' : '' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('products', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                   class="filter-link {{ request('category')===$cat->slug ? 'active' : '' }}">
                    <span>{{ $cat->icon ? $cat->icon.' ' : '' }}{{ $cat->name }}</span>
                    <span class="filter-count">{{ $cat->products_count }}</span>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Filter Toko --}}
            @if(isset($stores) && $stores->count() > 0)
            <div class="sidebar-section">
                <p class="sidebar-label">Toko</p>
                <a href="{{ route('products', array_merge(request()->except('store'), [])) }}"
                   class="filter-link {{ !request('store') ? 'active' : '' }}">
                    Semua Toko
                </a>
                @foreach($stores as $st)
                <a href="{{ route('products', array_merge(request()->except('store'), ['store' => $st->slug])) }}"
                   class="filter-link {{ request('store')===$st->slug ? 'active' : '' }}">
                    <span>{{ $st->name }}</span>
                    <span class="filter-count">{{ $st->products_count }}</span>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Urutan --}}
            <div class="sidebar-section">
                <p class="sidebar-label">{{ __('app.shop_sort') }}</p>
                <select name="sort" class="sidebar-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="default" {{ $sort==='default'?'selected':'' }}>Default</option>
                    <option value="name_az"  {{ $sort==='name_az' ?'selected':'' }}>Nama A–Z</option>
                    <option value="name_za"  {{ $sort==='name_za' ?'selected':'' }}>Nama Z–A</option>
                    <option value="price_lo" {{ $sort==='price_lo'?'selected':'' }}>Harga Terendah</option>
                    <option value="price_hi" {{ $sort==='price_hi'?'selected':'' }}>Harga Tertinggi</option>
                </select>
            </div>

            {{-- Harga --}}
            <div class="sidebar-section">
                <p class="sidebar-label">Rentang Harga</p>
                <div class="price-inputs">
                    <input type="number" name="min_price" class="price-input" placeholder="Min"
                           value="{{ $minPrice != $minPossible ? $minPrice : '' }}">
                    <span class="price-sep">—</span>
                    <input type="number" name="max_price" class="price-input" placeholder="Max"
                           value="{{ $maxPrice != $maxPossible ? $maxPrice : '' }}">
                </div>
                <button type="submit" class="sidebar-apply-btn">Filter</button>
            </div>

            <a href="{{ route('products') }}" class="sidebar-reset">Reset Filter</a>
        </form>
    </aside>

    <main class="shop-main">
        <div class="shop-topbar">
            <p class="shop-count">Menampilkan <span>{{ $total }}</span> produk</p>
        </div>

        <div class="shop-grid">
            @forelse($products as $product)
            <a href="{{ route('product.show', $product->id) }}" class="product-card">
                <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}"
                     class="product-card-img" alt="{{ $product->name }}"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="product-card-info">
                    @if($product->store)
                    <p class="product-card-store">{{ $product->store->name }}</p>
                    @else
                    <p class="product-card-store">Taku Official</p>
                    @endif
                    <p class="product-card-name">{{ $product->name }}</p>
                    @if($product->getDesc(session('lang','id')))
                    <p class="product-card-desc">{{ $product->getDesc(session('lang','id')) }}</p>
                    @endif
                    <div class="product-card-footer">
                        <div class="price-wrap">
                            <span class="product-card-price">{{ $product->getFinalPriceFormatted() }}</span>
                            @if($product->hasDiscount())
                                <span class="product-card-original">{{ $product->getPriceFormatted() }}</span>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            @if($product->hasDiscount())
                                <span class="discount-badge">-{{ $product->discount_percent }}%</span>
                            @endif
                            <span class="product-card-btn">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="shop-empty">
                <p style="font-size:14px;">Tidak ada produk yang sesuai filter.</p>
                <a href="{{ route('products') }}" style="display:inline-block;margin-top:12px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.5);text-decoration:none;border:.5px solid rgba(11,42,74,.15);border-radius:6px;padding:8px 16px;">Reset Filter</a>
            </div>
            @endforelse
        </div>
    </main>
</div>

<a href="https://wa.link/qf1hte" target="_blank"
   style="position:fixed;bottom:24px;right:24px;background:#25d366;color:white;width:52px;height:52px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,0.15);transition:transform 0.2s;z-index:100;"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white">...</svg>
</a>

@endsection
