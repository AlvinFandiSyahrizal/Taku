@extends('admin.layouts.sidebar')
@section('page-title', 'Dashboard')
@section('content')

<style>
*{box-sizing:border-box}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
.stat-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:20px 22px;position:relative;overflow:hidden;}
.stat-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:8px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:400;color:#0b2a4a;line-height:1;margin-bottom:3px;}
.stat-sub{font-size:11px;color:rgba(11,42,74,.35);}
.stat-accent{color:#c9a96e;}
.stat-icon{position:absolute;right:16px;top:16px;opacity:.06;}

.merchant-banner{background:#0b2a4a;border-radius:14px;padding:20px 26px;display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.merchant-banner-left{display:flex;gap:28px;}
.merchant-banner-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(240,235,224,.4);margin-bottom:5px;}
.merchant-banner-value{font-family:'Cormorant Garamond',serif;font-size:30px;color:#f0ebe0;line-height:1;}
.merchant-banner-sub{font-size:11px;color:rgba(201,169,110,.7);margin-top:2px;}
.merchant-banner-btn{background:rgba(201,169,110,.15);border:.5px solid rgba(201,169,110,.3);color:#c9a96e;font-size:11px;letter-spacing:.12em;text-transform:uppercase;border-radius:8px;padding:9px 18px;text-decoration:none;font-family:'DM Sans',sans-serif;transition:background .2s;display:flex;align-items:center;gap:7px;}
.merchant-banner-btn:hover{background:rgba(201,169,110,.25);}

.filter-bar{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:12px 16px;margin-bottom:20px;}
.filter-form{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;}
.filter-group{display:flex;gap:8px;}
.filter-input{border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:7px 11px;font-size:12px;color:#0b2a4a;background:#f9f7f2;outline:none;transition:.2s;font-family:'DM Sans',sans-serif;}
.filter-input:focus{border-color:#c9a96e;background:white;}
.filter-actions{display:flex;gap:8px;}
.btn-filter{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:7px 14px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;}
.btn-filter:hover{background:#0d3459;}
.btn-export{background:#c9a96e;color:#0b2a4a;border-radius:8px;padding:7px 14px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;font-family:'DM Sans',sans-serif;transition:.2s;}
.btn-export:hover{background:#b8965d;}
.btn-reset{border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:7px 14px;font-size:11px;text-decoration:none;color:rgba(11,42,74,.5);font-family:'DM Sans',sans-serif;}

.charts-row{display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:20px;}
.chart-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:20px 22px;}
.chart-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;}
.chart-title-sub{font-size:11px;color:rgba(11,42,74,.35);text-transform:none;letter-spacing:0;font-weight:400;}

.traffic-row{display:grid;grid-template-columns:3fr 1fr;gap:16px;margin-bottom:20px;}

.online-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:#27ae60;background:rgba(39,174,96,.08);border:.5px solid rgba(39,174,96,.2);padding:3px 10px;border-radius:100px;}
.online-dot{width:6px;height:6px;border-radius:50%;background:#27ae60;animation:pulse 2s infinite;}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

.top-pages-list{}
.top-page-item{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:.5px solid rgba(11,42,74,.04);}
.top-page-item:last-child{border-bottom:none;}
.top-page-path{font-size:12px;color:#0b2a4a;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.top-page-views{font-size:12px;font-weight:500;color:#0b2a4a;flex-shrink:0;}
.top-page-bar-wrap{width:60px;height:4px;background:rgba(11,42,74,.06);border-radius:100px;flex-shrink:0;}
.top-page-bar{height:100%;background:#c9a96e;border-radius:100px;}

.bottom-row{display:grid;grid-template-columns:3fr 2fr;gap:16px;}

.recent-table{width:100%;border-collapse:collapse;}
.recent-table th{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);font-weight:400;padding:10px 16px;text-align:left;border-bottom:.5px solid rgba(11,42,74,.06);}
.recent-table td{padding:12px 16px;font-size:13px;color:#0b2a4a;border-bottom:.5px solid rgba(11,42,74,.04);}
.recent-table tr:last-child td{border-bottom:none;}
.status-badge{display:inline-block;padding:3px 9px;border-radius:100px;font-size:10px;letter-spacing:.07em;text-transform:uppercase;font-weight:500;}
.empty-state{text-align:center;padding:32px;color:rgba(11,42,74,.3);font-size:13px;}

.section-title{font-size:11px;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:14px;}
.view-all-link{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.35);text-decoration:none;border:.5px solid rgba(11,42,74,.12);border-radius:5px;padding:4px 10px;transition:all .2s;}
.view-all-link:hover{color:#0b2a4a;border-color:rgba(11,42,74,.25);}

@media(max-width:640px){
    .stat-grid { grid-template-columns: 1fr 1fr !important; gap:10px; }
    .charts-row { grid-template-columns: 1fr !important; }
    .traffic-row { grid-template-columns: 1fr !important; }
    .bottom-row  { grid-template-columns: 1fr !important; }
    .merchant-banner { flex-direction: column; gap:16px; }
    .merchant-banner-left { flex-direction: column; gap:12px; }
    .filter-form { flex-direction: column; align-items: flex-start; }
    .filter-group { flex-wrap: wrap; }
    .filter-actions { width:100%; justify-content: flex-end; }
    .stat-value { font-size:26px !important; }
}
@media(max-width:400px){
    .stat-grid { grid-template-columns: 1fr !important; }
}

</style>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
        </div>
        <p class="stat-label">Produk Official</p>
        <p class="stat-value">{{ $stats['total_products'] }}</p>
        <p class="stat-sub">produk milik admin</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
        </div>
        <p class="stat-label">Pesanan Admin</p>
        <p class="stat-value">{{ $stats['total_orders'] }}</p>
        @if($stats['pending_orders'] > 0)
        <p class="stat-sub"><span class="stat-accent">{{ $stats['pending_orders'] }}</span> menunggu konfirmasi</p>
        @else
        <p class="stat-sub">semua pesanan diproses</p>
        @endif
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <p class="stat-label">Total User</p>
        <p class="stat-value">{{ $stats['total_users'] }}</p>
        <p class="stat-sub">pengguna terdaftar</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <p class="stat-label">Pendapatan Admin</p>
        <p class="stat-value" style="font-size:22px;">Rp {{ number_format($stats['total_revenue'],0,',','.') }}</p>
        <p class="stat-sub">dari pesanan selesai</p>
    </div>
</div>

<div class="merchant-banner">
    <div class="merchant-banner-left">
        <div>
            <p class="merchant-banner-label">Merchant Aktif</p>
            <p class="merchant-banner-value">{{ $stats['merchant_count'] }}</p>
            @if($stats['pending_stores'] > 0)
            <p class="merchant-banner-sub">+{{ $stats['pending_stores'] }} menunggu approval</p>
            @else
            <p class="merchant-banner-sub">semua diproses</p>
            @endif
        </div>
        <div>
            <p class="merchant-banner-label">Produk Merchant</p>
            <p class="merchant-banner-value">{{ $stats['merchant_products'] }}</p>
            <p class="merchant-banner-sub">dari semua toko</p>
        </div>
    </div>
    <a href="{{ route('admin.merchants.index') }}" class="merchant-banner-btn">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
        Lihat Analytics
    </a>
</div>

<div class="filter-bar">
    <form method="GET" class="filter-form">
        <div class="filter-group">
            <select name="month" class="filter-input">
                <option value="">Semua Bulan</option>
                @for($m=1;$m<=12;$m++)
                <option value="{{$m}}" {{request('month')==$m?'selected':''}}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>
                @endfor
            </select>
            <select name="year" class="filter-input">
                <option value="">Semua Tahun</option>
                @for($y=now()->year;$y>=2020;$y--)
                <option value="{{$y}}" {{request('year')==$y?'selected':''}}>{{$y}}</option>
                @endfor
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-filter">Terapkan</button>
            @if(request('month')||request('year'))
            <a href="{{route('admin.dashboard')}}" class="btn-reset">Reset</a>
            @endif
            <a href="{{route('admin.dashboard.export',request()->all())}}" class="btn-export">Export CSV</a>
        </div>
    </form>
</div>

<div class="charts-row">
    <div class="chart-card">
        <div class="chart-title">
            Pendapatan Per Bulan
            <span class="chart-title-sub">
                @if($stats['store_growth_percent'] > 0)
                    <span style="color:#27ae60;">▲ {{ $stats['store_growth_percent'] }}%</span>
                @elseif($stats['store_growth_percent'] < 0)
                    <span style="color:#c0392b;">▼ {{ abs($stats['store_growth_percent']) }}%</span>
                @else — @endif
                dari bulan lalu
            </span>
        </div>
        <div style="height:180px;"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="chart-title">Distribusi Produk</div>
        <div style="height:180px;display:flex;align-items:center;justify-content:center;">
            <canvas id="productDistChart"></canvas>
        </div>
    </div>
</div>

<div class="traffic-row">
    <div class="chart-card">
        <div class="chart-title">
            Traffic 30 Hari Terakhir
            <div style="display:flex;gap:12px;align-items:center;">
                <span style="font-size:11px;color:rgba(11,42,74,.4);">Hari ini: <strong style="color:#0b2a4a;">{{ number_format($stats['today_views']) }}</strong> views · <strong style="color:#0b2a4a;">{{ number_format($stats['today_unique']) }}</strong> unik</span>
                <span class="online-badge"><span class="online-dot"></span>{{ $stats['online_count'] }} online</span>
            </div>
        </div>
        <div style="height:140px;"><canvas id="trafficChart"></canvas></div>
    </div>

    <div class="chart-card">
        <div class="chart-title">Top Halaman (7 Hari)</div>
        @php $maxViews = $stats['top_pages']->max('views') ?: 1; @endphp
        <div class="top-pages-list">
            @forelse($stats['top_pages'] as $page)
            <div class="top-page-item">
                <span class="top-page-path" title="{{ $page->path }}">{{ $page->path }}</span>
                <div class="top-page-bar-wrap">
                    <div class="top-page-bar" style="width:{{ round($page->views/$maxViews*100) }}%;"></div>
                </div>
                <span class="top-page-views">{{ number_format($page->views) }}</span>
            </div>
            @empty
            <p style="font-size:12px;color:rgba(11,42,74,.35);padding:20px 0;text-align:center;">Belum ada data traffic.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="bottom-row">
    <div class="chart-card">
        <div class="chart-title" style="justify-content:space-between;">
            Pesanan Terbaru
            <a href="{{ route('admin.orders.index') }}" class="view-all-link">Lihat Semua</a>
        </div>
        <table class="recent-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_orders'] as $order)
                @php $st = $order->getStatusLabel(); @endphp
                <tr>
                    <td style="font-weight:500;">{{ $order->order_code }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->getTotalFormatted() }}</td>
                    <td>
                        <span class="status-badge" style="background:{{ $st['color'] }}18;color:{{ $st['color'] }};">
                            {{ $st['label'] }}
                        </span>
                    </td>
                    <td style="color:rgba(11,42,74,.45);font-size:11px;">{{ $order->created_at->format('d M') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-state">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="chart-card">
        <div class="chart-title">Pertumbuhan Toko</div>
        <div style="height:200px;"><canvas id="storeGrowthChart"></canvas></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const navy = '#0b2a4a', gold = '#c9a96e';
Chart.defaults.font = { family:"'DM Sans',sans-serif", size:11 };
Chart.defaults.color = 'rgba(11,42,74,0.45)';
const grid = { color:'rgba(11,42,74,0.05)' };

// Revenue chart — bar
const revLabels = @json($stats['revenue_chart']->keys());
const revData   = @json($stats['revenue_chart']->values());
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revLabels.length ? revLabels : ['Belum ada data'],
        datasets: [{
            label: 'Pendapatan',
            data: revData.length ? revData : [0],
            backgroundColor: 'rgba(201,169,110,0.15)',
            borderColor: gold,
            borderWidth: 1.5,
            borderRadius: 6,
            hoverBackgroundColor: 'rgba(201,169,110,0.3)',
            maxBarThickness: 40,
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins: {
            legend: { display:false },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + Number(ctx.raw).toLocaleString('id-ID')
                }
            }
        },
        scales: {
            y: { beginAtZero:true, grid, ticks: { callback: v => 'Rp '+Number(v).toLocaleString('id-ID') } },
            x: { grid: { display:false } }
        }
    }
});

new Chart(document.getElementById('productDistChart'), {
    type: 'doughnut',
    data: {
        labels: ['Official', 'Merchant'],
        datasets: [{
            data: [{{ $stats['total_products'] }}, {{ $stats['merchant_products'] }}],
            backgroundColor: [navy, gold],
            borderWidth: 0, hoverOffset: 4,
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false, cutout:'70%',
        plugins: { legend: { position:'bottom', labels: { padding:14, usePointStyle:true, pointStyleWidth:8 } } }
    }
});

const trLabels  = @json($trafficLabels);
const trViews   = @json($trafficViews);
const trUnique  = @json($trafficUnique);
new Chart(document.getElementById('trafficChart'), {
    type: 'line',
    data: {
        labels: trLabels,
        datasets: [
            {
                label: 'Views',
                data: trViews,
                borderColor: navy,
                backgroundColor: 'rgba(11,42,74,0.06)',
                borderWidth: 1.5,
                pointRadius: 0,
                fill: true,
                tension: 0.4,
            },
            {
                label: 'Unik',
                data: trUnique,
                borderColor: gold,
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
                tension: 0.4,
                borderDash: [4,3],
            }
        ]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins: { legend: { position:'bottom', labels: { padding:12, usePointStyle:true, pointStyleWidth:8 } } },
        scales: {
            y: { beginAtZero:true, grid },
            x: { grid: { display:false }, ticks: { maxTicksLimit:8 } }
        }
    }
});

const growthLabels = @json($stats['store_growth']->keys());
const growthData   = @json($stats['store_growth']->values());
new Chart(document.getElementById('storeGrowthChart'), {
    type: 'bar',
    data: {
        labels: growthLabels.length ? growthLabels : ['Belum ada data'],
        datasets: [{
            label: 'Toko baru',
            data: growthData.length ? growthData : [0],
            backgroundColor: 'rgba(11,42,74,0.08)',
            borderColor: navy,
            borderWidth: 1.5,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive:true, maintainAspectRatio:false,
        plugins: { legend: { display:false } },
        scales: {
            x: { beginAtZero:true, ticks: { stepSize:1 }, grid },
            y: { grid: { display:false } }
        }
    }
});
</script>

@endsection