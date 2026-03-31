@extends('layouts.app')

@section('content')

@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');

/* BANNER TIPIS */
.shop-banner {
    background: linear-gradient(160deg, #0b2a4a, #153d67);
    padding: 28px 40px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'DM Sans', sans-serif;
}
.shop-banner-label {
    font-size: 10px; letter-spacing: 0.22em;
    text-transform: uppercase; color: #c9a96e;
}
.shop-banner-sep { color: rgba(201,169,110,0.4); font-size: 12px; }
.shop-banner-current {
    font-size: 10px; letter-spacing: 0.18em;
    text-transform: uppercase; color: rgba(240,235,224,0.5);
}

/* BODY */
.shop-body {
    display: flex;
    max-width: 1300px;
    margin: 0 auto;
    padding: 40px 32px;
    font-family: 'DM Sans', sans-serif;
    align-items: flex-start;
    gap: 0;
}

/* SIDEBAR */
.shop-sidebar {
    width: 220px;
    flex-shrink: 0;
    position: sticky;
    top: 84px;
    padding-right: 36px;
    border-right: 0.5px solid rgba(11,42,74,0.08);
}

.sidebar-brand {
    font-size: 10px; letter-spacing: 0.22em;
    text-transform: uppercase; color: #c9a96e; margin-bottom: 4px;
}
.sidebar-title {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 400; font-size: 32px;
    color: #0b2a4a; margin-bottom: 28px; line-height: 1.1;
}

.sidebar-section { margin-bottom: 28px; }
.sidebar-label {
    font-size: 10px; letter-spacing: 0.18em;
    text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 12px;
}

/* Search di sidebar */
.sidebar-search {
    display: flex;
    border: 0.5px solid rgba(11,42,74,0.15);
    border-radius: 8px; overflow: hidden;
    margin-bottom: 28px;
}
.sidebar-search input {
    flex: 1; padding: 9px 12px; border: none;
    outline: none; font-size: 13px; color: #0b2a4a;
    font-family: 'DM Sans', sans-serif; background: white;
}
.sidebar-search input::placeholder { color: rgba(11,42,74,0.3); }
.sidebar-search button {
    background: #0b2a4a; border: none; cursor: pointer;
    padding: 0 14px; color: #f0ebe0; display: flex;
    align-items: center; justify-content: center;
    transition: background 0.2s;
}
.sidebar-search button:hover { background: #0d3459; }

.sidebar-select {
    width: 100%; padding: 9px 12px;
    border: 0.5px solid rgba(11,42,74,0.15);
    border-radius: 8px; font-size: 13px;
    color: #0b2a4a; background: white;
    font-family: 'DM Sans', sans-serif; outline: none;
    cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%230b2a4a' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

.price-inputs { display: flex; gap: 8px; align-items: center; margin-bottom: 10px; }
.price-input {
    flex: 1; padding: 8px 10px;
    border: 0.5px solid rgba(11,42,74,0.15);
    border-radius: 8px; font-size: 12px;
    color: #0b2a4a; font-family: 'DM Sans', sans-serif;
    outline: none; width: 100%;
}
.price-input:focus { border-color: #c9a96e; }
.price-sep { font-size: 11px; color: rgba(11,42,74,0.4); flex-shrink: 0; }

.sidebar-apply-btn {
    width: 100%; padding: 9px;
    background: #0b2a4a; color: #f0ebe0;
    border: none; border-radius: 8px;
    font-size: 11px; letter-spacing: 0.1em;
    text-transform: uppercase; font-weight: 500;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background 0.2s;
}
.sidebar-apply-btn:hover { background: #0d3459; }

.sidebar-reset {
    display: block; text-align: center; font-size: 11px;
    color: rgba(11,42,74,0.35); text-decoration: none;
    margin-top: 10px; letter-spacing: 0.08em; text-transform: uppercase;
    transition: color 0.2s;
}
.sidebar-reset:hover { color: #c9a96e; }

/* MAIN GRID */
.shop-main { flex: 1; padding-left: 40px; }

.shop-topbar {
    display: flex; justify-content: space-between;
    align-items: center; margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 0.5px solid rgba(11,42,74,0.08);
}
.shop-count { font-size: 12px; color: rgba(11,42,74,0.45); letter-spacing: 0.06em; }
.shop-count span { color: #0b2a4a; font-weight: 500; }

.shop-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1100px) { .shop-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 900px) {
    .shop-sidebar { width: 180px; }
    .shop-main { padding-left: 24px; }
}
@media (max-width: 640px) {
    .shop-body { flex-direction: column; padding: 24px 16px; }
    .shop-sidebar { width: 100%; position: static; padding-right: 0; border-right: none; border-bottom: 0.5px solid rgba(11,42,74,0.08); padding-bottom: 24px; margin-bottom: 24px; }
    .shop-main { padding-left: 0; }
    .shop-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .sidebar-title { font-size: 26px; }
}

.product-card {
    border-radius: 14px; overflow: hidden;
    border: 0.5px solid rgba(11,42,74,0.08);
    text-decoration: none; color: inherit; display: block;
    transition: transform 0.25s, box-shadow 0.25s;
    background: white;
}
.product-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(11,42,74,0.1); }

.product-card-img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; }
.product-card-info { padding: 14px 16px 16px; }
.product-card-name { font-size: 14px; font-weight: 500; color: #0b2a4a; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product-card-desc { font-size: 12px; color: rgba(11,42,74,0.45); margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product-card-footer { display: flex; justify-content: space-between; align-items: center; }
.product-card-price { font-size: 14px; font-weight: 500; color: #c9a96e; }
.product-card-btn {
    width: 30px; height: 30px; border-radius: 50%;
    background: #0b2a4a; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #f0ebe0; transition: background 0.2s, transform 0.15s;
    text-decoration: none; flex-shrink: 0;
}
.product-card-btn:hover { background: #c9a96e; transform: scale(1.1); }

.shop-empty { text-align: center; padding: 60px 0; color: rgba(11,42,74,0.35); grid-column: 1 / -1; }
.shop-empty-icon { font-size: 40px; margin-bottom: 12px; display: block; }
.shop-empty p { font-size: 14px; }
</style>

<div class="shop-banner">
    <a href="{{ route('home') }}" style="text-decoration:none;">
        <span class="shop-banner-label">Taku</span>
    </a>
    <span class="shop-banner-sep">/</span>
    <span class="shop-banner-current">{{ __('app.shop_title') }}</span>
</div>

<div class="shop-body">

    <aside class="shop-sidebar">

        <p class="sidebar-brand">Taku</p>
        <h1 class="sidebar-title">{{ __('app.shop_title') }}</h1>

        <form method="GET" action="{{ route('products') }}" class="sidebar-search" id="searchForm">
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
            <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('app.shop_search') }}">
            <button type="submit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>
        </form>

        <form method="GET" action="{{ route('products') }}" id="filterForm">
            @if($q) <input type="hidden" name="q" value="{{ $q }}"> @endif

            <div class="sidebar-section">
                <p class="sidebar-label">{{ __('app.shop_sort') }}</p>
                <select name="sort" class="sidebar-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="default" {{ $sort == 'default' ? 'selected' : '' }}>{{ __('app.shop_sort_default') }}</option>
                    <option value="name_az" {{ $sort == 'name_az' ? 'selected' : '' }}>{{ __('app.shop_sort_name_az') }}</option>
                    <option value="name_za" {{ $sort == 'name_za' ? 'selected' : '' }}>{{ __('app.shop_sort_name_za') }}</option>
                    <option value="price_lo" {{ $sort == 'price_lo' ? 'selected' : '' }}>{{ __('app.shop_sort_price_lo') }}</option>
                    <option value="price_hi" {{ $sort == 'price_hi' ? 'selected' : '' }}>{{ __('app.shop_sort_price_hi') }}</option>
                </select>
            </div>

            <div class="sidebar-section">
                <p class="sidebar-label">{{ __('app.shop_price_range') }}</p>
                <div class="price-inputs">
                    <input type="number" name="min_price" class="price-input"
                        placeholder="{{ __('app.shop_price_min') }}"
                        value="{{ $minPrice != $minPossible ? $minPrice : '' }}">
                    <span class="price-sep">—</span>
                    <input type="number" name="max_price" class="price-input"
                        placeholder="{{ __('app.shop_price_max') }}"
                        value="{{ $maxPrice != $maxPossible ? $maxPrice : '' }}">
                </div>
                <button type="submit" class="sidebar-apply-btn">{{ __('app.shop_filter') }}</button>
            </div>

            <a href="{{ route('products') }}" class="sidebar-reset">{{ __('app.shop_reset') }}</a>
        </form>

    </aside>

    <main class="shop-main">
        <div class="shop-topbar">
            <p class="shop-count">
                {{ __('app.shop_showing') }} <span>{{ $total }}</span> {{ __('app.shop_products') }}
            </p>
        </div>

        <div class="shop-grid">
            @forelse($products as $index => $item)
            @php
                $productIndex = array_search($item['name'], array_column((new App\Http\Controllers\ProductController)->products, 'name'));
            @endphp
            <a href="{{ route('product.show', $productIndex !== false ? $productIndex : $index) }}" class="product-card">
                <img src="{{ asset($item['image']) }}" class="product-card-img" alt="{{ $item['name'] }}">
                <div class="product-card-info">
                    <p class="product-card-name">{{ $item['name'] }}</p>
                    <p class="product-card-desc">{{ $item['desc'][session('lang', 'id')] }}</p>
                    <div class="product-card-footer">
                        <p class="product-card-price">{{ $item['price'] }}</p>
                        <span class="product-card-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="shop-empty">
                <span class="shop-empty-icon">🔍</span>
                <p>{{ __('app.shop_empty') }}</p>
            </div>
            @endforelse
        </div>
    </main>

</div>

<a href="https://wa.me/6281324683769" target="_blank" style="position:fixed; bottom:24px; right:24px; background:#25d366; color:white; width:52px; height:52px; border-radius:50%; text-decoration:none; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 16px rgba(0,0,0,0.15); transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">💬</a>

@endsection
