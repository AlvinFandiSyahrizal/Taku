@extends('admin.layouts.sidebar')
@section('page-title', 'Laporan')
@section('content')

<style>
*{box-sizing:border-box}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
.stat-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:18px 20px;}
.stat-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:7px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:30px;font-weight:400;color:#0b2a4a;line-height:1;margin-bottom:3px;}
.stat-sub{font-size:11px;color:rgba(11,42,74,.35);}

.filter-bar{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:14px 18px;margin-bottom:20px;}
.filter-form{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.filter-label{font-size:11px;color:rgba(11,42,74,.5);white-space:nowrap;}
.filter-input{border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:7px 11px;font-size:12px;color:#0b2a4a;background:#f9f7f2;outline:none;font-family:'DM Sans',sans-serif;}
.filter-input:focus{border-color:#c9a96e;background:white;}
.btn-filter{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:7px 16px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;}
.btn-preset{background:none;border:.5px solid rgba(11,42,74,.12);border-radius:6px;padding:5px 12px;font-size:11px;color:rgba(11,42,74,.5);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;}
.btn-preset:hover{color:#0b2a4a;border-color:rgba(11,42,74,.25);}
.btn-export{background:#c9a96e;color:#0b2a4a;border-radius:8px;padding:7px 14px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;font-family:'DM Sans',sans-serif;margin-left:auto;}
.btn-export:hover{background:#b8965d;}

.grid-2{display:grid;grid-template-columns:3fr 2fr;gap:16px;margin-bottom:16px;}
@media(max-width:800px){.grid-2{grid-template-columns:1fr;}}
.chart-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:20px;}
.chart-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:16px;}

.status-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.status-item{padding:12px 14px;border-radius:8px;background:rgba(11,42,74,.03);border:.5px solid rgba(11,42,74,.06);}
.status-item-label{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:4px;}
.status-item-val{font-size:20px;font-family:'Cormorant Garamond',serif;color:#0b2a4a;font-weight:400;}

.table-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);overflow:hidden;margin-bottom:16px;}
.tbl{width:100%;border-collapse:collapse;}
.tbl th{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);font-weight:400;padding:12px 18px;text-align:left;border-bottom:.5px solid rgba(11,42,74,.06);}
.tbl td{padding:11px 18px;font-size:13px;color:#0b2a4a;border-bottom:.5px solid rgba(11,42,74,.04);}
.tbl tr:last-child td{border-bottom:none;}
.tbl tbody tr:hover{background:rgba(11,42,74,.02);}
.status-badge{display:inline-block;padding:3px 9px;border-radius:100px;font-size:10px;letter-spacing:.07em;text-transform:uppercase;font-weight:500;}
.empty-row{text-align:center;padding:36px;color:rgba(11,42,74,.3);font-size:13px;}
</style>

<div class="stat-grid">
    <div class="stat-card">
        <p class="stat-label">Total Pesanan</p>
        <p class="stat-value">{{ $totalOrders }}</p>
        <p class="stat-sub">dalam periode ini</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Pendapatan</p>
        <p class="stat-value" style="font-size:20px;">Rp {{ number_format($totalRevenue,0,',','.') }}</p>
        <p class="stat-sub">pesanan selesai</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Rata-rata/Pesanan</p>
        <p class="stat-value" style="font-size:20px;">
            Rp {{ $totalOrders > 0 ? number_format(round($totalRevenue / max($byStatus->get('completed',0),1)),0,',','.') : '0' }}
        </p>
        <p class="stat-sub">nilai rata-rata</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">User Baru</p>
        <p class="stat-value">{{ $newUsers }}</p>
        <p class="stat-sub">registrasi baru</p>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" class="filter-form" id="reportForm">
        <span class="filter-label">Dari</span>
        <input type="date" name="from" class="filter-input" value="{{ $from }}">
        <span class="filter-label">sampai</span>
        <input type="date" name="to" class="filter-input" value="{{ $to }}">
        <button type="submit" class="btn-filter">Terapkan</button>
        <button type="button" class="btn-preset" onclick="setPreset(7)">7 Hari</button>
        <button type="button" class="btn-preset" onclick="setPreset(30)">30 Hari</button>
        <button type="button" class="btn-preset" onclick="setPreset('month')">Bulan Ini</button>
        <a href="{{ route('admin.reports.export', ['from'=>$from,'to'=>$to]) }}" class="btn-export">Export CSV</a>
    </form>
</div>

<div class="grid-2">
    <div class="chart-card">
        <p class="chart-title">Pendapatan Harian</p>
        <div style="height:180px;"><canvas id="revChart"></canvas></div>
    </div>
    <div class="chart-card">
        <p class="chart-title">Status Pesanan</p>
        @php
        $statusLabels = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
        $statusColors = ['pending'=>'#e67e22','confirmed'=>'#2980b9','shipped'=>'#8e44ad','completed'=>'#27ae60','cancelled'=>'#c0392b'];
        @endphp
        <div class="status-grid">
            @foreach($statusLabels as $key => $label)
            <div class="status-item">
                <p class="status-item-label" style="color:{{ $statusColors[$key] }};">{{ $label }}</p>
                <p class="status-item-val">{{ $byStatus->get($key, 0) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="table-card">
    <div style="padding:16px 18px 0;">
        <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);">Produk Terlaris</p>
    </div>
    @php $maxSold = $topProducts->max('total_sold') ?: 1; @endphp
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Terjual</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $i => $item)
            <tr>
                <td style="color:rgba(11,42,74,.3);font-size:12px;">{{ $i+1 }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:50px;height:4px;background:rgba(11,42,74,.06);border-radius:100px;">
                            <div style="height:100%;background:#c9a96e;border-radius:100px;width:{{ round($item->total_sold/$maxSold*100) }}%;"></div>
                        </div>
                        {{ $item->product_name }}
                    </div>
                </td>
                <td style="font-weight:500;">{{ $item->total_sold }}x</td>
                <td>Rp {{ number_format($item->total_revenue,0,',','.') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="empty-row">Belum ada data penjualan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="table-card">
    <div style="padding:16px 18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;border-bottom:.5px solid rgba(11,42,74,.06);">
        <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);">
            Detail Pesanan
            <span style="font-weight:400;text-transform:none;letter-spacing:0;margin-left:8px;color:rgba(11,42,74,.35);">
                {{ $orders->total() }} pesanan ditemukan
            </span>
        </p>
        <form method="GET" style="display:flex;gap:8px;align-items:center;">
            <input type="hidden" name="from" value="{{ $from }}">
            <input type="hidden" name="to" value="{{ $to }}">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari kode, nama, produk..."
                   style="padding:7px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:12px;color:#0b2a4a;outline:none;font-family:'DM Sans',sans-serif;width:220px;background:white;"
                   onfocus="this.style.borderColor='#c9a96e'" onblur="this.style.borderColor='rgba(11,42,74,.15)'">
            <button type="submit"
                    style="background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:7px 14px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;">
                Cari
            </button>
            @if($search)
            <a href="{{ route('admin.reports.index', ['from'=>$from,'to'=>$to]) }}"
               style="font-size:11px;color:rgba(11,42,74,.4);text-decoration:none;border:.5px solid rgba(11,42,74,.12);border-radius:6px;padding:7px 12px;">
                ✕ Reset
            </a>
            @endif
        </form>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php $s = $order->getStatusLabel(); @endphp
            <tr>
                <td style="font-weight:500;font-size:12px;">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       style="color:#0b2a4a;text-decoration:none;"
                       onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='#0b2a4a'">
                        {{ $order->order_code }}
                    </a>
                </td>
                <td>
                    <p style="font-size:13px;">{{ $order->name }}</p>
                    <p style="font-size:11px;color:rgba(11,42,74,.4);">{{ $order->phone }}</p>
                </td>
                <td style="font-size:12px;color:rgba(11,42,74,.5);">
                    {{ $order->items->take(2)->pluck('product_name')->implode(', ') }}
                    @if($order->items->count() > 2)
                        <span style="color:rgba(11,42,74,.35);">+{{ $order->items->count()-2 }} lagi</span>
                    @endif
                </td>
                <td style="font-weight:500;">{{ $order->getTotalFormatted() }}</td>
                <td>
                    <span class="status-badge" style="background:{{ $s['color'] }}18;color:{{ $s['color'] }};">
                        {{ $s['label'] }}
                    </span>
                </td>
                <td style="color:rgba(11,42,74,.45);font-size:11px;white-space:nowrap;">
                    {{ $order->created_at->format('d M Y') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-row">
                    @if($search)
                        Tidak ada pesanan yang cocok dengan "{{ $search }}"
                    @else
                        Tidak ada pesanan dalam periode ini.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div style="padding:14px 18px;border-top:.5px solid rgba(11,42,74,.06);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <p style="font-size:12px;color:rgba(11,42,74,.4);">
            Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }}
        </p>
        <div style="display:flex;gap:4px;">
            @if($orders->onFirstPage())
                <span style="padding:5px 10px;border-radius:6px;font-size:12px;border:.5px solid rgba(11,42,74,.08);color:rgba(11,42,74,.25);">‹</span>
            @else
                <a href="{{ $orders->previousPageUrl() }}"
                   style="padding:5px 10px;border-radius:6px;font-size:12px;text-decoration:none;border:.5px solid rgba(11,42,74,.12);color:rgba(11,42,74,.6);">‹</a>
            @endif

            @foreach($orders->getUrlRange(max(1,$orders->currentPage()-2), min($orders->lastPage(),$orders->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}"
                   style="padding:5px 10px;border-radius:6px;font-size:12px;text-decoration:none;
                          background:{{ $page === $orders->currentPage() ? '#0b2a4a' : 'none' }};
                          color:{{ $page === $orders->currentPage() ? '#f0ebe0' : 'rgba(11,42,74,.6)' }};
                          border:.5px solid {{ $page === $orders->currentPage() ? '#0b2a4a' : 'rgba(11,42,74,.12)' }};">
                    {{ $page }}
                </a>
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}"
                   style="padding:5px 10px;border-radius:6px;font-size:12px;text-decoration:none;border:.5px solid rgba(11,42,74,.12);color:rgba(11,42,74,.6);">›</a>
            @else
                <span style="padding:5px 10px;border-radius:6px;font-size:12px;border:.5px solid rgba(11,42,74,.08);color:rgba(11,42,74,.25);">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font = {family:"'DM Sans',sans-serif",size:11};
Chart.defaults.color = 'rgba(11,42,74,0.45)';

new Chart(document.getElementById('revChart'), {
    type: 'bar',
    data: {
        labels: @json($revLabels),
        datasets: [{
            label: 'Pendapatan',
            data: @json($revData),
            backgroundColor: 'rgba(201,169,110,0.15)',
            borderColor: '#c9a96e',
            borderWidth: 1.5,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(201,169,110,0.3)',
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins: {
            legend: { display:false },
            tooltip: {
                callbacks: { label: ctx => 'Rp '+Number(ctx.raw).toLocaleString('id-ID') }
            }
        },
        scales: {
            y: { beginAtZero:true, grid:{color:'rgba(11,42,74,0.05)'}, ticks:{callback:v=>'Rp '+Number(v/1000).toLocaleString('id-ID')+'k'} },
            x: { grid:{display:false}, ticks:{maxTicksLimit:10} }
        }
    }
});

function setPreset(val) {
    const f = document.getElementById('reportForm');
    const today = new Date().toISOString().split('T')[0];
    if (val === 'month') {
        const now = new Date();
        f.querySelector('[name="from"]').value = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
    } else {
        f.querySelector('[name="from"]').value = new Date(Date.now()-(val-1)*86400000).toISOString().split('T')[0];
    }
    f.querySelector('[name="to"]').value = today;
    f.submit();
}
</script>

@endsection
