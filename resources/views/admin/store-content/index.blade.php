@extends('admin.layouts.sidebar')
@section('page-title', 'Konten Toko Official')
@section('content')

<style>
*{box-sizing:border-box}
.page-tabs{display:flex;gap:0;margin-bottom:24px;border-bottom:.5px solid rgba(59,46,34,.1);}
.page-tab{padding:10px 20px;font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:rgba(59,46,34,.4);cursor:pointer;border-bottom:2px solid transparent;transition:all .2s;background:none;border-left:none;border-right:none;border-top:none;font-family:'DM Sans',sans-serif;}
.page-tab.active{color:#3b2e22;border-bottom-color:#c9a96e;}
.tab-pane{display:none;}.tab-pane.active{display:block;}

.page-layout{display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:flex-start;}
@media(max-width:900px){.page-layout{grid-template-columns:1fr;}}

.card{background:white;border-radius:14px;border:.5px solid rgba(59,46,34,.08);padding:22px;margin-bottom:18px;}
.card-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(59,46,34,.4);margin-bottom:18px;padding-bottom:12px;border-bottom:.5px solid rgba(59,46,34,.06);}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(59,46,34,.45);margin-bottom:7px;}
.form-input{width:100%;padding:9px 12px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;font-size:13px;color:#3b2e22;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus{border-color:#c9a96e;}
.form-hint{font-size:11px;color:rgba(59,46,34,.35);margin-top:4px;}
.btn-submit{background:#3b2e22;color:#f5f0e8;border:none;border-radius:8px;padding:10px 22px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-submit:hover{background:#2e2318;}

.item-list{}
.item-row{display:flex;align-items:center;gap:14px;padding:13px 0;border-bottom:.5px solid rgba(59,46,34,.05);}
.item-row:last-child{border-bottom:none;}
.item-thumb{width:64px;height:40px;border-radius:6px;object-fit:cover;border:.5px solid rgba(59,46,34,.08);flex-shrink:0;background:#f0ebe0;}
.item-info{flex:1;min-width:0;}
.item-title-text{font-size:13px;font-weight:500;color:#3b2e22;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.item-meta{font-size:11px;color:rgba(59,46,34,.4);margin-top:2px;}
.action-group{display:flex;gap:5px;flex-shrink:0;}
.btn-sm{font-size:10px;letter-spacing:.07em;text-transform:uppercase;border-radius:6px;padding:5px 9px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(59,46,34,.15);background:none;color:rgba(59,46,34,.55);transition:all .2s;}
.btn-sm:hover{color:#3b2e22;border-color:rgba(59,46,34,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}

.dot-green{display:inline-block;width:6px;height:6px;border-radius:50%;background:#27ae60;margin-right:4px;}
.dot-gray{display:inline-block;width:6px;height:6px;border-radius:50%;background:#bbb;margin-right:4px;}

.section-card{background:white;border-radius:12px;border:.5px solid rgba(59,46,34,.08);margin-bottom:16px;overflow:hidden;}
.section-card-header{display:flex;align-items:center;gap:12px;padding:15px 18px;border-bottom:.5px solid rgba(59,46,34,.05);}
.section-card-name{font-size:14px;font-weight:500;color:#3b2e22;flex:1;}
.section-card-meta{font-size:11px;color:rgba(59,46,34,.4);}
.section-products-wrap{padding:14px 18px;}
.chip-wrap{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:10px;}
.prod-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:rgba(59,46,34,.04);border-radius:100px;font-size:12px;color:#3b2e22;}
.chip-remove{background:none;border:none;cursor:pointer;color:rgba(59,46,34,.3);padding:0;display:flex;align-items:center;transition:color .2s;}
.chip-remove:hover{color:#c0392b;}
.add-prod-form{display:flex;gap:7px;}
.add-prod-select{flex:1;padding:7px 11px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;font-size:12px;color:#3b2e22;background:white;font-family:'DM Sans',sans-serif;outline:none;}
.btn-add{background:#3b2e22;color:#f5f0e8;border:none;border-radius:8px;padding:7px 14px;font-size:11px;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;}

toggle-wrap{display:flex;align-items:center;gap:8px;}
input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}

.modal-overlay{display:none;position:fixed;inset:0;background:rgba(59,46,34,.4);z-index:100;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:26px;width:100%;max-width:440px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:14px;font-weight:500;color:#3b2e22;margin-bottom:18px;}
</style>

@if(session('success'))
<div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="page-tabs">
    <button class="page-tab active" onclick="switchTab('banners',this)">Banner Toko</button>
    <button class="page-tab" onclick="switchTab('sections',this)">Home Sections</button>
</div>

<div id="tab-banners" class="tab-pane active">
    <div class="page-layout">
        <div class="card">
            <p class="card-title">Semua Banner ({{ $banners->count() }})</p>
            @if($banners->isEmpty())
                <p style="font-size:13px;color:rgba(59,46,34,.35);text-align:center;padding:32px 0;">Belum ada banner.</p>
            @else
            <div class="item-list">
                @foreach($banners as $banner)
                <form id="del-b-{{$banner->id}}" action="{{route('admin.store-banners.destroy',$banner)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                <form id="tog-b-{{$banner->id}}" action="{{route('admin.store-banners.toggle',$banner)}}" method="POST" style="display:none;">@csrf</form>
                <div class="item-row">
                    <img src="{{ asset($banner->image) }}" class="item-thumb" alt="">
                    <div class="item-info">
                        <p class="item-title-text">{{ $banner->title ?? '(Tanpa Judul)' }}</p>
                        <p class="item-meta">
                            <span class="{{ $banner->is_active?'dot-green':'dot-gray' }}"></span>
                            {{ $banner->is_active?'Aktif':'Nonaktif' }} · Sort {{ $banner->sort }}
                            @if($banner->auto_slide) · Auto @endif
                        </p>
                    </div>
                    <div class="action-group">
                        <button class="btn-sm" onclick="openEditBanner({{ $banner->id }})">Edit</button>
                        <button class="btn-sm" onclick="document.getElementById('tog-b-{{$banner->id}}').submit()">
                            {{ $banner->is_active?'Nonaktif':'Aktif' }}
                        </button>
                        <button class="btn-sm btn-sm-danger"
                                onclick="if(confirm('Hapus?')) document.getElementById('del-b-{{$banner->id}}').submit()">
                            Hapus
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="card">
            <p class="card-title">Tambah Banner</p>
            <form action="{{ route('admin.store-banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Gambar *</label>
                    <input type="file" name="image" class="form-input" accept="image/*" required>
                    <p class="form-hint">Rekomendasi 1200×400px. Max 4MB.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-input" placeholder="Flash Sale">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Diskon s/d 50%">
                </div>
                <div class="form-group">
                    <label class="form-label">Link</label>
                    <input type="text" name="link" class="form-input" placeholder="/toko/taku-official">
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" class="form-input" placeholder="Lihat Produk">
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" name="auto_slide" id="autoSlide" class="toggle" checked>
                    <label for="autoSlide" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
                </div>
                <button type="submit" class="btn-submit">Tambah Banner</button>
            </form>
        </div>
    </div>
</div>

<div id="tab-sections" class="tab-pane">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <p style="font-size:13px;color:rgba(59,46,34,.45);">{{ $sections->count() }} section aktif di halaman toko</p>
        <button class="btn-submit" onclick="document.getElementById('addSectionModal').classList.add('show')">
            + Section Baru
        </button>
    </div>

    @forelse($sections as $section)
    <form id="tog-s-{{$section->id}}" action="{{route('admin.store-sections.toggle',$section)}}" method="POST" style="display:none;">@csrf</form>
    <form id="del-s-{{$section->id}}" action="{{route('admin.store-sections.destroy',$section)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>

    <div class="section-card">
        <div class="section-card-header">
            <div style="flex:1;min-width:0;">
                <p class="section-card-name">{{ $section->title }}</p>
                @if($section->subtitle)
                <p style="font-size:11px;color:rgba(59,46,34,.4);margin-top:1px;">{{ $section->subtitle }}</p>
                @endif
            </div>
            <p class="section-card-meta">
                <span class="{{ $section->is_active?'dot-green':'dot-gray' }}"></span>
                {{ $section->products->count() }} produk · {{ $section->rows }} baris
            </p>
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
        <div class="section-products-wrap">
            <p style="font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(59,46,34,.35);margin-bottom:10px;">
                Produk ({{ $section->products->count() }})
            </p>
            @if($section->products->count() > 0)
            <div class="chip-wrap">
                @foreach($section->products as $prod)
                <span class="prod-chip">
                    {{ $prod->name }}
                    <form action="{{ route('admin.store-sections.products.remove',[$section,$prod]) }}" method="POST" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="chip-remove" title="Hapus">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </form>
                </span>
                @endforeach
            </div>
            @endif
            <form action="{{ route('admin.store-sections.products.add',$section) }}" method="POST" class="add-prod-form">
                @csrf
                <select name="product_id" class="add-prod-select" required>
                    <option value="">— Pilih produk official —</option>
                    @foreach($products as $prod)
                    @if(!$section->products->contains($prod->id))
                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                    @endif
                    @endforeach
                </select>
                <button type="submit" class="btn-add">+ Tambah</button>
            </form>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:48px;background:white;border-radius:14px;border:.5px solid rgba(59,46,34,.08);color:rgba(59,46,34,.35);font-size:13px;">
        Belum ada section. Buat section pertama.
    </div>
    @endforelse
</div>

<div class="modal-overlay" id="editBannerModal">
    <div class="modal-box">
        <p class="modal-title">Edit Banner</p>
        <form id="editBannerForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Gambar Baru (kosongkan jika tidak ganti)</label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">Judul</label>
                <input type="text" name="title" id="eb-title" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" id="eb-sub" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Link</label>
                <input type="text" name="link" id="eb-link" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Teks Tombol</label>
                <input type="text" name="button_text" id="eb-btn" class="form-input">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="eb-auto" class="toggle">
                <label for="eb-auto" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:14px;">
                <button type="button" onclick="document.getElementById('editBannerModal').classList.remove('show')"
                    style="flex:1;padding:9px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">
                    Batal
                </button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="addSectionModal">
    <div class="modal-box">
        <p class="modal-title">Buat Section Baru</p>
        <form action="{{ route('admin.store-sections.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="title" class="form-input" placeholder="Produk Pilihan" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" class="form-input" placeholder="Kurasi terbaik">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Baris</label>
                <select name="rows" class="form-input">
                    <option value="1">1 baris (slider)</option>
                    <option value="2">2 baris (grid)</option>
                    <option value="3">3 baris (grid padat)</option>
                </select>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="as-auto" class="toggle">
                <label for="as-auto" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:14px;">
                <button type="button" onclick="document.getElementById('addSectionModal').classList.remove('show')"
                    style="flex:1;padding:9px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">
                    Batal
                </button>
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
            <div class="form-group">
                <label class="form-label">Jumlah Baris</label>
                <select name="rows" id="es-rows" class="form-input">
                    <option value="1">1 baris</option>
                    <option value="2">2 baris</option>
                    <option value="3">3 baris</option>
                </select>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="es-auto" class="toggle">
                <label for="es-auto" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:14px;">
                <button type="button" onclick="document.getElementById('editSectionModal').classList.remove('show')"
                    style="flex:1;padding:9px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">
                    Batal
                </button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

@php
$bannersJson = $banners->map(fn($b) => [
    'id'=>$b->id,'title'=>$b->title,'subtitle'=>$b->subtitle,
    'link'=>$b->link,'button_text'=>$b->button_text,'auto_slide'=>$b->auto_slide,
])->keyBy('id');
@endphp

<script>
function switchTab(tab, el) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.page-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+tab).classList.add('active');
    el.classList.add('active');
}

const bData = @json($bannersJson);
function openEditBanner(id) {
    const b = bData[id];
    document.getElementById('eb-title').value = b.title ?? '';
    document.getElementById('eb-sub').value   = b.subtitle ?? '';
    document.getElementById('eb-link').value  = b.link ?? '';
    document.getElementById('eb-btn').value   = b.button_text ?? '';
    document.getElementById('eb-auto').checked = b.auto_slide;
    document.getElementById('editBannerForm').action = '/admin/store-banners/' + id;
    document.getElementById('editBannerModal').classList.add('show');
}

function openEditSection(id, title, subtitle, rows, auto) {
    document.getElementById('es-title').value = title;
    document.getElementById('es-sub').value   = subtitle;
    document.getElementById('es-rows').value  = rows;
    document.getElementById('es-auto').checked = auto;
    document.getElementById('editSectionForm').action = '/admin/store-sections/' + id;
    document.getElementById('editSectionModal').classList.add('show');
}
</script>

@endsection