@extends('merchant.layouts.sidebar')
@section('page-title', 'Dashboard Toko')
@section('content')

<style>
*{box-sizing:border-box}
:root{
    --navy:#1a3a2a;
    --gold:#c9a96e;
    --terracotta:#c96a3d;
    --beige:#f5f1e8;
    --beige-dark:#ede8dc;
}

.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px){.stat-grid{grid-template-columns:repeat(2,1fr);}}

.stat-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:18px 20px;position:relative;overflow:hidden;}
.stat-card::before{content:'';position:absolute;right:-20px;bottom:-20px;width:80px;height:80px;border-radius:50%;background:var(--beige);opacity:.5;}
.stat-label{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:6px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:400;color:#0b2a4a;line-height:1;margin-bottom:2px;}
.stat-sub{font-size:11px;color:rgba(11,42,74,.35);}
.stat-icon{position:absolute;right:14px;top:14px;opacity:.08;}

.store-hero-card{background:linear-gradient(135deg,#0b2a4a 0%,#1a3a52 100%);border-radius:16px;padding:24px 28px;margin-bottom:20px;display:flex;align-items:center;gap:20px;position:relative;overflow:hidden;}
.store-hero-card::after{content:'';position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:50%;border:.5px solid rgba(201,169,110,.1);}
.store-hero-logo{width:56px;height:56px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(201,169,110,.3);flex-shrink:0;}
.store-hero-logo-init{width:56px;height:56px;border-radius:50%;background:rgba(201,169,110,.15);border:1.5px solid rgba(201,169,110,.3);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:24px;color:#c9a96e;flex-shrink:0;}
.store-hero-name{font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:300;color:#f0ebe0;letter-spacing:.04em;margin-bottom:3px;}
.store-hero-sub{font-size:11px;color:rgba(240,235,224,.4);letter-spacing:.04em;}
.store-hero-badge{margin-left:auto;background:rgba(39,174,96,.12);border:.5px solid rgba(39,174,96,.25);color:#27ae60;font-size:9px;letter-spacing:.1em;text-transform:uppercase;padding:3px 10px;border-radius:100px;flex-shrink:0;}

.quick-actions{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;}
.btn-primary{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:10px 20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:background .2s;}
.btn-primary:hover{background:#0d3459;}
.btn-terracotta{background:var(--terracotta);color:#f0ebe0;border:none;border-radius:8px;padding:10px 20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:background .2s;}
.btn-terracotta:hover{background:#b85c33;}
.btn-outline{background:none;color:rgba(11,42,74,.55);border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:10px 20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:all .2s;}
.btn-outline:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}

.charts-row{display:grid;grid-template-columns:2fr 1fr;gap:14px;margin-bottom:20px;}
@media(max-width:768px){.charts-row{grid-template-columns:1fr;}}
.chart-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:18px 20px;}
.chart-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;}

.period-tabs{display:flex;gap:0;border:.5px solid rgba(11,42,74,.12);border-radius:7px;overflow:hidden;}
.period-tab{padding:4px 10px;font-size:10px;letter-spacing:.08em;text-transform:uppercase;background:none;border:none;cursor:pointer;color:rgba(11,42,74,.45);font-family:'DM Sans',sans-serif;transition:all .2s;}
.period-tab.active{background:#0b2a4a;color:#f0ebe0;}

.bottom-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
@media(max-width:768px){.bottom-row{grid-template-columns:1fr;}}

.recent-table{width:100%;border-collapse:collapse;}
.recent-table th{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);font-weight:400;padding:9px 14px;text-align:left;border-bottom:.5px solid rgba(11,42,74,.06);}
.recent-table td{padding:11px 14px;font-size:12px;color:#0b2a4a;border-bottom:.5px solid rgba(11,42,74,.04);}
.recent-table tr:last-child td{border-bottom:none;}
.status-badge{display:inline-block;padding:2px 8px;border-radius:100px;font-size:10px;letter-spacing:.06em;text-transform:uppercase;font-weight:500;}
.empty-state{text-align:center;padding:28px;color:rgba(11,42,74,.3);font-size:13px;}

.top-prod-item{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:.5px solid rgba(11,42,74,.04);}
.top-prod-item:last-child{border-bottom:none;}
.top-prod-img{width:36px;height:36px;border-radius:6px;object-fit:cover;flex-shrink:0;background:#f5f1e8;}
.top-prod-name{font-size:12px;font-weight:500;color:#0b2a4a;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.top-prod-count{font-size:11px;color:rgba(11,42,74,.4);}

.export-btn{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--terracotta);text-decoration:none;border:.5px solid rgba(201,106,61,.25);border-radius:6px;padding:4px 10px;transition:all .2s;}
.export-btn:hover{background:var(--terracotta);color:white;}

.pending-banner{background:rgba(201,106,61,.08);border:.5px solid rgba(201,106,61,.2);border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:12px;}
.pending-banner-icon{width:32px;height:32px;border-radius:50%;background:var(--terracotta);display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;}
.pending-banner-text{flex:1;}
.pending-banner-title{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:2px;}
.pending-banner-sub{font-size:11px;color:rgba(11,42,74,.5);}
</style>

<div class="store-hero-card">
    @if($store->logo)
        <img src="{{ asset($store->logo) }}" class="store-hero-logo" alt="{{ $store->name }}">
    @else
        <div class="store-hero-logo-init">{{ strtoupper(substr($store->name,0,1)) }}</div>
    @endif
    <div>
        <p class="store-hero-name">{{ $store->name }}</p>
        <p class="store-hero-sub">Aktif sejak {{ $store->approved_at?->format('d M Y') }}</p>
    </div>
    <span class="store-hero-badge">● Aktif</span>
</div>

@if($stats['pending_orders'] > 0)
<div class="pending-banner">
    <div class="pending-banner-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
    </div>
    <div class="pending-banner-text">
        <p class="pending-banner-title">{{ $stats['pending_orders'] }} pesanan menunggu konfirmasi</p>
        <p class="pending-banner-sub">Segera proses agar pembeli tidak menunggu terlalu lama.</p>
    </div>
    <a href="{{ route('merchant.orders.index') }}" class="btn-terracotta" style="font-size:10px;padding:8px 14px;">Lihat Pesanan</a>
</div>
@endif

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/></svg>
        </div>
        <p class="stat-label">Total Produk</p>
        <p class="stat-value">{{ $stats['total_products'] }}</p>
        <p class="stat-sub">{{ $stats['active_products'] }} aktif di toko</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
        </div>
        <p class="stat-label">Total Pesanan</p>
        <p class="stat-value">{{ $stats['total_orders'] }}</p>
        @if($stats['pending_orders'] > 0)
        <p class="stat-sub" style="color:var(--terracotta);">{{ $stats['pending_orders'] }} pending</p>
        @else
        <p class="stat-sub">semua diproses</p>
        @endif
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="stat-label">Pembeli Unik</p>
        <p class="stat-value">{{ $stats['unique_buyers'] }}</p>
        <p class="stat-sub">dari semua pesanan</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0b2a4a" stroke-width="1"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <p class="stat-label">Pendapatan</p>
        <p class="stat-value" style="font-size:20px;">Rp {{ number_format($stats['total_revenue'],0,',','.') }}</p>
        <p class="stat-sub">dari pesanan selesai</p>
    </div>
</div>

<div class="quick-actions">
    <a href="{{ route('merchant.products.create') }}" class="btn-primary">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Produk
    </a>
    <a href="{{ route('merchant.store.appearance') }}" class="btn-terracotta">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        Tampilan Toko
    </a>
    <a href="{{ route('store.show', $store->slug) }}" class="btn-outline" target="_blank">
        ↗ Lihat Toko
    </a>
    <a href="{{ route('merchant.orders.export') }}" class="btn-outline">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export CSV
    </a>
</div>

<div class="charts-row">
    <div class="chart-card">
        <div class="chart-title">
            Pendapatan
            <div class="period-tabs">
                <button class="period-tab active" onclick="switchPeriod('monthly', this)">Bulanan</button>
                <button class="period-tab" onclick="switchPeriod('daily', this)">Harian</button>
            </div>
        </div>
        <div style="height:180px;"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="chart-card">
        <div class="chart-title">Status Pesanan</div>
        <div style="height:180px;display:flex;align-items:center;justify-content:center;">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
</div>

<div class="bottom-row">
    <div class="chart-card">
        <div class="chart-title">
            Pesanan Terbaru
            <a href="{{ route('merchant.orders.index') }}" class="export-btn">Lihat Semua</a>
        </div>
        <table class="recent-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                @php
                    $colors = ['pending'=>'#e67e22','confirmed'=>'#2980b9','shipped'=>'#8e44ad','completed'=>'#27ae60','cancelled'=>'#c0392b'];
                    $labels = ['pending'=>'Pending','confirmed'=>'Dikonfirmasi','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Batal'];
                    $color = $colors[$order->status] ?? '#888';
                    $label = $labels[$order->status] ?? $order->status;
                @endphp
                <tr>
                    <td style="font-weight:500;font-size:11px;">{{ $order->order_code }}</td>
                    <td>{{ $order->name }}</td>
                    <td>Rp {{ number_format($order->total,0,',','.') }}</td>
                    <td>
                        <span class="status-badge" style="background:{{ $color }}18;color:{{ $color }};">{{ $label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="empty-state">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="chart-card">
        <div class="chart-title">Produk Terlaris</div>
        @forelse($topProducts as $product)
        <div class="top-prod-item">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="top-prod-img" alt="{{ $product->name }}">
            @else
                <div class="top-prod-img"></div>
            @endif
            <span class="top-prod-name">{{ $product->name }}</span>
            <span class="top-prod-count">{{ $product->order_items_count }} terjual</span>
        </div>
        @empty
        <p class="empty-state">Belum ada data penjualan</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font = { family:"'DM Sans',sans-serif", size:11 };
Chart.defaults.color = 'rgba(44,24,16,0.45)';
const gridColor = { color:'rgba(44,24,16,0.05)' };

const monthlyLabels = @json($revenueMonthly->keys());
const monthlyData   = @json($revenueMonthly->values());
const dailyLabels   = @json($revenueDaily->keys());
const dailyData     = @json($revenueDaily->values());

const revenueCtx = document.getElementById('revenueChart');
let revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: monthlyLabels.length ? monthlyLabels : ['Belum ada data'],
        datasets: [{
            label: 'Pendapatan',
            data: monthlyData.length ? monthlyData : [0],
            backgroundColor: 'rgba(201,106,61,0.15)',
            borderColor: '#c96a3d',
            borderWidth: 1.5,
            borderRadius: 6,
            borderSkipped: false,
            maxBarThickness: 40,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: { label: ctx => 'Rp ' + Number(ctx.raw).toLocaleString('id-ID') }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: gridColor,
                ticks: { callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') }
            },
            x: { grid: { display: false } }
        }
    }
});

function switchPeriod(period, btn) {
    document.querySelectorAll('.period-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const labels = period === 'monthly' ? monthlyLabels : dailyLabels;
    const data   = period === 'monthly' ? monthlyData   : dailyData;
    revenueChart.data.labels = labels.length ? labels : ['Belum ada data'];
    revenueChart.data.datasets[0].data = data.length ? data : [0];
    revenueChart.update('active');
}

const statusData = @json($orderStatusData);
if (statusData.length > 0) {
    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(s => s.label),
            datasets: [{
                data: statusData.map(s => s.count),
                backgroundColor: statusData.map(s => s.color),
                borderWidth: 0,
                hoverOffset: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 10, usePointStyle: true, pointStyleWidth: 8 }
                }
            }
        }
    });
}
</script>

@endsection
