@extends('admin.layouts.sidebar')
@section('page-title', 'Analytics Merchant')
@section('content')

<style>
*{box-sizing:border-box}

:root{
    --beige:#f5f0e8; --cream:#ede6d6; --sand:#d4c4a8; --sand-lt:#ece3d4;
    --olive:#6b7c5c; --olive-dk:#4a5940; --olive-lt:#c8d4b8;
    --stone:#8c7b6b; --bark:#3b2e22; --terra:#c4694f;
}

.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.stat-grid{grid-template-columns:1fr 1fr;gap:10px;}}

.stat-card{
    background:var(--cream); border-radius:12px;
    border:1px solid var(--sand); padding:18px 20px;
    transition:box-shadow .2s;
}
.stat-card:hover{box-shadow:0 4px 16px rgba(59,46,34,.08);}
.stat-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--stone);margin-bottom:8px;font-family:'DM Sans',sans-serif;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:400;color:var(--bark);line-height:1;margin-bottom:4px;}
.stat-sub{font-size:11px;color:var(--stone);}
.stat-accent{color:var(--terra);}

.charts-grid{display:grid;grid-template-columns:3fr 2fr;gap:16px;margin-bottom:24px;}
@media(max-width:768px){.charts-grid{grid-template-columns:1fr;}}

.chart-card{
    background:var(--cream); border-radius:12px;
    border:1px solid var(--sand); padding:22px;
}
.chart-title{
    font-size:9px;letter-spacing:.18em;text-transform:uppercase;
    color:var(--olive);margin-bottom:18px;font-family:'DM Sans',sans-serif;
}

.section-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.section-title{
    font-size:9px;letter-spacing:.18em;text-transform:uppercase;
    color:var(--olive);font-family:'DM Sans',sans-serif;
}
.section-divider{width:36px;height:1.5px;background:var(--sand);margin-bottom:16px;}

.top-list{
    background:var(--cream); border-radius:12px;
    border:1px solid var(--sand); overflow:hidden;
    margin-bottom:16px;
}
.top-item{
    display:flex;align-items:center;padding:14px 20px;
    border-bottom:.5px solid var(--sand);gap:14px;
    transition:background .15s;
}
.top-item:last-child{border-bottom:none;}
.top-item:hover{background:var(--sand-lt);}
.top-rank{
    font-family:'Cormorant Garamond',serif;font-size:22px;
    color:var(--sand);width:28px;flex-shrink:0;text-align:center;
    font-weight:300;
}
.top-rank-gold{color:#b8955a;}
.top-rank-silver{color:#9aaca0;}
.top-rank-bronze{color:#a07858;}
.top-link{color:inherit;text-decoration:none;display:flex;align-items:center;gap:14px;flex:1;min-width:0;}
.top-store-name{font-size:13px;font-weight:500;color:var(--bark);margin-bottom:2px;transition:color .15s;}
.top-link:hover .top-store-name{color:var(--olive-dk);}
.top-store-owner{font-size:11px;color:var(--stone);}
.top-count{margin-left:auto;text-align:right;flex-shrink:0;}
.top-count-val{font-size:14px;font-weight:500;color:var(--olive-dk);}
.top-count-label{font-size:10px;color:var(--stone);}

.store-avatar-sm{
    width:36px;height:36px;border-radius:50%;object-fit:cover;
    border:1.5px solid var(--sand);flex-shrink:0;
}
.store-avatar-init-sm{
    width:36px;height:36px;border-radius:50%;background:var(--sand-lt);
    border:1.5px solid var(--sand);display:flex;align-items:center;justify-content:center;
    font-family:'Cormorant Garamond',serif;font-size:16px;color:var(--olive-dk);flex-shrink:0;
}

.top-bar-wrap{width:80px;height:4px;background:var(--sand);border-radius:100px;flex-shrink:0;}
.top-bar-fill{height:100%;background:var(--olive);border-radius:100px;transition:width .4s;}

.pagination-wrap{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 20px;border-top:.5px solid var(--sand);
    flex-wrap:wrap;gap:8px;
}
.pagination-info{font-size:12px;color:var(--stone);}
.pagination-btns{display:flex;gap:4px;}
.page-btn{
    padding:5px 10px;border-radius:6px;font-size:12px;
    text-decoration:none;border:1px solid var(--sand);
    color:var(--stone);background:var(--cream);transition:all .15s;
    font-family:'DM Sans',sans-serif;cursor:pointer;
}
.page-btn:hover{background:var(--sand-lt);color:var(--bark);}
.page-btn.active{background:var(--olive-dk);color:var(--olive-lt);border-color:var(--olive-dk);}
.page-btn.disabled{opacity:.4;pointer-events:none;}

.view-all-link{
    display:block;text-align:center;padding:12px;
    font-size:10px;letter-spacing:.12em;text-transform:uppercase;
    color:var(--stone);text-decoration:none;
    border-top:.5px solid var(--sand);transition:all .2s;
    font-family:'DM Sans',sans-serif;background:none;
}
.view-all-link:hover{color:var(--olive-dk);background:var(--sand-lt);}

.growth-legend{
    display:flex;gap:16px;margin-top:12px;flex-wrap:wrap;
}
.legend-item{display:flex;align-items:center;gap:6px;font-size:11px;color:var(--stone);}
.legend-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}

.empty-cell{text-align:center;padding:48px 20px;color:var(--stone);font-size:13px;}
</style>

<div class="stat-grid">
    <div class="stat-card">
        <p class="stat-label">Total Merchant</p>
        <p class="stat-value">{{ $stats['total_stores'] }}</p>
        <p class="stat-sub">toko terdaftar</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Aktif</p>
        <p class="stat-value" style="color:var(--olive-dk);">{{ $stats['active_stores'] }}</p>
        @if($stats['pending_stores'] > 0)
            <p class="stat-sub"><span class="stat-accent">{{ $stats['pending_stores'] }}</span> menunggu approval</p>
        @else
            <p class="stat-sub">semua diproses</p>
        @endif
    </div>
    <div class="stat-card">
        <p class="stat-label">Produk Merchant</p>
        <p class="stat-value">{{ $stats['total_merchant_products'] }}</p>
        <p class="stat-sub">dari semua toko</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Produk Aktif</p>
        <p class="stat-value" style="color:var(--olive-dk);">{{ $stats['active_products'] }}</p>
        <p class="stat-sub">
            @if($stats['inactive_products'] > 0)
                <span class="stat-accent">{{ $stats['inactive_products'] }}</span> nonaktif
            @else
                semua tampil di katalog
            @endif
        </p>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <p class="chart-title">Pertumbuhan Toko — 6 Bulan Terakhir</p>
        <div style="height:190px;"><canvas id="growthChart"></canvas></div>
        <div class="growth-legend">
            <div class="legend-item">
                <div class="legend-dot" style="background:var(--olive);"></div>
                Toko baru per bulan
            </div>
        </div>
    </div>
    <div class="chart-card" style="display:flex;flex-direction:column;">
        <p class="chart-title">Distribusi Produk</p>
        <div style="flex:1;display:flex;align-items:center;justify-content:center;min-height:160px;">
            <canvas id="productStatusChart"></canvas>
        </div>
        <div class="growth-legend" style="justify-content:center;margin-top:8px;">
            <div class="legend-item"><div class="legend-dot" style="background:var(--olive-dk);"></div>Aktif</div>
            <div class="legend-item"><div class="legend-dot" style="background:var(--sand);"></div>Nonaktif</div>
        </div>
    </div>
</div>

<div class="section-hd">
    <p class="section-title">Merchant Teratas</p>
    <a href="{{ route('admin.stores.index') }}"
       style="font-size:11px;color:var(--stone);text-decoration:none;letter-spacing:.06em;transition:color .2s;"
       onmouseover="this.style.color='var(--olive-dk)'" onmouseout="this.style.color='var(--stone)'">
        Lihat Semua →
    </a>
</div>
<div class="section-divider"></div>

@php
    $perPage   = 10;
    $page      = (int) request('page', 1);
    $allTop    = \App\Models\Store::withCount('products')
                    ->where('status', 'active')
                    ->orderByDesc('products_count')
                    ->get();
    $total     = $allTop->count();
    $totalPages= (int) ceil($total / $perPage);
    $pagedTop  = $allTop->forPage($page, $perPage);
    $maxCount  = $allTop->first()?->products_count ?: 1;
@endphp

<div class="top-list">
    @forelse($pagedTop as $i => $store)
    @php $rank = ($page - 1) * $perPage + $i + 1; @endphp
    <div class="top-item">
        <span class="top-rank {{ $rank===1?'top-rank-gold':($rank===2?'top-rank-silver':($rank===3?'top-rank-bronze':'')) }}">
            {{ $rank }}
        </span>
        @if($store->logo)
            <img src="{{ asset($store->logo) }}" class="store-avatar-sm" alt="{{ $store->name }}">
        @else
            <div class="store-avatar-init-sm">{{ strtoupper(substr($store->name,0,1)) }}</div>
        @endif
        <a href="{{ route('admin.merchants.show', $store) }}" class="top-link">
            <div style="min-width:0;flex:1;">
                <p class="top-store-name">{{ $store->name }}</p>
                <p class="top-store-owner">{{ $store->user->name }}</p>
            </div>
            <div class="top-bar-wrap">
                <div class="top-bar-fill" style="width:{{ round($store->products_count / $maxCount * 100) }}%;"></div>
            </div>
            <div class="top-count" style="min-width:52px;">
                <p class="top-count-val">{{ $store->products_count }}</p>
                <p class="top-count-label">produk</p>
            </div>
        </a>
    </div>
    @empty
    <div class="empty-cell">Belum ada merchant aktif.</div>
    @endforelse

    @if($totalPages > 1)
    <div class="pagination-wrap">
        <p class="pagination-info">{{ ($page-1)*$perPage+1 }}–{{ min($page*$perPage,$total) }} dari {{ $total }} merchant</p>
        <div class="pagination-btns">
            <a href="?page={{ max(1,$page-1) }}" class="page-btn {{ $page<=1?'disabled':'' }}">‹</a>
            @for($p=1;$p<=$totalPages;$p++)
                @if($p===1 || $p===$totalPages || abs($p-$page)<=1)
                    <a href="?page={{ $p }}" class="page-btn {{ $p===$page?'active':'' }}">{{ $p }}</a>
                @elseif(abs($p-$page)===2)
                    <span class="page-btn disabled">…</span>
                @endif
            @endfor
            <a href="?page={{ min($totalPages,$page+1) }}" class="page-btn {{ $page>=$totalPages?'disabled':'' }}">›</a>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font = {family:"'DM Sans',sans-serif", size:11};
Chart.defaults.color = '#8c7b6b';

const growthLabels = @json($storeGrowth->keys());
const growthData   = @json($storeGrowth->values());

new Chart(document.getElementById('growthChart'), {
    type: 'bar',
    data: {
        labels: growthLabels.length ? growthLabels : ['Belum ada data'],
        datasets:[{
            label: 'Toko baru',
            data: growthData.length ? growthData : [0],
            backgroundColor: 'rgba(107,124,92,0.18)',
            borderColor: '#6b7c5c',
            borderWidth: 1.5,
            borderRadius: 6,
            hoverBackgroundColor: 'rgba(107,124,92,0.32)',
        }]
    },
    options:{
        responsive:true, maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{
            y:{
                beginAtZero:true,
                ticks:{stepSize:1, color:'#8c7b6b'},
                grid:{color:'rgba(212,196,168,0.4)'},
                border:{display:false},
            },
            x:{
                grid:{display:false},
                ticks:{color:'#8c7b6b'},
                border:{display:false},
            }
        }
    }
});

new Chart(document.getElementById('productStatusChart'), {
    type:'doughnut',
    data:{
        labels:['Aktif','Nonaktif'],
        datasets:[{
            data:[{{ $stats['active_products'] }}, {{ $stats['inactive_products'] }}],
            backgroundColor:['#4a5940','#d4c4a8'],
            borderWidth:0,
            hoverOffset:6,
        }]
    },
    options:{
        responsive:true, maintainAspectRatio:false, cutout:'70%',
        plugins:{
            legend:{display:false},
            tooltip:{callbacks:{label: ctx => ' '+ctx.label+': '+ctx.raw+' produk'}}
        }
    }
});
</script>

@endsection
