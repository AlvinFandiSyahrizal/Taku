@extends('merchant.layouts.sidebar')
@section('page-title', 'Tampilan Toko')
@section('content')

<style>
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
.tab-nav{display:flex;gap:0;border:.5px solid rgba(11,42,74,.12);border-radius:8px;overflow:hidden;background:white;margin-bottom:24px;}
.tab-btn{flex:1;padding:9px 16px;background:none;border:none;cursor:pointer;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-family:'DM Sans',sans-serif;color:rgba(11,42,74,.5);transition:all .2s;text-align:center;}
.tab-btn.active{background:#0b2a4a;color:#f0ebe0;}
.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);margin-bottom:16px;overflow:hidden;}
.card-header{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:.5px solid rgba(11,42,74,.06);}
.card-title{font-size:13px;font-weight:500;color:#0b2a4a;flex:1;}
.card-body{padding:20px;}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:7px;}
.form-input,.form-textarea{width:100%;padding:9px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;background:white;box-sizing:border-box;}
.form-input:focus,.form-textarea:focus{border-color:#c9a96e;}
.form-textarea{resize:vertical;min-height:70px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.btn-primary{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:9px 20px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-primary:hover{background:#0d3459;}
.btn-sm{font-size:10px;letter-spacing:.08em;text-transform:uppercase;border-radius:6px;padding:5px 10px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.6);transition:all .2s;}
.btn-sm:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}
.action-group{display:flex;gap:6px;flex-shrink:0;}
.dot-green{width:6px;height:6px;border-radius:50%;background:#27ae60;display:inline-block;margin-right:4px;}
.dot-gray{width:6px;height:6px;border-radius:50%;background:#bbb;display:inline-block;margin-right:4px;}
.banner-preview{width:100%;height:80px;object-fit:cover;border-radius:8px;border:.5px solid rgba(11,42,74,.08);}
.banner-placeholder{width:100%;height:80px;border-radius:8px;background:#f5f1e8;display:flex;align-items:center;justify-content:center;font-size:11px;color:rgba(11,42,74,.3);border:.5px solid rgba(11,42,74,.06);}
.product-chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:rgba(11,42,74,.05);border-radius:100px;font-size:12px;color:#0b2a4a;margin:3px;}
.chip-remove{background:none;border:none;cursor:pointer;color:rgba(11,42,74,.35);padding:0;line-height:1;transition:color .2s;}
.chip-remove:hover{color:#c0392b;}
.add-row{display:flex;gap:8px;margin-top:12px;}
.add-select{flex:1;padding:8px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:12px;color:#0b2a4a;background:white;font-family:'DM Sans',sans-serif;outline:none;}
.toggle-wrap{display:flex;align-items:center;gap:8px;}
input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(11,42,74,.4);z-index:100;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:28px;width:100%;max-width:440px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:15px;font-weight:500;color:#0b2a4a;margin-bottom:20px;}
.empty-card{text-align:center;padding:40px;background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);color:rgba(11,42,74,.3);font-size:13px;}
@media(max-width:640px){.form-row{grid-template-columns:1fr;}.card-header{flex-wrap:wrap;gap:8px;}}
</style>

<div class="tab-nav">
    <button class="tab-btn active" id="tab-btn-banner" onclick="switchTab('banner')">Banner Toko</button>
    <button class="tab-btn" id="tab-btn-section" onclick="switchTab('section')">Section Produk</button>
</div>

<div id="tab-banner">
    <div class="top-bar">
        <p style="font-size:13px;color:rgba(11,42,74,.45);">{{ $banners->count() }} banner</p>
        <button class="btn-primary" onclick="document.getElementById('addBannerModal').classList.add('show')">+ Tambah Banner</button>
    </div>

    @forelse($banners as $banner)
    <div class="card">
        <div class="card-header">
            @if($banner->image)
                <img src="{{ asset($banner->image) }}" class="banner-preview" style="width:120px;height:60px;object-fit:cover;border-radius:6px;flex-shrink:0;">
            @else
                <div class="banner-placeholder" style="width:120px;height:60px;flex-shrink:0;border-radius:6px;">No Image</div>
            @endif
            <div style="flex:1;min-width:0;">
                <p class="card-title">{{ $banner->title ?? '(Tanpa judul)' }}</p>
                @if($banner->subtitle)<p style="font-size:11px;color:rgba(11,42,74,.4);">{{ $banner->subtitle }}</p>@endif
                <p style="font-size:10px;color:rgba(11,42,74,.3);margin-top:3px;">
                    <span class="{{ $banner->is_active ? 'dot-green' : 'dot-gray' }}"></span>
                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                </p>
            </div>
            <div class="action-group">
                <form action="{{ route('merchant.banners.toggle', $banner) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-sm">{{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                </form>
                <form action="{{ route('merchant.banners.destroy', $banner) }}" method="POST" style="display:inline."
                      onsubmit="return confirm('Hapus banner ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sm btn-sm-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-card">Belum ada banner. Tambahkan banner untuk ditampilkan di halaman toko.</div>
    @endforelse
</div>

<div id="tab-section" style="display:none;">
    <div class="top-bar">
        <p style="font-size:13px;color:rgba(11,42,74,.45);">{{ $sections->count() }} section</p>
        <button class="btn-primary" onclick="document.getElementById('addSectionModal').classList.add('show')">+ Buat Section</button>
    </div>

    @forelse($sections as $section)
    <form id="del-sec-{{ $section->id }}" action="{{ route('merchant.store-sections.destroy', $section) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
    <form id="tog-sec-{{ $section->id }}" action="{{ route('merchant.store-sections.toggle', $section) }}" method="POST" style="display:none;">@csrf</form>

    <div class="card">
        <div class="card-header">
            <div style="flex:1;">
                <p class="card-title">{{ $section->title }}</p>
                @if($section->subtitle)<p style="font-size:11px;color:rgba(11,42,74,.4);">{{ $section->subtitle }}</p>@endif
                <p style="font-size:10px;color:rgba(11,42,74,.3);margin-top:3px;">
                    <span class="{{ $section->is_active ? 'dot-green' : 'dot-gray' }}"></span>
                    {{ $section->products->count() }} produk · {{ $section->rows }} baris
                    @if($section->auto_slide) · Auto slide @endif
                </p>
            </div>
            <div class="action-group">
                <button class="btn-sm" onclick="openEditSection({{ $section->id }},'{{ addslashes($section->title) }}','{{ addslashes($section->subtitle ?? '') }}',{{ $section->rows }},{{ $section->auto_slide ? 'true' : 'false' }})">Edit</button>
                <button class="btn-sm" onclick="document.getElementById('tog-sec-{{ $section->id }}').submit()">
                    {{ $section->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
                <button class="btn-sm btn-sm-danger" onclick="if(confirm('Hapus section?')) document.getElementById('del-sec-{{ $section->id }}').submit()">Hapus</button>
            </div>
        </div>
        <div class="card-body">
            <p style="font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:10px;">Produk ({{ $section->products->count() }})</p>
            <div>
                @foreach($section->products as $prod)
                <span class="product-chip">
                    @if($prod->image)
                        <img src="{{ asset($prod->image) }}" style="width:24px;height:24px;border-radius:4px;object-fit:cover;flex-shrink:0;">
                    @else
                        <div style="width:24px;height:24px;border-radius:4px;background:#f5f1e8;flex-shrink:0;"></div>
                    @endif
                    {{ $prod->name }}
                    <form action="{{ route('merchant.store-sections.products.remove', [$section, $prod]) }}" method="POST" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="chip-remove">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </form>
                </span>
                @endforeach
            </div>
            <form action="{{ route('merchant.store-sections.products.add', $section) }}" method="POST" class="add-row">
                @csrf
                    <select name="product_id" class="add-select" required>
                        <option value="">— Tambah produk ke section —</option>
                        @foreach($products as $prod)
                            @if(!$section->products->contains($prod->id))
                            <option value="{{ $prod->id }}">
                                {{ $prod->name }} — Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}
                                {{ $prod->is_active ? '' : '(nonaktif)' }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                <button type="submit" class="btn-primary" style="padding:8px 16px;white-space:nowrap;">+ Tambah</button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-card">Belum ada section. Buat section untuk menampilkan produk pilihan di toko.</div>
    @endforelse
</div>

<div class="modal-overlay" id="addBannerModal">
    <div class="modal-box">
        <p class="modal-title">Tambah Banner</p>
        <form action="{{ route('merchant.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Gambar Banner</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;">Rekomendasi: 1200×400px. Max 2MB.</p>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-input" placeholder="Flash Sale!">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Diskon hingga 50%">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Link (opsional)</label>
                    <input type="text" name="link" class="form-input" placeholder="/products">
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" class="form-input" placeholder="Lihat Semua">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="button" onclick="document.getElementById('addBannerModal').classList.remove('show')"
                    style="flex:1;padding:10px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">Batal</button>
                <button type="submit" class="btn-primary" style="flex:2;">Simpan Banner</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="addSectionModal">
    <div class="modal-box">
        <p class="modal-title">Buat Section Baru</p>
        <form action="{{ route('merchant.store-sections.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="title" class="form-input" placeholder="Produk Unggulan" required>
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" class="form-input" placeholder="Pilihan terbaik dari toko">
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Baris</label>
                <select name="rows" class="form-input">
                    <option value="1">1 baris (slider)</option>
                    <option value="2">2 baris (grid)</option>
                    <option value="3">3 baris (grid penuh)</option>
                </select>
            </div>
            <div class="form-group toggle-wrap">
                <input type="checkbox" name="auto_slide" id="newSecAuto" class="toggle">
                <label for="newSecAuto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="button" onclick="document.getElementById('addSectionModal').classList.remove('show')"
                    style="flex:1;padding:10px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">Batal</button>
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
                    <option value="1">1 baris (slider)</option>
                    <option value="2">2 baris (grid)</option>
                    <option value="3">3 baris (grid penuh)</option>
                </select>
            </div>
            <div class="form-group toggle-wrap">
                <input type="checkbox" name="auto_slide" id="editSecAuto" class="toggle">
                <label for="editSecAuto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="button" onclick="document.getElementById('editSectionModal').classList.remove('show')"
                    style="flex:1;padding:10px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">Batal</button>
                <button type="submit" class="btn-primary" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tab) {
    document.getElementById('tab-banner').style.display = tab === 'banner' ? 'block' : 'none';
    document.getElementById('tab-section').style.display = tab === 'section' ? 'block' : 'none';
    document.getElementById('tab-btn-banner').classList.toggle('active', tab === 'banner');
    document.getElementById('tab-btn-section').classList.toggle('active', tab === 'section');
}

function openEditSection(id, title, subtitle, rows, autoSlide) {
    document.getElementById('editSecTitle').value   = title;
    document.getElementById('editSecSub').value     = subtitle;
    document.getElementById('editSecRows').value    = rows;
    document.getElementById('editSecAuto').checked  = autoSlide;
    document.getElementById('editSectionForm').action = '/merchant/store-sections/' + id;
    document.getElementById('editSectionModal').classList.add('show');
}
</script>

@endsection
