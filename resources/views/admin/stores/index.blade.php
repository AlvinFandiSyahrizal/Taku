@extends('admin.layouts.sidebar')
@section('page-title', 'Kelola Toko Merchant')
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
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);padding:18px 20px;
    transition:box-shadow .2s;
}
.stat-card:hover{box-shadow:0 4px 16px rgba(59,46,34,.08);}
.stat-label{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--stone);margin-bottom:8px;}
.stat-value{font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:400;line-height:1;margin-bottom:4px;color:var(--bark);}
.stat-sub{font-size:11px;color:var(--stone);}

.filter-bar{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);padding:14px 18px;margin-bottom:20px;
}
.filter-form{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.filter-select,.filter-input-text{
    border:1px solid var(--sand);border-radius:8px;
    padding:7px 12px;font-size:12px;color:var(--bark);
    background:var(--beige);outline:none;
    font-family:'DM Sans',sans-serif;transition:.2s;
}
.filter-select:focus,.filter-input-text:focus{border-color:var(--olive);background:white;}
.filter-input-text{width:200px;}
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

.status-tabs{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap;}
.status-tab{
    padding:6px 16px;border-radius:100px;
    font-size:11px;letter-spacing:.08em;text-transform:uppercase;
    text-decoration:none;border:1px solid var(--sand);
    color:var(--stone);background:var(--cream);
    font-family:'DM Sans',sans-serif;transition:all .2s;white-space:nowrap;
}
.status-tab:hover{color:var(--bark);border-color:var(--stone);}
.status-tab.active{background:var(--olive-dk);color:var(--olive-lt);border-color:var(--olive-dk);}
.status-tab .tab-count{
    display:inline-flex;align-items:center;justify-content:center;
    width:18px;height:18px;border-radius:50%;font-size:10px;
    background:rgba(255,255,255,.2);margin-left:4px;
}
.status-tab:not(.active) .tab-count{background:var(--sand);}

.table-wrap{
    background:var(--cream);border-radius:12px;
    border:1px solid var(--sand);overflow:hidden;
}
.tbl{width:100%;border-collapse:collapse;min-width:680px;}
.tbl th{
    font-size:10px;letter-spacing:.12em;text-transform:uppercase;
    color:var(--stone);font-weight:400;
    padding:13px 18px;text-align:left;
    border-bottom:.5px solid var(--sand);background:var(--sand-lt);
}
.tbl td{
    padding:14px 18px;font-size:13px;color:var(--bark);
    border-bottom:.5px solid var(--sand);vertical-align:middle;
}
.tbl tr:last-child td{border-bottom:none;}
.tbl tbody tr:hover td{background:var(--sand-lt);}

.store-name{font-weight:500;font-size:13px;color:var(--bark);margin-bottom:2px;}
.store-owner{font-size:11px;color:var(--stone);margin-bottom:2px;}
.store-phone{font-size:11px;color:var(--stone);}
.reject-reason{font-size:11px;color:#c47820;margin-top:4px;font-style:italic;}
.rejection-count{
    display:inline-block;font-size:10px;padding:2px 7px;
    border-radius:100px;background:rgba(196,105,79,.1);
    color:var(--terra);margin-top:3px;
}

.store-logo-sm{
    width:36px;height:36px;border-radius:50%;
    object-fit:cover;border:1.5px solid var(--sand);
}
.store-logo-init{
    width:36px;height:36px;border-radius:50%;
    background:var(--sand-lt);border:1.5px solid var(--sand);
    display:flex;align-items:center;justify-content:center;
    font-family:'Cormorant Garamond',serif;font-size:16px;color:var(--olive-dk);
}
.store-logo-cell{display:flex;align-items:center;gap:12px;}

.status-badge{
    display:inline-block;padding:4px 12px;border-radius:100px;
    font-size:10px;letter-spacing:.08em;text-transform:uppercase;font-weight:500;
}

.action-group{display:flex;gap:6px;align-items:center;flex-wrap:wrap;}
.btn-action{
    border-radius:6px;padding:6px 12px;font-size:10px;
    letter-spacing:.08em;text-transform:uppercase;
    cursor:pointer;font-family:'DM Sans',sans-serif;
    transition:all .2s;border:1px solid transparent;
    display:inline-flex;align-items:center;gap:4px;
    text-decoration:none;
}
.btn-approve{background:#eaf0e4;color:var(--olive-dk);border-color:#c0ceb0;}
.btn-approve:hover{background:var(--olive-dk);color:var(--olive-lt);border-color:var(--olive-dk);}
.btn-reject{background:transparent;color:var(--stone);border-color:var(--sand);}
.btn-reject:hover{color:var(--bark);border-color:var(--stone);}
.btn-ban{background:rgba(196,105,79,.1);color:var(--terra);border-color:rgba(196,105,79,.3);}
.btn-ban:hover{background:var(--terra);color:white;border-color:var(--terra);}
.btn-unban{background:#eaf0e4;color:var(--olive-dk);border-color:#c0ceb0;}
.btn-unban:hover{background:var(--olive-dk);color:var(--olive-lt);}
.btn-detail{background:transparent;color:var(--stone);border-color:var(--sand);}
.btn-detail:hover{color:var(--olive-dk);border-color:var(--olive);}

.product-bar-wrap{display:flex;align-items:center;gap:8px;}
.product-bar-track{width:50px;height:3px;background:var(--sand);border-radius:100px;}
.product-bar-fill{height:100%;background:var(--olive);border-radius:100px;}

.pagination-wrap{
    padding:14px 18px;border-top:.5px solid var(--sand);
    display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;
}
.pagination-info{font-size:12px;color:var(--stone);}
.pagination-btns{display:flex;gap:4px;}
.page-btn{
    padding:5px 10px;border-radius:6px;font-size:12px;
    text-decoration:none;border:1px solid var(--sand);
    color:var(--stone);background:var(--cream);transition:all .15s;
}
.page-btn:hover{background:var(--sand-lt);color:var(--bark);}
.page-btn.active{background:var(--olive-dk);color:var(--olive-lt);border-color:var(--olive-dk);}
.page-btn.disabled{opacity:.4;pointer-events:none;}

.empty-cell{text-align:center;padding:48px;color:var(--stone);font-size:13px;}

.modal-overlay{
    display:none;position:fixed;inset:0;
    background:rgba(59,46,34,.45);z-index:100;
    align-items:center;justify-content:center;
    backdrop-filter:blur(2px);
}
.modal-overlay.show{display:flex;}
.modal-box{
    background:var(--cream);border-radius:16px;
    padding:32px;width:100%;max-width:460px;
    border:1px solid var(--sand);
    box-shadow:0 20px 60px rgba(59,46,34,.2);
}
.modal-title{font-size:16px;font-weight:500;color:var(--bark);margin-bottom:6px;}
.modal-sub{font-size:13px;color:var(--stone);margin-bottom:20px;line-height:1.6;}
.modal-textarea{
    width:100%;padding:11px 14px;
    border:1px solid var(--sand);border-radius:8px;
    font-size:13px;color:var(--bark);background:var(--beige);
    font-family:'DM Sans',sans-serif;outline:none;
    resize:vertical;min-height:90px;box-sizing:border-box;
    transition:border-color .2s;
}
.modal-textarea:focus{border-color:var(--olive);background:white;}
.modal-footer{display:flex;gap:10px;margin-top:18px;justify-content:flex-end;}
.btn-modal-cancel{
    background:transparent;color:var(--stone);
    border:1px solid var(--sand);border-radius:8px;
    padding:10px 20px;font-size:11px;letter-spacing:.08em;
    text-transform:uppercase;cursor:pointer;
    font-family:'DM Sans',sans-serif;transition:all .2s;
}
.btn-modal-cancel:hover{color:var(--bark);border-color:var(--stone);}
.btn-modal-submit{
    background:var(--olive-dk);color:var(--olive-lt);border:none;
    border-radius:8px;padding:10px 20px;font-size:11px;
    letter-spacing:.08em;text-transform:uppercase;
    cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;
}
.btn-modal-submit:hover{background:var(--olive);}

@media(max-width:768px){
    .stat-grid{grid-template-columns:repeat(2,1fr);}
    .table-wrap{overflow-x:auto;}
    .action-group{flex-direction:column;gap:4px;}
    .action-group .btn-action{width:100%;justify-content:center;}
    .filter-form{gap:6px;}
}
</style>

@php
    $pendingStores = \App\Models\Store::where('status','pending')->count();
    $rejectedCount = \App\Models\Store::where('rejection_count','>',0)->count();
@endphp
<div class="stat-grid">
    <div class="stat-card">
        <p class="stat-label">Menunggu Approval</p>
        <p class="stat-value" style="{{ $stats['pending']>0 ? 'color:var(--terra);' : 'color:var(--olive-dk);' }}">
            {{ $stats['pending'] }}
        </p>
        <p class="stat-sub">{{ $stats['pending']>0 ? 'perlu ditinjau' : 'tidak ada antrian' }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Toko Aktif</p>
        <p class="stat-value" style="color:var(--olive-dk);">{{ $stats['active'] }}</p>
        <p class="stat-sub">merchant berjualan</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Pernah Ditolak</p>
        <p class="stat-value">{{ $rejectedCount }}</p>
        <p class="stat-sub">punya riwayat penolakan</p>
    </div>
    <div class="stat-card">
        <p class="stat-label">Dibanned</p>
        <p class="stat-value" style="{{ $stats['banned']>0 ? 'color:var(--terra);' : '' }}">{{ $stats['banned'] }}</p>
        <p class="stat-sub">toko dinonaktifkan</p>
    </div>
</div>

@php
    $statusFilter = request('status','');
    $searchQuery  = request('search','');
@endphp
<div class="filter-bar">
    <form method="GET" class="filter-form">
        <input type="hidden" name="status" value="{{ $statusFilter }}">
        <input type="text" name="search" value="{{ $searchQuery }}"
               placeholder="Cari nama toko atau pemilik..."
               class="filter-input-text">
        <button type="submit" class="btn-filter">Cari</button>
        @if($searchQuery || $statusFilter)
            <a href="{{ route('admin.stores.index') }}" class="btn-reset">Reset</a>
        @endif
    </form>
</div>

<div class="status-tabs">
    @php
        $tabItems = [
            '' => ['label' => 'Semua', 'count' => $stats['pending']+$stats['active']+$stats['banned']],
            'pending'  => ['label' => 'Pending',  'count' => $stats['pending']],
            'active'   => ['label' => 'Aktif',    'count' => $stats['active']],
            'banned'   => ['label' => 'Banned',   'count' => $stats['banned']],
        ];
    @endphp
    @foreach($tabItems as $val => $tab)
    <a href="{{ route('admin.stores.index', array_merge(request()->except('status','page'), ['status'=>$val, 'search'=>$searchQuery])) }}"
       class="status-tab {{ $statusFilter === $val ? 'active' : '' }}">
        {{ $tab['label'] }}
        <span class="tab-count">{{ $tab['count'] }}</span>
    </a>
    @endforeach
</div>

@php
    $storesQuery = \App\Models\Store::with('user')->withCount('products')->latest();
    if ($statusFilter) $storesQuery->where('status', $statusFilter);
    if ($searchQuery) {
        $storesQuery->where(function($q) use ($searchQuery) {
            $q->where('name','like',"%{$searchQuery}%")
              ->orWhereHas('user', fn($u) => $u->where('name','like',"%{$searchQuery}%"));
        });
    }
    $paginatedStores = $storesQuery->paginate(10)->withQueryString();
    $maxProductCount = \App\Models\Store::withCount('products')->orderByDesc('products_count')->first()?->products_count ?: 1;
@endphp

<div class="table-wrap">
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:52px;"></th>
                    <th>Toko & Pemilik</th>
                    <th>Produk</th>
                    <th>Status</th>
                    <th>Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginatedStores as $store)
                @php $s = $store->getStatusLabel(); @endphp

                <form id="approve-{{ $store->id }}" action="{{ route('admin.stores.approve', $store) }}" method="POST" style="display:none;">@csrf</form>
                <form id="ban-{{ $store->id }}" action="{{ route('admin.stores.ban', $store) }}" method="POST" style="display:none;">@csrf</form>

                <tr>
                    <td>
                        @if($store->logo)
                            <img src="{{ asset($store->logo) }}" class="store-logo-sm" alt="{{ $store->name }}">
                        @else
                            <div class="store-logo-init">{{ strtoupper(substr($store->name,0,1)) }}</div>
                        @endif
                    </td>
                    <td>
                        <p class="store-name">{{ $store->name }}</p>
                        <p class="store-owner">{{ $store->user->name }}</p>
                        @if($store->phone)<p class="store-phone">{{ $store->phone }}</p>@endif
                        @if($store->reject_reason)
                            <p class="reject-reason">⚠ {{ Str::limit($store->reject_reason, 60) }}</p>
                        @endif
                        @if($store->rejection_count > 0)
                            <span class="rejection-count">{{ $store->rejection_count }}× ditolak</span>
                        @endif
                    </td>
                    <td>
                        <div class="product-bar-wrap">
                            <div class="product-bar-track">
                                <div class="product-bar-fill" style="width:{{ round($store->products_count/$maxProductCount*100) }}%;"></div>
                            </div>
                            <span style="font-size:13px;font-weight:500;color:var(--olive-dk);">{{ $store->products_count }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge" style="background:{{ $s['color'] }}18;color:{{ $s['color'] }};border:.5px solid {{ $s['color'] }}35;">
                            {{ $s['label'] }}
                        </span>
                    </td>
                    <td style="color:var(--stone);font-size:12px;white-space:nowrap;">
                        {{ $store->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="action-group">
                            {{-- Detail analytics --}}
                            <a href="{{ route('admin.merchants.show', $store) }}" class="btn-action btn-detail">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                Detail
                            </a>

                            @if($store->status !== 'active')
                            <button class="btn-action btn-approve"
                                    onclick="document.getElementById('approve-{{ $store->id }}').submit()">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                Approve
                            </button>
                            @endif

                            @if($store->status === 'pending')
                            <button class="btn-action btn-reject"
                                    onclick="openReject({{ $store->id }}, '{{ addslashes($store->name) }}')">
                                Tolak
                            </button>
                            @endif

                            @if($store->status === 'active')
                            <button class="btn-action btn-ban"
                                    onclick="if(confirm('Ban toko \'{{ addslashes($store->name) }}\'?')) document.getElementById('ban-{{ $store->id }}').submit()">
                                Ban
                            </button>
                            @endif

                            @if($store->status === 'banned')
                            <button class="btn-action btn-unban"
                                    onclick="document.getElementById('approve-{{ $store->id }}').submit()">
                                Unban
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-cell">
                        @if($searchQuery)
                            Tidak ada toko dengan nama "{{ e($searchQuery) }}"
                        @elseif($statusFilter)
                            Tidak ada toko dengan status {{ $statusFilter }}
                        @else
                            Belum ada toko yang mendaftar
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paginatedStores->hasPages())
    <div class="pagination-wrap">
        <p class="pagination-info">
            {{ $paginatedStores->firstItem() }}–{{ $paginatedStores->lastItem() }} dari {{ $paginatedStores->total() }} toko
        </p>
        <div class="pagination-btns">
            @if($paginatedStores->onFirstPage())
                <span class="page-btn disabled">‹</span>
            @else
                <a href="{{ $paginatedStores->previousPageUrl() }}" class="page-btn">‹</a>
            @endif
            @foreach($paginatedStores->getUrlRange(max(1,$paginatedStores->currentPage()-2), min($paginatedStores->lastPage(),$paginatedStores->currentPage()+2)) as $pg => $url)
                <a href="{{ $url }}" class="page-btn {{ $pg===$paginatedStores->currentPage()?'active':'' }}">{{ $pg }}</a>
            @endforeach
            @if($paginatedStores->hasMorePages())
                <a href="{{ $paginatedStores->nextPageUrl() }}" class="page-btn">›</a>
            @else
                <span class="page-btn disabled">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

<div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
        <p class="modal-title">Tolak Pendaftaran Toko</p>
        <p class="modal-sub" id="rejectModalSub">Tuliskan alasan penolakan untuk merchant.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reason" class="modal-textarea"
                      placeholder="Contoh: Nama toko tidak sesuai ketentuan, harap gunakan nama yang lebih spesifik." required></textarea>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeReject()">Batal</button>
                <button type="submit" class="btn-modal-submit">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReject(id, name) {
    document.getElementById('rejectModalSub').textContent = 'Tuliskan alasan penolakan untuk toko "' + name + '".';
    document.getElementById('rejectForm').action = '/admin/stores/' + id + '/reject';
    document.getElementById('rejectModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeReject() {
    document.getElementById('rejectModal').classList.remove('show');
    document.body.style.overflow = '';
}
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeReject();
});
</script>

@endsection
