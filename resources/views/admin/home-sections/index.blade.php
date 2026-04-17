@extends('admin.layouts.sidebar')
@section('page-title', 'Home Sections')
@section('content')

<style>
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;}
.btn-primary{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:10px 20px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-primary:hover{background:#0d3459;}

.section-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);margin-bottom:20px;overflow:hidden;}
.section-header{display:flex;align-items:center;gap:12px;padding:18px 24px;border-bottom:.5px solid rgba(11,42,74,.06);}
.section-drag{cursor:grab;color:rgba(11,42,74,.25);flex-shrink:0;}
.section-name{font-size:14px;font-weight:500;color:#0b2a4a;flex:1;}
.section-sub{font-size:11px;color:rgba(11,42,74,.4);margin-top:2px;}
.section-meta{font-size:11px;color:rgba(11,42,74,.35);}
.action-group{display:flex;gap:6px;}
.btn-sm{font-size:10px;letter-spacing:.08em;text-transform:uppercase;border-radius:6px;padding:5px 10px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.6);transition:all .2s;}
.btn-sm:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}

.section-products{padding:16px 24px;}
.section-products-label{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:12px;}
.product-chips{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;}
.product-chip{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;background:rgba(11,42,74,.05);border-radius:100px;font-size:12px;color:#0b2a4a;}
.chip-remove{background:none;border:none;cursor:pointer;color:rgba(11,42,74,.35);padding:0;display:flex;align-items:center;transition:color .2s;line-height:1;}
.chip-remove:hover{color:#c0392b;}
.add-product-form{display:flex;gap:8px;}
.add-product-select{flex:1;padding:8px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:12px;color:#0b2a4a;background:white;font-family:'DM Sans',sans-serif;outline:none;}
.add-product-select:focus{border-color:#c9a96e;}
.btn-add-product{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:8px 16px;font-size:11px;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;}

.dot-green{display:inline-block;width:6px;height:6px;border-radius:50%;background:#27ae60;margin-right:5px;}
.dot-gray{display:inline-block;width:6px;height:6px;border-radius:50%;background:#bbb;margin-right:5px;}

.modal-overlay{display:none;position:fixed;inset:0;background:rgba(11,42,74,.4);z-index:100;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:28px;width:100%;max-width:440px;}
.modal-title{font-size:15px;font-weight:500;color:#0b2a4a;margin-bottom:20px;}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.form-input{width:100%;padding:10px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;background:white;box-sizing:border-box;}
.form-input:focus{border-color:#c9a96e;}
.form-hint{font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;}
toggle-wrap{display:flex;align-items:center;gap:8px;}
input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}
</style>

@if(session('success'))
    <div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:20px;">{{ session('success') }}</div>
@endif

<div class="top-bar">
    <p style="font-size:13px;color:rgba(11,42,74,.45);">{{ $sections->count() }} section aktif di home</p>
    <button class="btn-primary" onclick="document.getElementById('addModal').classList.add('show')">
        + Buat Section Baru
    </button>
</div>

@forelse($sections as $section)
<form id="toggle-sec-{{$section->id}}" action="{{route('admin.home-sections.toggle',$section)}}" method="POST" style="display:none;">@csrf</form>
<form id="del-sec-{{$section->id}}" action="{{route('admin.home-sections.destroy',$section)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<div class="section-card">
    <div class="section-header">
        <div class="section-drag">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="18" x2="16" y2="18"/></svg>
        </div>
        <div>
            <p class="section-name">{{ $section->title }}</p>
            @if($section->subtitle)
            <p class="section-sub">{{ $section->subtitle }}</p>
            @endif
        </div>
        <p class="section-meta">
            <span class="{{ $section->is_active ? 'dot-green' : 'dot-gray' }}"></span>
            {{ $section->products->count() }} produk
            · {{ $section->rows }} baris
            @if($section->auto_slide) · Auto @endif
        </p>
        <div class="action-group">
            <button class="btn-sm" onclick="openEditSection({{ $section->id }}, '{{ addslashes($section->title) }}', '{{ addslashes($section->subtitle ?? '') }}', {{ $section->rows }}, {{ $section->auto_slide ? 'true' : 'false' }})">Edit</button>
            <button class="btn-sm" onclick="document.getElementById('toggle-sec-{{$section->id}}').submit()">
                {{ $section->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
            <button class="btn-sm btn-sm-danger"
                    onclick="if(confirm('Hapus section ini?')) document.getElementById('del-sec-{{$section->id}}').submit()">
                Hapus
            </button>
        </div>
    </div>

    <div class="section-products">
        <p class="section-products-label">Produk di section ini ({{ $section->products->count() }})</p>

        @if($section->products->count() > 0)
        <div class="product-chips">
            @foreach($section->products as $prod)
            <span class="product-chip">
                {{ $prod->name }}
                @if($prod->store) <span style="opacity:.5">· {{ $prod->store->name }}</span> @endif
                <form action="{{ route('admin.home-sections.products.remove', [$section, $prod]) }}" method="POST" style="display:contents;">
                    @csrf @method('DELETE')
                    <button type="submit" class="chip-remove" title="Hapus dari section">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </form>
            </span>
            @endforeach
        </div>
        @else
        <p style="font-size:12px;color:rgba(11,42,74,.35);margin-bottom:12px;">Belum ada produk. Tambahkan produk dari daftar di bawah.</p>
        @endif

        <form action="{{ route('admin.home-sections.products.add', $section) }}" method="POST" class="add-product-form">
            @csrf
            <select name="product_id" class="add-product-select" required>
                <option value="">— Pilih produk —</option>
                @foreach($products as $prod)
                @if(!$section->products->contains($prod->id))
                <option value="{{ $prod->id }}">{{ $prod->name }} {{ $prod->store ? '('.$prod->store->name.')' : '(Official)' }}</option>
                @endif
                @endforeach
            </select>
            <button type="submit" class="btn-add-product">+ Tambah</button>
        </form>
    </div>
</div>
@empty
<div style="text-align:center;padding:60px;background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);color:rgba(11,42,74,.35);font-size:13px;">
    Belum ada section. Buat section pertama kamu.
</div>
@endforelse

<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <p class="modal-title">Buat Section Baru</p>
        <form action="{{ route('admin.home-sections.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul Section *</label>
                <input type="text" name="title" class="form-input" placeholder="Produk Pilihan" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul (opsional)</label>
                <input type="text" name="subtitle" class="form-input" placeholder="Kurasi terbaik dari kami">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Baris</label>
                <select name="rows" class="form-input">
                    <option value="1">1 baris</option>
                    <option value="2">2 baris</option>
                    <option value="3">3 baris</option>
                </select>
                <p class="form-hint">1 baris = slider horizontal. 2-3 baris = grid.</p>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="addAutoSlide" class="toggle">
                <label for="addAutoSlide" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="button" onclick="document.getElementById('addModal').classList.remove('show')"
                    style="flex:1;padding:10px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">
                    Batal
                </button>
                <button type="submit" class="btn-primary" style="flex:2;">Buat Section</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editSectionModal">
    <div class="modal-box">
        <p class="modal-title">Edit Section</p>
        <form id="editSectionForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul</label>
                <input type="text" name="title" id="editSecTitle" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" id="editSecSub" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Baris</label>
                <select name="rows" id="editSecRows" class="form-input">
                    <option value="1">1 baris</option>
                    <option value="2">2 baris</option>
                    <option value="3">3 baris</option>
                </select>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="editSecAuto" class="toggle">
                <label for="editSecAuto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="button" onclick="document.getElementById('editSectionModal').classList.remove('show')"
                    style="flex:1;padding:10px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">
                    Batal
                </button>
                <button type="submit" class="btn-primary" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditSection(id, title, subtitle, rows, autoSlide) {
    document.getElementById('editSecTitle').value = title;
    document.getElementById('editSecSub').value   = subtitle;
    document.getElementById('editSecRows').value  = rows;
    document.getElementById('editSecAuto').checked = autoSlide;
    document.getElementById('editSectionForm').action = '/admin/home-sections/' + id;
    document.getElementById('editSectionModal').classList.add('show');
}
</script>

@endsection
