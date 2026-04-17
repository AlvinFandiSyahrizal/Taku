@extends('admin.layouts.sidebar')
@section('page-title', 'Traffic')
@section('content')

<style>
*{box-sizing:border-box}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:500px){.stat-grid{grid-template-columns:1fr 1fr;}}
.stat-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:18px 20px;}
.stat-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:7px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:400;color:#0b2a4a;line-height:1;margin-bottom:3px;}
.stat-sub{font-size:11px;color:rgba(11,42,74,.35);}

.filter-bar{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:14px 18px;margin-bottom:20px;}
.filter-form{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.filter-label{font-size:11px;color:rgba(11,42,74,.5);white-space:nowrap;}
.filter-input{border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:7px 11px;font-size:12px;color:#0b2a4a;background:#f9f7f2;outline:none;font-family:'DM Sans',sans-serif;}
.filter-input:focus{border-color:#c9a96e;background:white;}
.btn-filter{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:7px 16px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;}
.btn-filter:hover{background:#0d3459;}
.btn-export{background:#c9a96e;color:#0b2a4a;border-radius:8px;padding:7px 14px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;font-family:'DM Sans',sans-serif;transition:.2s;}
.btn-export:hover{background:#b8965d;}
.btn-preset{background:none;border:.5px solid rgba(11,42,74,.12);border-radius:6px;padding:5px 12px;font-size:11px;color:rgba(11,42,74,.5);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;}
.btn-preset:hover{color:#0b2a4a;border-color:rgba(11,42,74,.25);}

.chart-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);padding:20px;margin-bottom:16px;}
.chart-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:16px;}

.table-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);overflow:hidden;}
.tbl{width:100%;border-collapse:collapse;}
.tbl th{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);font-weight:400;padding:12px 18px;text-align:left;border-bottom:.5px solid rgba(11,42,74,.06);}
.tbl td{padding:11px 18px;font-size:13px;color:#0b2a4a;border-bottom:.5px solid rgba(11,42,74,.04);}
.tbl tr:last-child td{border-bottom:none;}
.tbl tbody tr:hover{background:rgba(11,42,74,.02);}
.bar-wrap{width:80px;height:4px;background:rgba(11,42,74,.06);border-radius:100px;display:inline-block;vertical-align:middle;margin-right:8px;}
.bar-fill{height:100%;background:#c9a96e;border-radius:100px;}
.pagination-wrap{padding:14px 18px;border-top:.5px solid rgba(11,42,74,.06);display:flex;justify-content:flex-end;gap:4px;}
.page-btn{padding:5px 10px;border-radius:6px;font-size:12px;text-decoration:none;border:.5px solid rgba(11,42,74,.12);color:rgba(11,42,74,.6);transition:all .2s;}
.page-btn:hover,.page-btn.active{background:#0b2a4a;color:#f0ebe0;border-color:#0b2a4a;}
</style>

<div class="stat-grid">
    <div class="stat-card">
        <p class="stat-label">Total Views</p>
        <p class="stat-value">{{ number_format($totalViews) }}</p>
        <p class="stat-sub">dalam periode ini</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Pengunjung Unik</p>
        <p class="stat-value">{{ number_format($totalUnique) }}</p>
        <p class="stat-sub">IP berbeda</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Rata-rata/Hari</p>
        <p class="stat-value">{{ number_format($avgPerDay) }}</p>
        <p class="stat-sub">views per hari</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Peak Day</p>
        <p class="stat-value" style="font-size:20px;">{{ $peakDay ? number_format($peakDay->views) : '—' }}</p>
        <p class="stat-sub">{{ $peakDay ? \Carbon\Carbon::parse($peakDay->viewed_date)->format('d M Y') : 'belum ada data' }}</p>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" class="filter-form" id="trafficFilterForm">
        <span class="filter-label">Dari</span>
        <input type="date" name="from" class="filter-input" value="{{ $from }}" max="{{ today()->toDateString() }}">
        <span class="filter-label">sampai</span>
        <input type="date" name="to" class="filter-input" value="{{ $to }}" max="{{ today()->toDateString() }}">
        <button type="submit" class="btn-filter">Terapkan</button>
        <button type="button" class="btn-preset" onclick="setPreset(7)">7 Hari</button>
        <button type="button" class="btn-preset" onclick="setPreset(30)">30 Hari</button>
        <button type="button" class="btn-preset" onclick="setPreset(90)">90 Hari</button>
        <a href="{{ route('admin.traffic.export', ['from'=>$from,'to'=>$to]) }}" class="btn-export" style="margin-left:auto;">Export CSV</a>
    </form>
</div>

<div class="chart-card">
    <p class="chart-title">Views & Pengunjung Unik — {{ \Carbon\Carbon::parse($from)->format('d M') }} s/d {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
    <div style="height:220px;"><canvas id="trafficChart"></canvas></div>
</div>

<div class="table-card">
    <div style="padding:18px 18px 0;display:flex;justify-content:space-between;align-items:center;">
        <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);">Top Halaman</p>
        <p style="font-size:12px;color:rgba(11,42,74,.4);">{{ $topPages->total() }} halaman berbeda</p>
    </div>
    @php $maxV = $topPages->max('views') ?: 1; @endphp
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Halaman</th>
                <th>Views</th>
                <th>Unik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topPages as $i => $page)
            <tr>
                <td style="color:rgba(11,42,74,.3);font-size:12px;">{{ ($topPages->currentPage()-1)*$topPages->perPage() + $i + 1 }}</td>
                <td>
                    <span class="bar-wrap"><span class="bar-fill" style="width:{{ round($page->views/$maxV*100) }}%;"></span></span>
                    <a href="{{ $page->path }}" target="_blank"
                       style="color:#0b2a4a;text-decoration:none;font-size:13px;"
                       onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='#0b2a4a'">
                        {{ $page->path }}
                    </a>
                </td>
                <td style="font-weight:500;">{{ number_format($page->views) }}</td>
                <td style="color:rgba(11,42,74,.5);">{{ number_format($page->unique_visitors) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:40px;color:rgba(11,42,74,.3);font-size:13px;">Belum ada data traffic untuk periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($topPages->hasPages())
    <div class="pagination-wrap">
        @if($topPages->onFirstPage())
            <span class="page-btn" style="opacity:.4;">‹</span>
        @else
            <a href="{{ $topPages->previousPageUrl() }}&from={{ $from }}&to={{ $to }}" class="page-btn">‹</a>
        @endif

        @foreach($topPages->getUrlRange(max(1,$topPages->currentPage()-2), min($topPages->lastPage(),$topPages->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}&from={{ $from }}&to={{ $to }}"
               class="page-btn {{ $page === $topPages->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($topPages->hasMorePages())
            <a href="{{ $topPages->nextPageUrl() }}&from={{ $from }}&to={{ $to }}" class="page-btn">›</a>
        @else
            <span class="page-btn" style="opacity:.4;">›</span>
        @endif
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font = {family:"'DM Sans',sans-serif",size:11};
Chart.defaults.color = 'rgba(11,42,74,0.45)';

new Chart(document.getElementById('trafficChart'), {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [
            {
                label: 'Views',
                data: @json($views),
                borderColor: '#0b2a4a',
                backgroundColor: 'rgba(11,42,74,0.05)',
                borderWidth: 2,
                pointRadius: 2,
                fill: true,
                tension: 0.4,
            },
            {
                label: 'Unik',
                data: @json($unique),
                borderColor: '#c9a96e',
                backgroundColor: 'transparent',
                borderWidth: 1.5,
                pointRadius: 2,
                borderDash: [4,3],
                tension: 0.4,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position:'bottom', labels: { padding:14, usePointStyle:true, pointStyleWidth:8 } }
        },
        scales: {
            y: { beginAtZero:true, grid: { color:'rgba(11,42,74,0.05)' } },
            x: { grid: { display:false }, ticks: { maxTicksLimit:10 } }
        }
    }
});

function setPreset(days) {
    const form = document.getElementById('trafficFilterForm');
    const today = new Date().toISOString().split('T')[0];
    const from  = new Date(Date.now() - (days-1)*86400000).toISOString().split('T')[0];
    form.querySelector('[name="from"]').value = from;
    form.querySelector('[name="to"]').value   = today;
    form.submit();
}
</script>

@endsection