@extends('admin.layouts.sidebar')
@section('page-title', $store->name)
@section('content')

<style>
*{box-sizing:border-box}
:root{
    --beige:#f5f0e8; --cream:#ede6d6; --sand:#d4c4a8; --sand-lt:#ece3d4;
    --olive:#6b7c5c; --olive-dk:#4a5940; --olive-lt:#c8d4b8;
    --stone:#8c7b6b; --bark:#3b2e22; --terra:#c4694f;
}

.back-link{
    display:inline-flex;align-items:center;gap:6px;
    font-size:11px;letter-spacing:.08em;color:var(--stone);
    text-decoration:none;margin-bottom:18px;
    transition:color .2s;
}
.back-link:hover{color:var(--olive-dk);}

.store-hero{
    background:var(--olive-dk);border-radius:14px;
    padding:28px 32px;margin-bottom:20px;
    display:flex;align-items:flex-start;justify-content:space-between;
    gap:20px;flex-wrap:wrap;position:relative;overflow:hidden;
}
.store-hero::before{
    content:'';position:absolute;right:-40px;bottom:-40px;
    width:200px;height:200px;border-radius:50%;
    border:1px solid rgba(200,212,184,.1);pointer-events:none;
}
.store-hero::after{
    content:'';position:absolute;right:60px;top:-50px;
    width:120px;height:120px;border-radius:50%;
    border:1px solid rgba(200,212,184,.07);pointer-events:none;
}
.hero-left{flex:1;min-width:0;}
.hero-avatar-row{display:flex;align-items:center;gap:14px;margin-bottom:12px;}
.hero-avatar{width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid rgba(200,212,184,.3);}
.hero-avatar-init{
    width:52px;height:52px;border-radius:50%;
    background:rgba(200,212,184,.12);border:2px solid rgba(200,212,184,.25);
    display:flex;align-items:center;justify-content:center;
    font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--olive-lt);
}
.hero-name{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:300;color:#f5f0e8;line-height:1.1;margin-bottom:4px;}
.hero-meta{font-size:12px;color:rgba(200,212,184,.55);margin-bottom:2px;}
.hero-desc{font-size:12px;color:rgba(200,212,184,.35);margin-top:8px;max-width:500px;line-height:1.6;}
.hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0;position:relative;z-index:2;}
.status-badge{
    display:inline-block;padding:5px 14px;border-radius:100px;
    font-size:9px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;
}
.hero-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;}
.btn-hero{
    display:inline-flex;align-items:center;gap:6px;
    padding:7px 14px;border-radius:7px;font-size:10px;
    letter-spacing:.1em;text-transform:uppercase;
    font-family:'DM Sans',sans-serif;font-weight:500;
    text-decoration:none;transition:all .2s;cursor:pointer;border:none;
}
.btn-hero-outline{background:rgba(200,212,184,.12);color:var(--olive-lt);border:1px solid rgba(200,212,184,.25);}
.btn-hero-outline:hover{background:rgba(200,212,184,.22);color:var(--olive-lt);}

.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.stat-grid{grid-template-columns:1fr 1fr;gap:10px;}}
.stat-card{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);padding:18px 20px;
}
.stat-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--stone);margin-bottom:8px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:400;color:var(--bark);line-height:1;margin-bottom:4px;}
.stat-sub{font-size:11px;color:var(--stone);}

.filter-bar{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);padding:14px 18px;margin-bottom:20px;
}
.filter-form{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.filter-select{
    border:1px solid var(--sand);border-radius:8px;
    padding:7px 11px;font-size:12px;color:var(--bark);
    background:var(--beige);outline:none;
    font-family:'DM Sans',sans-serif;transition:.2s;cursor:pointer;
}
.filter-select:focus{border-color:var(--olive);background:white;}
.btn-filter{
    background:var(--olive-dk);color:var(--olive-lt);border:none;
    border-radius:8px;padding:7px 16px;font-size:11px;
    letter-spacing:.1em;text-transform:uppercase;
    cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;
}
.btn-filter:hover{background:var(--olive);}
.btn-reset{
    border:1px solid var(--sand);border-radius:6px;
    padding:6px 12px;font-size:11px;text-decoration:none;
    color:var(--stone);font-family:'DM Sans',sans-serif;transition:all .2s;
}
.btn-reset:hover{color:var(--bark);border-color:var(--stone);}
.btn-export{
    background:var(--terra);color:white;border-radius:8px;
    padding:7px 14px;font-size:11px;letter-spacing:.08em;text-transform:uppercase;
    text-decoration:none;font-family:'DM Sans',sans-serif;
    margin-left:auto;transition:background .2s;
}
.btn-export:hover{background:#a3553d;color:white;}

.charts-row{display:grid;grid-template-columns:3fr 2fr;gap:16px;margin-bottom:16px;}
@media(max-width:768px){.charts-row{grid-template-columns:1fr;}}
.chart-card{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);padding:22px;
}
.chart-title{
    font-size:9px;letter-spacing:.18em;text-transform:uppercase;
    color:var(--olive);margin-bottom:16px;font-family:'DM Sans',sans-serif;
    display:flex;align-items:center;gap:8px;
}
.growth-tag{
    font-size:11px;text-transform:none;letter-spacing:0;font-weight:500;
}
.no-data-placeholder{
    height:180px;display:flex;flex-direction:column;
    align-items:center;justify-content:center;
    color:var(--stone);gap:8px;
}
.no-data-placeholder svg{opacity:.3;}

.top-products-card{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);padding:22px;margin-bottom:16px;
}
.top-product-item{
    display:flex;align-items:center;gap:12px;
    padding:10px 0;border-bottom:.5px solid var(--sand);
}
.top-product-item:last-child{border-bottom:none;}
.top-product-img{width:38px;height:38px;border-radius:8px;object-fit:cover;border:1px solid var(--sand);flex-shrink:0;}
.top-product-img-empty{width:38px;height:38px;border-radius:8px;background:var(--sand-lt);border:1px solid var(--sand);flex-shrink:0;}
.top-product-name{font-size:13px;font-weight:500;color:var(--bark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.top-product-sold{margin-left:auto;font-size:13px;font-weight:500;color:var(--olive-dk);flex-shrink:0;white-space:nowrap;}
.bar-track{width:64px;height:4px;background:var(--sand);border-radius:100px;flex-shrink:0;}
.bar-fill{height:100%;background:var(--olive);border-radius:100px;}

.table-card{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);overflow:hidden;
}
.table-header{
    padding:14px 18px;display:flex;
    justify-content:space-between;align-items:center;
    flex-wrap:wrap;gap:10px;border-bottom:.5px solid var(--sand);
}
.table-header-label{
    font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:var(--olive);
}
.search-form{display:flex;gap:6px;align-items:center;}
.search-input{
    padding:6px 11px;border:1px solid var(--sand);border-radius:8px;
    font-size:12px;color:var(--bark);outline:none;
    font-family:'DM Sans',sans-serif;width:180px;background:var(--beige);
    transition:border-color .2s;
}
.search-input:focus{border-color:var(--olive);}
.btn-search{
    background:var(--olive-dk);color:var(--olive-lt);border:none;
    border-radius:8px;padding:6px 12px;font-size:11px;cursor:pointer;
    font-family:'DM Sans',sans-serif;transition:background .2s;
}
.btn-search:hover{background:var(--olive);}
.btn-clear{
    font-size:11px;color:var(--stone);text-decoration:none;
    border:1px solid var(--sand);border-radius:6px;padding:6px 10px;
    transition:all .2s;
}
.btn-clear:hover{color:var(--bark);}

.tbl{width:100%;border-collapse:collapse;min-width:500px;}
.tbl th{
    font-size:10px;letter-spacing:.12em;text-transform:uppercase;
    color:var(--stone);font-weight:400;padding:12px 16px;
    text-align:left;border-bottom:.5px solid var(--sand);
    background:var(--sand-lt);
}
.tbl td{
    padding:11px 16px;font-size:13px;color:var(--bark);
    border-bottom:.5px solid var(--sand);vertical-align:middle;
}
.tbl tr:last-child td{border-bottom:none;}
.tbl tbody tr:hover td{background:var(--sand-lt);}
.product-thumb{width:36px;height:36px;border-radius:6px;object-fit:cover;border:1px solid var(--sand);}

.dot{display:inline-block;width:6px;height:6px;border-radius:50%;margin-right:5px;}
.dot-green{background:#5a8a5a;}
.dot-gray{background:var(--sand);}
.dot-red{background:var(--terra);}

.stock-ok{color:var(--stone);font-size:12px;}
.stock-low{color:#c47820;font-size:12px;font-weight:500;}
.stock-out{color:var(--terra);font-size:12px;font-weight:500;}

.empty-row{text-align:center;padding:40px;color:var(--stone);font-size:13px;}

.pagination-wrap{
    padding:12px 16px;border-top:.5px solid var(--sand);
    display:flex;justify-content:space-between;align-items:center;
    flex-wrap:wrap;gap:8px;
}
.pagination-info{font-size:12px;color:var(--stone);}
.pagination-btns{display:flex;gap:4px;}
.page-btn{
    padding:5px 9px;border-radius:6px;font-size:12px;
    text-decoration:none;border:1px solid var(--sand);
    color:var(--stone);background:var(--cream);transition:all .15s;
}
.page-btn:hover{background:var(--sand-lt);color:var(--bark);}
.page-btn.active{background:var(--olive-dk);color:var(--olive-lt);border-color:var(--olive-dk);}
.page-btn.disabled{opacity:.4;pointer-events:none;}

@media(max-width:600px){
    .store-hero{padding:20px;}
    .hero-name{font-size:22px;}
    .filter-form{gap:6px;}
    .btn-export{margin-left:0;}
}
</style>

<a href="{{ route('admin.merchants.index') }}" class="back-link">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Analytics Merchant
</a>

@php $s = $store->getStatusLabel(); @endphp

<div class="store-hero">
    <div class="hero-left">
        <div class="hero-avatar-row">
            @if($store->logo)
                <img src="{{ asset($store->logo) }}" class="hero-avatar" alt="{{ $store->name }}">
            @else
                <div class="hero-avatar-init">{{ strtoupper(substr($store->name,0,1)) }}</div>
            @endif
            <div>
                <h1 class="hero-name">{{ $store->name }}</h1>
                <p class="hero-meta">{{ $store->user->name }} · {{ $store->user->email }}</p>
                @if($store->phone)<p class="hero-meta">{{ $store->phone }}</p>@endif
            </div>
        </div>
        @if($store->description)
            <p class="hero-desc">{{ $store->description }}</p>
        @endif
    </div>
    <div class="hero-right">
        <span class="status-badge" style="background:{{ $s['color'] }}22;color:{{ $s['color'] }};border:.5px solid {{ $s['color'] }}44;">
            {{ $s['label'] }}
        </span>
        <p style="font-size:11px;color:rgba(200,212,184,.35);">Daftar {{ $store->created_at->format('d M Y') }}</p>
        <div class="hero-actions">
            <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="btn-hero btn-hero-outline">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Lihat Toko ↗
            </a>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <p class="stat-label">Total Produk</p>
        <p class="stat-value">{{ $totalProducts }}</p>
        <p class="stat-sub">semua produk</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Produk Aktif</p>
        <p class="stat-value" style="color:var(--olive-dk);">{{ $activeProducts }}</p>
        <p class="stat-sub">tampil di katalog</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Total Terjual</p>
        <p class="stat-value">{{ $totalOrders }}</p>
        <p class="stat-sub">item (order selesai)</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Pendapatan</p>
        <p class="stat-value" style="font-size:20px;margin-top:4px;">
            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </p>
        <p class="stat-sub">dari pesanan selesai</p>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" class="filter-form">
        <select name="month" class="filter-select">
            <option value="">Semua Bulan</option>
            @for($m=1;$m<=12;$m++)
            <option value="{{ $m }}" {{ request('month')==$m?'selected':'' }}>
                {{ date('F', mktime(0,0,0,$m,1)) }}
            </option>
            @endfor
        </select>
        <select name="year" class="filter-select">
            <option value="">Semua Tahun</option>
            @for($y=now()->year;$y>=2020;$y--)
            <option value="{{ $y }}" {{ request('year')==$y?'selected':'' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="btn-filter">Terapkan</button>
        @if(request('month') || request('year'))
            <a href="{{ route('admin.merchants.show', $store->id) }}" class="btn-reset">Reset</a>
        @endif
        <a href="{{ route('admin.merchants.export', [$store->id]+request()->all()) }}" class="btn-export">
            Export CSV
        </a>
    </form>
</div>

<div class="charts-row">
    <div class="chart-card">
        <p class="chart-title">
            Pendapatan ({{ $chartType==='daily' ? 'Harian' : 'Bulanan' }})
            @if($growth > 0)
                <span class="growth-tag" style="color:#5a8a5a;">▲ +{{ number_format($growth,1) }}%</span>
            @elseif($growth < 0)
                <span class="growth-tag" style="color:var(--terra);">▼ {{ number_format($growth,1) }}%</span>
            @else
                <span class="growth-tag" style="color:var(--stone);">— 0%</span>
            @endif
        </p>
        @php $hasData = collect($currentData)->sum() > 0; @endphp
        @if(!$hasData)
        <div class="no-data-placeholder">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            <p style="font-size:13px;">Belum ada data penjualan</p>
        </div>
        @else
        <div style="height:190px;"><canvas id="revenueChart"></canvas></div>
        <div style="display:flex;gap:14px;margin-top:10px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--stone);">
                <div style="width:16px;height:2px;background:#6b7c5c;border-radius:2px;"></div>
                {{ $chartType==='daily' ? date('F Y', mktime(0,0,0,request('month'),1,request('year'))) : 'Tahun '.now()->year }}
            </div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--stone);">
                <div style="width:16px;height:2px;background:var(--sand);border-radius:2px;border-top:1px dashed var(--stone);"></div>
                Periode sebelumnya
            </div>
        </div>
        @endif
    </div>

    <div class="chart-card" style="display:flex;flex-direction:column;">
        <p class="chart-title">Produk Aktif vs Nonaktif</p>
        <div style="flex:1;display:flex;align-items:center;justify-content:center;min-height:160px;">
            <canvas id="productChart"></canvas>
        </div>
        <div style="display:flex;gap:14px;justify-content:center;margin-top:8px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--stone);">
                <div style="width:8px;height:8px;border-radius:50%;background:var(--olive-dk);"></div>
                Aktif ({{ $productStats['active'] }})
            </div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--stone);">
                <div style="width:8px;height:8px;border-radius:50%;background:var(--sand);"></div>
                Nonaktif ({{ $productStats['inactive'] }})
            </div>
        </div>
    </div>
</div>

@if($topProducts->count() > 0)
<div class="top-products-card">
    <p class="chart-title">Produk Terlaris — Top {{ $topProducts->count() }}</p>
    @php $maxSold = $topProducts->max('sold') ?: 1; @endphp
    @foreach($topProducts as $i => $item)
    <div class="top-product-item">
        <span style="font-family:'Cormorant Garamond',serif;font-size:16px;color:var(--sand);width:20px;text-align:center;flex-shrink:0;">
            {{ $i+1 }}
        </span>
        @if($item->product?->image)
            <img src="{{ asset($item->product->image) }}" class="top-product-img" alt="">
        @else
            <div class="top-product-img-empty"></div>
        @endif
        <div style="flex:1;min-width:0;">
            <p class="top-product-name">{{ $item->product->name ?? '(Produk dihapus)' }}</p>
            @if($item->product?->category)
                <p style="font-size:11px;color:var(--stone);">{{ $item->product->category->name }}</p>
            @endif
        </div>
        <div class="bar-track">
            <div class="bar-fill" style="width:{{ round($item->sold/$maxSold*100) }}%;"></div>
        </div>
        <span class="top-product-sold">{{ $item->sold }}×</span>
    </div>
    @endforeach
</div>
@endif

<div class="table-card">
    <div class="table-header">
        <p class="table-header-label">
            Daftar Produk
            <span style="text-transform:none;letter-spacing:0;font-weight:400;color:var(--stone);margin-left:6px;">
                {{ $products->total() }} produk
            </span>
        </p>
        <form method="GET" class="search-form">
            @if(request('month'))<input type="hidden" name="month" value="{{ request('month') }}">@endif
            @if(request('year'))<input type="hidden" name="year" value="{{ request('year') }}">@endif
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama produk..." class="search-input">
            <button type="submit" class="btn-search">Cari</button>
            @if($search)
            <a href="{{ route('admin.merchants.show', [$store->id]+request()->except('search','page')) }}" class="btn-clear">✕</a>
            @endif
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:48px;"></th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="product-thumb" alt="">
                        @else
                            <div style="width:36px;height:36px;border-radius:6px;background:var(--sand-lt);border:1px solid var(--sand);"></div>
                        @endif
                    </td>
                    <td>
                        <p style="font-weight:500;font-size:13px;color:var(--bark);">{{ $product->name }}</p>
                        @if($product->category)
                            <p style="font-size:11px;color:var(--stone);margin-top:1px;">{{ $product->category->name }}</p>
                        @endif
                    </td>
                    <td>
                        <p style="font-size:13px;color:var(--olive-dk);font-weight:500;">{{ $product->getFinalPriceFormatted() }}</p>
                        @if($product->hasDiscount())
                            <p style="font-size:11px;color:var(--stone);text-decoration:line-through;">{{ $product->getPriceFormatted() }}</p>
                        @endif
                    </td>
                    <td>
                        @if($product->stock === 0)
                            <span class="stock-out">Habis</span>
                        @elseif($product->isLowStock())
                            <span class="stock-low">{{ $product->stock }} (rendah)</span>
                        @else
                            <span class="stock-ok">{{ $product->stock > 0 ? $product->stock : '∞' }}</span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="dot dot-green"></span><span style="font-size:12px;color:var(--olive-dk);">Aktif</span>
                        @else
                            <span class="dot dot-gray"></span><span style="font-size:12px;color:var(--stone);">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-row">
                    {{ $search ? 'Tidak ada produk dengan nama "'.e($search).'"' : 'Toko ini belum punya produk.' }}
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="pagination-wrap">
        <p class="pagination-info">{{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }}</p>
        <div class="pagination-btns">
            @if($products->onFirstPage())
                <span class="page-btn disabled">‹</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="page-btn">‹</a>
            @endif
            @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $pg => $url)
                <a href="{{ $url }}" class="page-btn {{ $pg===$products->currentPage()?'active':'' }}">{{ $pg }}</a>
            @endforeach
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="page-btn">›</a>
            @else
                <span class="page-btn disabled">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font = {family:"'DM Sans',sans-serif", size:11};
Chart.defaults.color = '#8c7b6b';

new Chart(document.getElementById('productChart'), {
    type:'doughnut',
    data:{
        labels:['Aktif','Nonaktif'],
        datasets:[{
            data:[{{ $productStats['active'] }},{{ $productStats['inactive'] }}],
            backgroundColor:['#4a5940','#d4c4a8'],
            borderWidth:0, hoverOffset:6,
        }]
    },
    options:{
        responsive:true, maintainAspectRatio:false, cutout:'70%',
        plugins:{
            legend:{display:false},
            tooltip:{callbacks:{label:ctx=>' '+ctx.label+': '+ctx.raw}}
        }
    }
});

@if(collect($currentData)->sum() > 0)
new Chart(document.getElementById('revenueChart'), {
    type:'line',
    data:{
        labels: @json($labels->map(fn($l) => $chartType==='daily' ? 'Tgl '.$l : \Carbon\Carbon::create()->month($l)->format('M'))),
        datasets:[
            {
                label:'Sekarang',
                data: @json($currentData),
                borderColor:'#6b7c5c',
                backgroundColor:'rgba(107,124,92,0.1)',
                borderWidth:2, fill:true, tension:0.4,
                pointBackgroundColor:'#6b7c5c',
                pointRadius:3, pointHoverRadius:5,
            },
            {
                label:'Sebelumnya',
                data: @json($previousData),
                borderColor:'#d4c4a8',
                backgroundColor:'transparent',
                borderWidth:1.5, borderDash:[5,5], tension:0.4,
                pointRadius:0,
            }
        ]
    },
    options:{
        responsive:true, maintainAspectRatio:false,
        plugins:{
            legend:{display:false},
            tooltip:{callbacks:{
                label: ctx => ' '+ctx.dataset.label+': Rp '+Number(ctx.raw).toLocaleString('id-ID')
            }}
        },
        scales:{
            y:{
                beginAtZero:true,
                grid:{color:'rgba(212,196,168,0.4)'},
                border:{display:false},
                ticks:{
                    color:'#8c7b6b',
                    callback: v => 'Rp '+Number(v/1000).toLocaleString('id-ID')+'k'
                }
            },
            x:{
                grid:{display:false},
                border:{display:false},
                ticks:{color:'#8c7b6b', maxTicksLimit:10}
            }
        }
    }
});
@endif
</script>

@endsection
