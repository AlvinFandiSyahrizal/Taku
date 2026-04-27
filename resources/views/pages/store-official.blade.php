@extends('layouts.app')
@section('title', 'Taku Official — Koleksi Tanaman Pilihan Tim Taku')
@section('meta_description', 'Belanja langsung dari Taku Official Store. Setiap tanaman dikurasi dengan cermat oleh tim kami — kualitas terjamin, estetik terjaga.')
@section('content')
@php
    app()->setLocale(session('lang','id'));
    $baseUrl = route('store.official');
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
:root{
    --beige:#f5f0e8;--cream:#ede6d6;--sand:#d4c4a8;--sand-lt:#ece3d4;
    --olive:#6b7c5c;--olive-dk:#4a5940;--olive-lt:#c8d4b8;
    --stone:#8c7b6b;--stone-lt:#b8a898;--bark:#3b2e22;
}

.store-hero{background:var(--cream);border-bottom:1px solid var(--sand);padding:44px 40px;display:flex;align-items:center;gap:28px;position:relative;overflow:hidden;}
.store-hero::before{content:'';position:absolute;right:-40px;bottom:-40px;width:220px;height:220px;border-radius:50%;border:1px solid rgba(212,196,168,.45);pointer-events:none;}
.store-hero::after{content:'';position:absolute;right:80px;top:-60px;width:130px;height:130px;border-radius:50%;border:1px solid rgba(212,196,168,.25);pointer-events:none;}
.official-avatar{width:80px;height:80px;border-radius:16px;background:var(--sand-lt);border:2px solid var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.store-info{flex:1;min-width:0;}
.store-label{font-size:9px;letter-spacing:.28em;text-transform:uppercase;color:var(--stone-lt);font-family:'DM Sans',sans-serif;margin-bottom:6px;}
.store-name{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:36px;color:var(--bark);letter-spacing:.02em;margin-bottom:6px;line-height:1.1;}
.store-desc{font-size:13px;color:var(--stone);font-family:'DM Sans',sans-serif;line-height:1.7;max-width:500px;}
.store-meta{display:flex;gap:20px;margin-top:10px;flex-wrap:wrap;}
.store-meta-item{font-size:11px;color:var(--stone-lt);font-family:'DM Sans',sans-serif;}
.store-actions{display:flex;flex-direction:column;align-items:flex-end;gap:10px;flex-shrink:0;position:relative;z-index:10;}
.badge-official{background:#eaf0e4;color:var(--olive-dk);border:.5px solid #c0ceb0;font-size:9px;letter-spacing:.12em;text-transform:uppercase;padding:5px 14px;border-radius:100px;font-family:'DM Sans',sans-serif;}
.btn-wa{display:inline-flex !important;align-items:center;gap:8px;padding:11px 20px;background:var(--olive-dk) !important;color:var(--olive-lt) !important;border:none !important;border-radius:8px;font-size:11px;letter-spacing:.08em;text-transform:uppercase;text-decoration:none !important;font-family:'DM Sans',sans-serif;font-weight:500;transition:background .2s,transform .15s;box-shadow:0 2px 8px rgba(74,89,64,.15);}
.btn-wa:hover{background:var(--olive) !important;transform:translateY(-1px);}

.store-banner-wrap{position:relative;overflow:hidden;max-height:380px;}
.store-banner-track{display:flex;transition:transform .5s ease;}
.store-banner-slide{min-width:100%;position:relative;flex-shrink:0;}
.store-banner-slide img{width:100%;max-height:380px;object-fit:cover;display:block;}
.store-banner-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(59,46,34,.55) 0%,rgba(59,46,34,.08) 60%,transparent 100%);display:flex;align-items:center;padding:0 52px;}
.store-banner-content{max-width:440px;}
.store-banner-title{font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:300;font-style:italic;color:#f5f0e8;line-height:1.1;margin-bottom:8px;}
.store-banner-sub{font-size:13px;color:rgba(245,240,232,.7);margin-bottom:20px;font-family:'DM Sans',sans-serif;}
.store-banner-cta{display:inline-block;padding:10px 26px;background:var(--olive-dk);color:var(--beige);border-radius:7px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:500;text-decoration:none;transition:background .2s;}
.store-banner-cta:hover{background:var(--olive);}
.banner-nav-btn{position:absolute;top:50%;transform:translateY(-50%);background:rgba(245,240,232,.18);border:none;cursor:pointer;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#f5f0e8;z-index:5;transition:background .2s;}
.banner-nav-btn:hover{background:rgba(245,240,232,.32);}
.banner-prev{left:14px;}.banner-next{right:14px;}
.banner-dots{position:absolute;bottom:14px;left:50%;transform:translateX(-50%);display:flex;gap:6px;}
.banner-dot{width:5px;height:5px;border-radius:50%;background:rgba(245,240,232,.4);cursor:pointer;transition:background .2s;}
.banner-dot.active{background:#f5f0e8;}

.store-body-wrap{background:var(--beige);}
.store-body{max-width:1280px;margin:0 auto;padding:44px 40px 80px;}

.section-hd{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:14px;}
.section-label-txt{font-size:9px;letter-spacing:.28em;text-transform:uppercase;color:var(--olive);font-family:'DM Sans',sans-serif;margin-bottom:5px;}
.section-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:30px;color:var(--bark);}
.section-divider{width:40px;height:1px;background:var(--sand);margin-bottom:20px;}
.snap-slider{overflow-x:auto;scroll-snap-type:x mandatory;display:flex;gap:14px;padding-bottom:8px;scrollbar-width:none;cursor:grab;}
.snap-slider::-webkit-scrollbar{display:none;}
.snap-slider.dragging{cursor:grabbing;user-select:none;}

.catalog-wrap{display:flex;gap:28px;align-items:flex-start;margin-top:52px;padding-top:40px;border-top:1px solid var(--sand);}
.cat-sidebar{width:220px;flex-shrink:0;position:sticky;top:20px;}
.cat-sidebar-title{font-size:9px;letter-spacing:.28em;text-transform:uppercase;color:var(--olive);font-family:'DM Sans',sans-serif;margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--sand);}
.cat-list{list-style:none;padding:0;margin:0;}
.cat-list li{margin-bottom:2px;}
.cat-link{display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border-radius:8px;font-size:12px;color:var(--stone);font-family:'DM Sans',sans-serif;text-decoration:none;transition:all .15s;cursor:pointer;}
.cat-link:hover{background:var(--sand-lt);color:var(--bark);}
.cat-link.active{background:var(--olive-dk);color:var(--olive-lt);}
.cat-link-inner{display:flex;align-items:center;gap:8px;}
.cat-icon{font-size:14px;line-height:1;}
.cat-chevron{transition:transform .2s;color:var(--stone-lt);font-size:10px;flex-shrink:0;}
.cat-chevron.open{transform:rotate(90deg);}
.sub-cat-list{list-style:none;padding:0 0 0 28px;margin:0;overflow:hidden;max-height:0;transition:max-height .3s ease;}
.sub-cat-list.open{max-height:400px;}
.sub-cat-list li{margin-bottom:1px;}
.sub-cat-link{display:block;padding:6px 10px;border-radius:6px;font-size:11px;color:var(--stone);font-family:'DM Sans',sans-serif;text-decoration:none;transition:all .15s;}
.sub-cat-link:hover{background:var(--sand-lt);color:var(--bark);}
.sub-cat-link.active{background:var(--olive-lt);color:var(--olive-dk);font-weight:500;}
.catalog-main{flex:1;min-width:0;}
.catalog-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;}
.catalog-info{font-size:12px;color:var(--stone-lt);font-family:'DM Sans',sans-serif;}
.catalog-info strong{color:var(--stone);}
.breadcrumb-row{display:flex;align-items:center;gap:6px;margin-bottom:16px;flex-wrap:wrap;}
.bc-item{font-size:11px;color:var(--stone-lt);font-family:'DM Sans',sans-serif;text-decoration:none;}
.bc-item:hover{color:var(--olive);}
.bc-sep{font-size:11px;color:var(--stone-lt);}
.bc-current{font-size:11px;color:var(--bark);font-weight:500;font-family:'DM Sans',sans-serif;}
.sort-bar{display:flex;gap:6px;align-items:center;}
.sort-label{font-size:11px;color:var(--stone-lt);font-family:'DM Sans',sans-serif;margin-right:4px;}
.sort-select{padding:6px 12px;border-radius:8px;font-size:11px;border:1px solid var(--sand);background:var(--cream);color:var(--stone);font-family:'DM Sans',sans-serif;cursor:pointer;outline:none;}
.sort-select:focus{border-color:var(--olive);}

.prod-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;}
.prod-card{border-radius:12px;overflow:hidden;border:1px solid var(--sand);background:var(--cream);text-decoration:none !important;color:inherit;display:block;transition:transform .25s,box-shadow .25s,border-color .2s;}
.prod-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(59,46,34,.1);border-color:var(--stone-lt);}
.prod-card-img{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:var(--sand-lt);}
.prod-card-info{padding:10px 12px 12px;}
.prod-card-cat{font-size:9px;color:var(--olive);letter-spacing:.07em;text-transform:uppercase;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.prod-card-name{font-size:12px;font-weight:500;color:var(--bark);margin-bottom:5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.prod-card-price{font-size:12px;color:var(--olive-dk);font-weight:500;display:flex;align-items:baseline;gap:5px;flex-wrap:wrap;}
.prod-card-original{font-size:10px;color:var(--stone-lt);text-decoration:line-through;}
.prod-card-disc{font-size:9px;background:rgba(74,89,64,.1);color:var(--olive-dk);padding:2px 5px;border-radius:100px;}
.prod-grid-2{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;}
.prod-grid-3{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;}
.snap-slider .prod-card{flex-shrink:0;scroll-snap-align:start;}

.pagination-wrap{display:flex;justify-content:center;align-items:center;gap:6px;margin-top:36px;padding-top:28px;border-top:1px solid var(--sand);}
.pg-btn{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;text-decoration:none;color:var(--stone);border:1px solid var(--sand);background:var(--cream);transition:all .2s;}
.pg-btn:hover{background:var(--sand-lt);color:var(--olive-dk);border-color:var(--olive);}
.pg-btn.active{background:var(--olive-dk);color:var(--olive-lt);border-color:var(--olive-dk);}
.pg-btn.disabled{opacity:.4;pointer-events:none;}
.pg-dots{font-size:12px;color:var(--stone-lt);padding:0 4px;}
.empty-wrap{text-align:center;padding:80px 0;color:var(--stone);}
.empty-title{font-family:'Cormorant Garamond',serif;font-size:28px;margin-bottom:8px;color:var(--olive-dk);}

.wa-float{position:fixed;bottom:24px;right:24px;background:#25D366;color:white;width:52px;height:52px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(37,211,102,.3);transition:transform .2s,background .2s;z-index:100;}
.wa-float:hover{background:#1ebe5d;transform:scale(1.08);}

@media(max-width:1024px){.prod-grid{grid-template-columns:repeat(4,1fr);}}
@media(max-width:768px){.catalog-wrap{flex-direction:column;}.cat-sidebar{width:100%;position:static;}.prod-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:640px){
    .store-hero{flex-wrap:wrap;padding:28px 20px;gap:16px;}
    .store-actions{flex-direction:row;align-items:center;width:100%;}
    .store-body{padding:28px 16px 60px;}
    .prod-grid{grid-template-columns:repeat(2,1fr);gap:10px;}
    .store-banner-overlay{padding:0 24px;}
    .store-banner-title{font-size:28px;}
    .snap-slider .prod-card{width:160px !important;}
}
</style>

<div class="store-hero">
    <div class="official-avatar">
        <img src="{{ asset('images/logotaku.png') }}" style="max-height:52px;width:auto;opacity:.85;" alt="{{ $store->name }}" onerror="this.style.display='none'">
    </div>

    <div class="store-info">
        <p class="store-label">Toko Official · Taku</p>
        <h1 class="store-name">{{ $store->name }}</h1>
        @if($store->description)<p class="store-desc">{{ $store->description }}</p>@endif
        <div class="store-meta">
            <span class="store-meta-item">{{ $totalProducts }} produk</span>
            <span class="store-meta-item">Taku Marketplace</span>
        </div>
    </div>

    <div class="store-actions">
        <span class="badge-official">✦ Official Store</span>
        @if(!empty($store->phone))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $store->phone) }}"
           target="_blank" class="btn-wa">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
            Chat Support
        </a>
        @endif
    </div>
</div>

@if($bannersTop->count() > 0)
    @include('partials.store-banner-slider', ['banners' => $bannersTop, 'sliderId' => 'offBannerTop'])
@endif

<div class="store-body-wrap">
<div class="store-body">

    @foreach($sections as $section)
    @if($section->products->count() > 0)
    <section style="margin-bottom:48px;">
        <div class="section-hd">
            <div>
                @if($section->subtitle)<p class="section-label-txt">{{ $section->subtitle }}</p>@endif
                <h2 class="section-title">{{ $section->title }}</h2>
            </div>
        </div>
        <div class="section-divider"></div>

        @if($section->rows === 1)
        <div class="snap-slider" onmousedown="sliderDrag(this,event)">
            @foreach($section->products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="prod-card" style="width:200px;">
                <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}" class="prod-card-img" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="prod-card-info">
                    @if($product->category)<p class="prod-card-cat">{{ $product->category->name }}</p>@endif
                    <p class="prod-card-name">{{ $product->name }}</p>
                    <p class="prod-card-price">
                        {{ $product->getFinalPriceFormatted() }}
                        @if($product->hasDiscount())<span class="prod-card-original">{{ $product->getPriceFormatted() }}</span>@endif
                    </p>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="{{ $section->rows === 2 ? 'prod-grid-2' : 'prod-grid-3' }}">
            @foreach($section->products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="prod-card">
                <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}" class="prod-card-img" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="prod-card-info">
                    @if($product->category)<p class="prod-card-cat">{{ $product->category->name }}</p>@endif
                    <p class="prod-card-name">{{ $product->name }}</p>
                    <p class="prod-card-price">{{ $product->getFinalPriceFormatted() }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </section>
    @endif
    @endforeach

    @if($bannersAfterSections->count() > 0)
    <div style="margin-bottom:48px;border-radius:12px;overflow:hidden;">
        @include('partials.store-banner-slider', ['banners' => $bannersAfterSections, 'sliderId' => 'offBannerMid'])
    </div>
    @endif

    <div class="catalog-wrap" id="catalogSection">

        <aside class="cat-sidebar">
            <p class="cat-sidebar-title">Kategori</p>
            <ul class="cat-list">
                <li>
                    <a href="{{ $baseUrl }}" class="cat-link {{ !$categorySlug && !$subSlug ? 'active' : '' }}">
                        <span class="cat-link-inner"><span>Semua Produk</span></span>
                        <span style="font-size:10px;opacity:.7;">{{ $totalProducts }}</span>
                    </a>
                </li>
                @foreach($sidebarCategories as $cat)
                @php
                    $isCatActive = $categorySlug === $cat->slug;
                    $hasChildren = $cat->children->count() > 0;
                    $isExpanded  = $isCatActive || ($hasChildren && $cat->children->contains('slug', $subSlug));
                    $catProdCount = $cat->products()->whereNull('store_id')->where('is_active', true)->count();
                @endphp
                <li>
                    <a href="{{ $baseUrl }}?category={{ $cat->slug }}"
                       class="cat-link {{ $isCatActive && !$subSlug ? 'active' : '' }}"
                       @if($hasChildren) onclick="toggleSub(event,'sub-{{ $cat->id }}')" @endif>
                        <span class="cat-link-inner">
                            @if($cat->icon)<span class="cat-icon">{{ $cat->icon }}</span>@endif
                            <span>{{ $cat->name }}</span>
                        </span>
                        @if($hasChildren)
                            <span class="cat-chevron {{ $isExpanded ? 'open' : '' }}" id="chev-{{ $cat->id }}">›</span>
                        @else
                            <span style="font-size:10px;opacity:.6;">{{ $catProdCount }}</span>
                        @endif
                    </a>
                    @if($hasChildren)
                    <ul class="sub-cat-list {{ $isExpanded ? 'open' : '' }}" id="sub-{{ $cat->id }}">
                        @foreach($cat->children as $child)
                        @php $childCount = $child->products()->whereNull('store_id')->where('is_active', true)->count(); @endphp
                        <li>
                            <a href="{{ $baseUrl }}?category={{ $cat->slug }}&sub={{ $child->slug }}"
                               class="sub-cat-link {{ $subSlug === $child->slug ? 'active' : '' }}">
                                {{ $child->icon ? $child->icon.' ' : '' }}{{ $child->name }}
                                <span style="font-size:10px;color:var(--stone-lt);margin-left:4px;">({{ $childCount }})</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </aside>

        <div class="catalog-main">

            @if($activeCategory || $activeSubCategory)
            <div class="breadcrumb-row">
                <a href="{{ $baseUrl }}" class="bc-item">Semua</a>
                @if($activeCategory)
                    <span class="bc-sep">›</span>
                    @if($activeSubCategory)
                        <a href="{{ $baseUrl }}?category={{ $activeCategory->slug }}" class="bc-item">{{ $activeCategory->name }}</a>
                        <span class="bc-sep">›</span>
                        <span class="bc-current">{{ $activeSubCategory->name }}</span>
                    @else
                        <span class="bc-current">{{ $activeCategory->name }}</span>
                    @endif
                @endif
            </div>
            @endif

            <div class="catalog-topbar">
                <div class="catalog-info">
                    Menampilkan <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                    dari <strong>{{ $products->total() }}</strong> produk
                    @if($activeSubCategory) dalam <strong>{{ $activeSubCategory->name }}</strong>
                    @elseif($activeCategory) dalam <strong>{{ $activeCategory->name }}</strong>
                    @endif
                </div>
                <div class="sort-bar">
                    <span class="sort-label">Urutkan:</span>
                    <select class="sort-select" onchange="changeSort(this.value)">
                        <option value="latest"     {{ $sort==='latest'     ? 'selected':'' }}>Terbaru</option>
                        <option value="oldest"     {{ $sort==='oldest'     ? 'selected':'' }}>Terlama</option>
                        <option value="price_asc"  {{ $sort==='price_asc'  ? 'selected':'' }}>Harga ↑</option>
                        <option value="price_desc" {{ $sort==='price_desc' ? 'selected':'' }}>Harga ↓</option>
                        <option value="name"       {{ $sort==='name'       ? 'selected':'' }}>Nama A–Z</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)
            <div class="prod-grid" id="prodGrid">
                @foreach($products as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="prod-card">
                    <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}" class="prod-card-img" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="prod-card-info">
                        @if($product->category)<p class="prod-card-cat">{{ $product->category->name }}</p>@endif
                        <p class="prod-card-name">{{ $product->name }}</p>
                        <p class="prod-card-price">
                            {{ $product->getFinalPriceFormatted() }}
                            @if($product->hasDiscount())
                                <span class="prod-card-original">{{ $product->getPriceFormatted() }}</span>
                                <span class="prod-card-disc">-{{ $product->discount_percent }}%</span>
                            @endif
                        </p>
                    </div>
                </a>
                @endforeach
            </div>

            @if($products->lastPage() > 1)
            <div class="pagination-wrap">
                @if($products->onFirstPage())
                    <span class="pg-btn disabled"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="pg-btn" onclick="scrollToCatalog(event,this.href)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
                @endif

                @php
                    $current = $products->currentPage();
                    $last    = $products->lastPage();
                    $pages   = [];
                    for ($i = 1; $i <= $last; $i++) {
                        if ($i === 1 || $i === $last || abs($i - $current) <= 2) $pages[] = $i;
                    }
                    $pages = array_unique($pages); sort($pages);
                @endphp
                @php $prev = null; @endphp
                @foreach($pages as $page)
                    @if($prev !== null && $page - $prev > 1)<span class="pg-dots">…</span>@endif
                    @if($page === $current)
                        <span class="pg-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $products->url($page) }}" class="pg-btn" onclick="scrollToCatalog(event,this.href)">{{ $page }}</a>
                    @endif
                    @php $prev = $page; @endphp
                @endforeach

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="pg-btn" onclick="scrollToCatalog(event,this.href)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
                @else
                    <span class="pg-btn disabled"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
                @endif
            </div>
            @endif

            @else
            <div class="empty-wrap">
                <p class="empty-title">Belum ada produk</p>
                <p style="font-size:13px;font-family:'DM Sans',sans-serif;">
                    {{ $categorySlug || $subSlug ? 'Tidak ada produk di kategori ini.' : 'Produk official akan segera hadir.' }}
                </p>
            </div>
            @endif
        </div>
    </div>

    @if($bannersBottom->count() > 0)
    <div style="margin-top:48px;border-radius:12px;overflow:hidden;">
        @include('partials.store-banner-slider', ['banners' => $bannersBottom, 'sliderId' => 'offBannerBot'])
    </div>
    @endif

</div>
</div>

<a href="https://wa.link/qf1hte" target="_blank" class="wa-float" title="Chat Support Taku">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
</a>

<script>
function scrollToCatalog(e, href) {
    e.preventDefault();
    window.history.pushState({}, '', href);
    fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
            const parser   = new DOMParser();
            const doc      = parser.parseFromString(html, 'text/html');
            const newGrid  = doc.getElementById('prodGrid');
            const newPager = doc.querySelector('.pagination-wrap');
            const newInfo  = doc.querySelector('.catalog-info');
            if (newGrid)  document.getElementById('prodGrid').outerHTML  = newGrid.outerHTML;
            if (newPager) {
                const pager = document.querySelector('.pagination-wrap');
                if (pager) pager.outerHTML = newPager.outerHTML;
                else document.querySelector('.catalog-main').insertAdjacentHTML('beforeend', newPager.outerHTML);
            }
            if (newInfo) document.querySelector('.catalog-info').outerHTML = newInfo.outerHTML;
            bindPagination();
            document.getElementById('catalogSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(() => { window.location.href = href; });
}

function bindPagination() {
    document.querySelectorAll('.pg-btn:not(.disabled):not(.active)').forEach(btn => {
        if (btn.href) btn.onclick = (e) => scrollToCatalog(e, btn.href);
    });
}

function changeSort(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', val);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function toggleSub(e, id) {
    const list  = document.getElementById(id);
    const catId = id.replace('sub-', '');
    const chev  = document.getElementById('chev-' + catId);
    if (!list || list.classList.contains('open')) return;
    e.preventDefault();
    list.classList.toggle('open');
    if (chev) chev.classList.toggle('open');
}

function sliderDrag(el, e) {
    if (e.button !== 0) return;
    const startX = e.pageX, scrollLeft = el.scrollLeft;
    el.classList.add('dragging');
    const onMove = ev => { el.scrollLeft = scrollLeft - (ev.pageX - startX); };
    const onUp   = () => { el.classList.remove('dragging'); document.removeEventListener('mousemove', onMove); document.removeEventListener('mouseup', onUp); };
    document.addEventListener('mousemove', onMove);
    document.addEventListener('mouseup', onUp);
}

bindPagination();
</script>

@endsection
