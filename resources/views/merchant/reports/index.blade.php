@extends('merchant.layouts.sidebar')
@section('page-title', 'Laporan & Keuangan')
@section('content')

<style>
*{box-sizing:border-box}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
.stat-card{background:white;border-radius:12px;border:.5px solid rgba(44,24,16,.08);padding:18px 20px;}
.stat-label{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(44,24,16,.4);margin-bottom:6px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:30px;font-weight:400;color:#2c1810;line-height:1;margin-bottom:3px;}
.stat-sub{font-size:11px;color:rgba(44,24,16,.35);}

.filter-bar{background:white;border-radius:12px;border:.5px solid rgba(44,24,16,.08);padding:14px 18px;margin-bottom:20px;}
.filter-form{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.filter-label{font-size:11px;color:rgba(44,24,16,.5);white-space:nowrap;}
.filter-input{border:.5px solid rgba(44,24,16,.15);border-radius:8px;padding:7px 11px;font-size:12px;color:#2c1810;background:#faf7f2;outline:none;font-family:'DM Sans',sans-serif;}
.filter-input:focus{border-color:#c96a3d;background:white;}
.btn-filter{background:#2c1810;color:#f5f1e8;border:none;border-radius:8px;padding:7px 16px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;}
.btn-preset{background:none;border:.5px solid rgba(44,24,16,.12);border-radius:6px;padding:5px 12px;font-size:11px;color:rgba(44,24,16,.5);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;}
.btn-preset:hover{color:#2c1810;border-color:rgba(44,24,16,.25);}
.btn-export{background:#c96a3d;color:#f5f1e8;border:none;border-radius:8px;padding:7px 14px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;font-family:'DM Sans',sans-serif;margin-left:auto;}
.btn-export:hover{background:#b85c33;}

.grid-2{display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:16px;}
@media(max-width:800px){.grid-2{grid-template-columns:1fr;}}
.chart-card{background:white;border-radius:12px;border:.5px solid rgba(44,24,16,.08);padding:20px;}
.chart-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(44,24,16,.4);margin-bottom:16px;}

.status-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.status-item{padding:11px 13px;border-radius:8px;background:rgba(44,24,16,.03);border:.5px solid rgba(44,24,16,.06);}
.status-item-label{font-size:10px;letter-spacing:.1em;text-transform:uppercase;margin-bottom:3px;}
.status-item-val{font-size:20px;font-family:'Cormorant Garamond',serif;color:#2c1810;}

.table-card{background:white;border-radius:12px;border:.5px solid rgba(44,24,16,.08);overflow:hidden;margin-bottom:16px;}
.tbl{width:100%;border-collapse:collapse;}
.tbl th{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(44,24,16,.4);font-weight:400;padding:11px 16px;text-align:left;border-bottom:.5px solid rgba(44,24,16,.06);}
.tbl td{padding:11px 16px;font-size:12px;color:#2c1810;border-bottom:.5px solid rgba(44,24,16,.04);}
.tbl tr:last-child td{border-bottom:none;}
.tbl tbody tr:hover{background:rgba(44,24,16,.02);}
.status-badge{display:inline-block;padding:2px 8px;border-radius:100px;font-size:10px;letter-spacing:.06em;text-transform:uppercase;font-weight:500;}
.empty-row{text-align:center;padding:32px;color:rgba(44,24,16,.3);font-size:13px;}

.low-stock-banner{background:rgba(201,106,61,.08);border:.5px solid rgba(201,106,61,.2);border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.low-stock-item{display:inline-flex;align-items:center;gap:6px;background:white;border:.5px solid rgba(201,106,61,.2);border-radius:100px;padding:3px 10px;font-size:11px;color:#2c1810;}

.pagination-wrap{padding:14px 16px;display:flex;justify-content:space-between;align-items:center;border-top:.5px solid rgba(44,24,16,.06);}
.pagination-info{font-size:11px;color:rgba(44,24,16,.4);}
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
        <p class="stat-label">Rata-rata</p>
        <p class="stat-value" style="font-size:20px;">
            Rp {{ $byStatus->get('completed',0) > 0 ? number_format(round($totalRevenue / $byStatus->get('completed',1)),0,',','.') : '0' }}
        </p>
        <p class="stat-sub">per pesanan selesai</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Stok Menipis</p>
        <p class="stat-value" style="{{ $lowStock->count() > 0 ? 'color:#c96a3d;' : '' }}">{{ $lowStock->count() }}</p>
        <p class="stat-sub">produk stok ≤ 5</p>
    </div>
</div>

@if($lowStock->count() > 0)
<div class="low-stock-banner">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c96a3d" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span style="font-size:12px;color:#2c1810;font-weight:500;">Stok menipis:</span>
    @foreach($lowStock as $p)
    <span class="low-stock-item">
        <span style="color:#c96a3d;font-weight:500;">{{ $p->stock }}</span>
        {{ $p->name }}
    </span>
    @endforeach
</div>
@endif

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
        <a href="{{ route('merchant.reports.export', ['from'=>$from,'to'=>$to]) }}" class="btn-export">Export CSV</a>
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
                <p class="status-item-val">{{ $byStatus->get($key,0) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($topProducts->count() > 0)
<div class="table-card" style="margin-bottom:16px;">
    <div style="padding:14px 16px 0;">
        <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(44,24,16,.4);">Produk Terlaris</p>
    </div>
    @php $maxSold = $topProducts->max('total_sold') ?: 1; @endphp
    <table class="tbl">
        <thead>
            <tr><th>#</th><th>Produk</th><th>Terjual</th><th>Pendapatan</th></tr>
        </thead>
        <tbody>
            @foreach($topProducts as $i => $item)
            <tr>
                <td style="color:rgba(44,24,16,.3);font-size:11px;">{{ $i+1 }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:50px;height:4px;background:rgba(44,24,16,.06);border-radius:100px;">
                            <div style="height:100%;background:#c96a3d;border-radius:100px;width:{{ round($item->total_sold/$maxSold*100) }}%;"></div>
                        </div>
                        {{ $item->product_name }}
                    </div>
                </td>
                <td style="font-weight:500;">{{ $item->total_sold }}x</td>
                <td>Rp {{ number_format($item->total_revenue,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="table-card">
    <div style="padding:14px 16px 0;display:flex;justify-content:space-between;align-items:center;">
        <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(44,24,16,.4);">Detail Pesanan</p>
        <p style="font-size:12px;color:rgba(44,24,16,.4);">{{ $orders->total() }} pesanan</p>
    </div>
    <table class="tbl">
        <thead>
            <tr><th>Kode</th><th>Nama</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $s = $statusColors[$order->status] ?? '#888';
                $l = $statusLabels[$order->status] ?? $order->status;
            @endphp
            <tr>
                <td style="font-weight:500;font-size:11px;">{{ $order->order_code }}</td>
                <td>{{ $order->name }}</td>
                <td>Rp {{ number_format($order->total,0,',','.') }}</td>
                <td><span class="status-badge" style="background:{{ $s }}18;color:{{ $s }};">{{ $l }}</span></td>
                <td style="color:rgba(44,24,16,.45);font-size:11px;">{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty-row">Tidak ada pesanan dalam periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="pagination-wrap">
        <span class="pagination-info">Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }}</span>
        {{ $orders->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font = {family:"'DM Sans',sans-serif",size:11};
Chart.defaults.color = 'rgba(44,24,16,0.45)';

new Chart(document.getElementById('revChart'),{
    type:'bar',
    data:{
        labels: @json($revLabels),
        datasets:[{
            label:'Pendapatan',
            data: @json($revData),
            backgroundColor:'rgba(201,106,61,0.15)',
            borderColor:'#c96a3d',
            borderWidth:1.5,
            borderRadius:5,
            maxBarThickness:40,
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,
        plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>'Rp '+Number(ctx.raw).toLocaleString('id-ID')}}},
        scales:{
            y:{beginAtZero:true,grid:{color:'rgba(44,24,16,0.05)'},ticks:{callback:v=>'Rp '+Number(v).toLocaleString('id-ID')}},
            x:{grid:{display:false},ticks:{maxTicksLimit:10}}
        }
    }
});

function setPreset(val){
    const f=document.getElementById('reportForm');
    const today=new Date().toISOString().split('T')[0];
    if(val==='month'){
        const now=new Date();
        f.querySelector('[name="from"]').value=new Date(now.getFullYear(),now.getMonth(),1).toISOString().split('T')[0];
    }else{
        f.querySelector('[name="from"]').value=new Date(Date.now()-(val-1)*86400000).toISOString().split('T')[0];
    }
    f.querySelector('[name="to"]').value=today;
    f.submit();
}
</script>

@endsection
