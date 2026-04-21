@extends('admin.layouts.sidebar')
@section('page-title', 'Home Sections')
@section('content')

<style>
*{box-sizing:border-box}
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:22px;margin-bottom:18px;}
.btn-submit{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:10px 22px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-submit:hover{background:#0d3459;}
.btn-sm{font-size:10px;letter-spacing:.07em;text-transform:uppercase;border-radius:6px;padding:5px 9px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.55);transition:all .2s;}
.btn-sm:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}

.section-card{background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);margin-bottom:16px;overflow:hidden;}
.section-card-header{display:flex;align-items:center;gap:12px;padding:15px 18px;border-bottom:.5px solid rgba(11,42,74,.05);background:#f8fbff;}
.section-card-name{font-size:14px;font-weight:500;color:#0b2a4a;flex:1;}
.action-group{display:flex;gap:5px;flex-shrink:0;}
.dot-green{display:inline-block;width:6px;height:6px;border-radius:50%;background:#27ae60;margin-right:4px;}
.dot-gray{display:inline-block;width:6px;height:6px;border-radius:50%;background:#bbb;margin-right:4px;}

.prod-list{display:flex;flex-direction:column;gap:6px;margin-bottom:10px;}
.prod-item-card{display:flex;align-items:center;gap:10px;padding:9px 12px;background:#f8fbff;border-radius:8px;border:.5px solid rgba(11,42,74,.06);}
.prod-item-img{width:44px;height:44px;border-radius:7px;object-fit:cover;background:#eef2f7;border:.5px solid rgba(11,42,74,.06);flex-shrink:0;}
.prod-item-info{flex:1;min-width:0;}
.prod-item-name{font-size:12px;font-weight:500;color:#0b2a4a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.prod-item-meta{font-size:10px;color:rgba(11,42,74,.45);margin-top:2px;display:flex;gap:8px;flex-wrap:wrap;}
.chip-remove{background:none;border:none;cursor:pointer;color:rgba(11,42,74,.3);padding:5px;border-radius:4px;display:flex;align-items:center;transition:all .2s;}
.chip-remove:hover{color:#c0392b;background:rgba(192,57,43,.06);}

.prod-add-wrap{display:flex;gap:7px;}
.add-prod-select{flex:1;padding:8px 11px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:12px;color:#0b2a4a;background:white;font-family:'DM Sans',sans-serif;outline:none;}
.btn-add{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:8px 14px;font-size:11px;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;}
.btn-add:hover{background:#0d3459;}

input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(11,42,74,.4);z-index:200;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:26px;width:100%;max-width:460px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:14px;font-weight:500;color:#0b2a4a;margin-bottom:18px;}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:7px;}
.form-input,.form-select{width:100%;padding:9px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;background:white;box-sizing:border-box;}
.form-input:focus,.form-select:focus{border-color:#c9a96e;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.modal-footer{display:flex;gap:10px;margin-top:16px;}
.btn-cancel{flex:1;padding:9px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;}
</style>

@if(session('success'))
<div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="top-bar">
    <p style="font-size:13px;color:rgba(11,42,74,.45);">{{ $sections->count() }} section aktif di beranda</p>
    <button class="btn-submit" onclick="document.getElementById('addSectionModal').classList.add('show')">+ Section Baru</button>
</div>

@forelse($sections as $section)
<form id="tog-s-{{$section->id}}" action="{{route('admin.home-sections.toggle',$section)}}" method="POST" style="display:none;">@csrf</form>
<form id="del-s-{{$section->id}}" action="{{route('admin.home-sections.destroy',$section)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<div class="section-card">
    <div class="section-card-header">
        <div style="flex:1;min-width:0;">
            <p class="section-card-name">{{ $section->title }}</p>
            @if($section->subtitle)
            <p style="font-size:11px;color:rgba(11,42,74,.4);margin-top:1px;">{{ $section->subtitle }}</p>
            @endif
            <p style="font-size:10px;color:rgba(11,42,74,.35);margin-top:4px;">
                <span class="{{ $section->is_active?'dot-green':'dot-gray' }}"></span>
                {{ $section->is_active?'Aktif':'Nonaktif' }} ·
                {{ $section->products->count() }} produk · {{ $section->rows }} baris
                @if($section->auto_slide) · Auto slide @endif
            </p>
        </div>
        <div class="action-group">
            <button class="btn-sm" onclick="openEditSection({{ $section->id }},'{{ addslashes($section->title) }}','{{ addslashes($section->subtitle??'') }}',{{ $section->rows }},{{ $section->auto_slide?'true':'false' }})">Edit</button>
            <button class="btn-sm" onclick="document.getElementById('tog-s-{{$section->id}}').submit()">
                {{ $section->is_active?'Nonaktif':'Aktif' }}
            </button>
            <button class="btn-sm btn-sm-danger"
                    onclick="if(confirm('Hapus section ini?')) document.getElementById('del-s-{{$section->id}}').submit()">
                Hapus
            </button>
        </div>
    </div>

    <div style="padding:14px 18px;">
        <p style="font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.35);margin-bottom:10px;">
            Produk ({{ $section->products->count() }})
        </p>

        @if($section->products->count() > 0)
        <div class="prod-list">
            @foreach($section->products as $prod)
            <div class="prod-item-card">
                <img src="{{ asset($prod->image ?? 'images/placeholder.jpg') }}"
                     class="prod-item-img" alt="{{ $prod->name }}"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="prod-item-info">
                    <p class="prod-item-name">{{ $prod->name }}</p>
                    <div class="prod-item-meta">
                        @if($prod->category)<span>{{ $prod->category->name }}</span>@endif
                        <span style="color:#0b7a43;font-weight:500;">Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}</span>
                        @if($prod->hasDiscount())
                            <span style="color:#c0392b;text-decoration:line-through;">Rp {{ number_format($prod->price,0,',','.') }}</span>
                            <span style="background:#fee;color:#c0392b;padding:1px 5px;border-radius:4px;">-{{ $prod->discount_percent }}%</span>
                        @endif
                        @if(isset($prod->stock))<span>Stok: {{ $prod->stock ?? '–' }}</span>@endif
                        @if(!$prod->is_active)<span style="color:#c0392b;">(Nonaktif)</span>@endif
                    </div>
                </div>
                <form action="{{ route('admin.home-sections.products.remove',[$section,$prod]) }}" method="POST" style="display:contents;">
                    @csrf @method('DELETE')
                    <button type="submit" class="chip-remove" title="Hapus dari section"
                            onclick="return confirm('Hapus produk dari section?')">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.home-sections.products.add',$section) }}" method="POST" class="prod-add-wrap">
            @csrf
            <select name="product_id" class="add-prod-select" required>
                <option value="">— Pilih produk untuk ditambahkan —</option>
                @foreach($products as $prod)
                @if(!$section->products->contains($prod->id))
                <option value="{{ $prod->id }}">
                    {{ $prod->name }}
                    · Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}
                    @if(isset($prod->stock)) · Stok: {{ $prod->stock }} @endif
                    @if(!$prod->is_active) [Nonaktif] @endif
                </option>
                @endif
                @endforeach
            </select>
            <button type="submit" class="btn-add">+ Tambah</button>
        </form>
    </div>
</div>
@empty
<div style="text-align:center;padding:60px;background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);color:rgba(11,42,74,.35);font-size:13px;">
    Belum ada section. Buat section pertama untuk menampilkan produk pilihan di beranda.
</div>
@endforelse

<div class="modal-overlay" id="addSectionModal">
    <div class="modal-box">
        <p class="modal-title">Buat Section Baru</p>
        <form action="{{ route('admin.home-sections.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="title" class="form-input" placeholder="Produk Pilihan" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" class="form-input" placeholder="Kurasi terbaik dari Taku">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jumlah Baris</label>
                    <select name="rows" class="form-select">
                        <option value="1">1 baris (slider)</option>
                        <option value="2">2 baris (grid)</option>
                        <option value="3">3 baris (padat)</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="auto_slide" id="as-auto" class="toggle">
                        <label for="as-auto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('addSectionModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Buat Section</button>
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
                <input type="text" name="title" id="es-title" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" id="es-sub" class="form-input">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jumlah Baris</label>
                    <select name="rows" id="es-rows" class="form-select">
                        <option value="1">1 baris (slider)</option>
                        <option value="2">2 baris (grid)</option>
                        <option value="3">3 baris (padat)</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="auto_slide" id="es-auto" class="toggle">
                        <label for="es-auto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('editSectionModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditSection(id, title, subtitle, rows, auto) {
    document.getElementById('es-title').value  = title;
    document.getElementById('es-sub').value    = subtitle;
    document.getElementById('es-rows').value   = rows;
    document.getElementById('es-auto').checked = auto;
    document.getElementById('editSectionForm').action = '/admin/home-sections/' + id;
    document.getElementById('editSectionModal').classList.add('show');
}
</script>

@endsection