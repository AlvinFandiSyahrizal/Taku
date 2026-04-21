@extends('merchant.layouts.sidebar')
@section('page-title', 'Tampilan Toko')
@section('content')

<style>
*{box-sizing:border-box}
.tab-nav{display:flex;gap:0;border:.5px solid rgba(11,42,74,.12);border-radius:10px;overflow:hidden;background:white;margin-bottom:24px;}
.tab-btn{flex:1;padding:10px 16px;background:none;border:none;cursor:pointer;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-family:'DM Sans',sans-serif;color:rgba(11,42,74,.5);transition:all .2s;text-align:center;}
.tab-btn.active{background:#0b2a4a;color:#f0ebe0;}

.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);margin-bottom:14px;overflow:hidden;}
.card-header{display:flex;align-items:center;gap:12px;padding:14px 18px;border-bottom:.5px solid rgba(11,42,74,.05);background:#f8fbff;}
.card-title{font-size:13px;font-weight:500;color:#0b2a4a;flex:1;}
.card-body{padding:16px 18px;}

.form-group{margin-bottom:13px;}
.form-label{display:block;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:7px;}
.form-input,.form-select,.form-textarea{width:100%;padding:9px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;background:white;box-sizing:border-box;transition:border-color .2s;}
.form-input:focus,.form-select:focus{border-color:#c9a96e;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.form-hint{font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;}

.btn-primary{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:9px 20px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-primary:hover{background:#0d3459;}
.btn-sm{font-size:10px;letter-spacing:.07em;text-transform:uppercase;border-radius:6px;padding:5px 9px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.55);transition:all .2s;}
.btn-sm:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}
.action-group{display:flex;gap:5px;flex-shrink:0;}

.dot-green{width:6px;height:6px;border-radius:50%;background:#27ae60;display:inline-block;margin-right:4px;}
.dot-gray{width:6px;height:6px;border-radius:50%;background:#bbb;display:inline-block;margin-right:4px;}

.pos-badge{display:inline-block;padding:2px 8px;border-radius:100px;font-size:9px;letter-spacing:.07em;text-transform:uppercase;font-family:'DM Sans',sans-serif;font-weight:500;}
.pos-top{background:#e8f0e4;color:#3d6b2e;}
.pos-mid{background:#e8eaf0;color:#2e3d6b;}
.pos-bot{background:#f0e8e8;color:#6b2e2e;}

.banner-item{display:flex;align-items:center;gap:10px;padding:11px 12px;border-radius:9px;border:.5px solid rgba(11,42,74,.07);background:#f8fbff;margin-bottom:7px;cursor:grab;transition:border-color .2s;}
.banner-item:active{cursor:grabbing;}
.banner-item.dragging-over{border-color:#c9a96e;background:#fdf6ea;}
.drag-handle{color:rgba(11,42,74,.25);flex-shrink:0;}
.banner-preview{width:64px;height:40px;border-radius:6px;object-fit:cover;border:.5px solid rgba(11,42,74,.06);flex-shrink:0;background:#eef2f7;}
.banner-info{flex:1;min-width:0;}
.banner-title{font-size:12px;font-weight:500;color:#0b2a4a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.banner-meta{font-size:10px;color:rgba(11,42,74,.4);margin-top:2px;display:flex;gap:6px;flex-wrap:wrap;align-items:center;}

.prod-list{display:flex;flex-direction:column;gap:5px;margin-bottom:10px;}
.prod-item{display:flex;align-items:center;gap:9px;padding:8px 10px;background:#f8fbff;border-radius:8px;border:.5px solid rgba(11,42,74,.06);}
.prod-item-img{width:40px;height:40px;border-radius:6px;object-fit:cover;background:#eef2f7;border:.5px solid rgba(11,42,74,.06);flex-shrink:0;}
.prod-item-info{flex:1;min-width:0;}
.prod-item-name{font-size:12px;font-weight:500;color:#0b2a4a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.prod-item-meta{font-size:10px;color:rgba(11,42,74,.45);margin-top:1px;display:flex;gap:7px;flex-wrap:wrap;}
.chip-remove{background:none;border:none;cursor:pointer;color:rgba(11,42,74,.3);padding:4px;border-radius:4px;display:flex;align-items:center;flex-shrink:0;transition:all .2s;}
.chip-remove:hover{color:#c0392b;background:rgba(192,57,43,.06);}
.add-row{display:flex;gap:7px;margin-top:6px;}
.add-select{flex:1;padding:8px 11px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:12px;color:#0b2a4a;background:white;font-family:'DM Sans',sans-serif;outline:none;}
.btn-add{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:8px 14px;font-size:11px;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;}
.btn-add:hover{background:#0d3459;}

input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}

.empty-card{text-align:center;padding:40px;background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);color:rgba(11,42,74,.3);font-size:13px;}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(11,42,74,.4);z-index:200;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:26px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:14px;font-weight:500;color:#0b2a4a;margin-bottom:18px;}
.modal-footer{display:flex;gap:10px;margin-top:16px;}
.btn-cancel{flex:1;padding:9px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;}

@media(max-width:640px){.form-row{grid-template-columns:1fr;}.card-header{flex-wrap:wrap;gap:8px;}}
</style>

@if(session('success'))
<div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="tab-nav">
    <button class="tab-btn active" id="tab-btn-banner" onclick="switchTab('banner')">Banner Toko</button>
    <button class="tab-btn" id="tab-btn-section" onclick="switchTab('section')">Section Produk</button>
</div>

<div id="tab-banner">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
        <p style="font-size:13px;color:rgba(11,42,74,.45);">{{ $banners->count() }} banner · Drag untuk ubah urutan</p>
        <button class="btn-primary" onclick="document.getElementById('addBannerModal').classList.add('show')">+ Tambah Banner</button>
    </div>

    @php
        $grouped   = $banners->groupBy('position');
        $posLabels = ['top'=>'Atas (Hero)','after_sections'=>'Tengah (Setelah Section)','bottom'=>'Bawah (Setelah Katalog)'];
        $posBadge  = ['top'=>'pos-top','after_sections'=>'pos-mid','bottom'=>'pos-bot'];
    @endphp

    @if($banners->isEmpty())
    <div class="empty-card">Belum ada banner. Klik "Tambah Banner" untuk memulai.</div>
    @else
    @foreach(['top','after_sections','bottom'] as $pos)
    @if(isset($grouped[$pos]) && $grouped[$pos]->count() > 0)
    <div style="margin-bottom:18px;">
        <p style="font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:rgba(11,42,74,.35);margin-bottom:8px;display:flex;align-items:center;gap:8px;">
            <span class="pos-badge {{ $posBadge[$pos] }}">{{ $posLabels[$pos] }}</span>
            <span style="flex:1;height:.5px;background:rgba(11,42,74,.06);display:block;"></span>
        </p>
        <div class="banner-sortable-merchant" data-pos="{{ $pos }}" id="merch-sortable-{{ $pos }}">
        @foreach($grouped[$pos] as $banner)
        <div class="banner-item" draggable="true" data-id="{{ $banner->id }}">
            <form id="del-b-{{$banner->id}}" action="{{ route('merchant.banners.destroy', $banner) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
            <form id="tog-b-{{$banner->id}}" action="{{ route('merchant.banners.toggle', $banner) }}" method="POST" style="display:none;">@csrf</form>
            <span class="drag-handle">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none"><circle cx="9" cy="5" r="1.2" fill="currentColor"/><circle cx="15" cy="5" r="1.2" fill="currentColor"/><circle cx="9" cy="12" r="1.2" fill="currentColor"/><circle cx="15" cy="12" r="1.2" fill="currentColor"/><circle cx="9" cy="19" r="1.2" fill="currentColor"/><circle cx="15" cy="19" r="1.2" fill="currentColor"/></svg>
            </span>
            @if($banner->image)
                <img src="{{ asset($banner->image) }}" class="banner-preview" alt="">
            @else
                <div class="banner-preview" style="display:flex;align-items:center;justify-content:center;font-size:9px;color:rgba(11,42,74,.25);">No Img</div>
            @endif
            <div class="banner-info">
                <p class="banner-title">{{ $banner->title ?? '(Tanpa judul)' }}</p>
                <div class="banner-meta">
                    <span><span class="{{ $banner->is_active ? 'dot-green' : 'dot-gray' }}"></span>{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    @if($banner->subtitle)<span>{{ Str::limit($banner->subtitle,28) }}</span>@endif
                </div>
            </div>
            <div class="action-group">
                <button class="btn-sm" onclick="openEditBanner({{ $banner->id }})">Edit</button>
                <button class="btn-sm" onclick="document.getElementById('tog-b-{{$banner->id}}').submit()">
                    {{ $banner->is_active ? 'Off' : 'On' }}
                </button>
                <button class="btn-sm btn-sm-danger"
                        onclick="if(confirm('Hapus banner ini?')) document.getElementById('del-b-{{$banner->id}}').submit()">
                    Hapus
                </button>
            </div>
        </div>
        @endforeach
        </div>
    </div>
    @endif
    @endforeach
    @endif
</div>

<div id="tab-section" style="display:none;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
        <p style="font-size:13px;color:rgba(11,42,74,.45);">{{ $sections->count() }} section</p>
        <button class="btn-primary" onclick="document.getElementById('addSectionModal').classList.add('show')">+ Buat Section</button>
    </div>

    @forelse($sections as $section)
    <form id="del-sec-{{ $section->id }}" action="{{ route('merchant.store-sections.destroy', $section) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
    <form id="tog-sec-{{ $section->id }}" action="{{ route('merchant.store-sections.toggle', $section) }}" method="POST" style="display:none;">@csrf</form>

    <div class="card">
        <div class="card-header">
            <div style="flex:1;min-width:0;">
                <p class="card-title">{{ $section->title }}</p>
                @if($section->subtitle)<p style="font-size:11px;color:rgba(11,42,74,.4);margin-top:2px;">{{ $section->subtitle }}</p>@endif
                <p style="font-size:10px;color:rgba(11,42,74,.3);margin-top:3px;">
                    <span class="{{ $section->is_active ? 'dot-green' : 'dot-gray' }}"></span>
                    {{ $section->products->count() }} produk · {{ $section->rows }} baris
                    @if($section->auto_slide) · Auto slide @endif
                </p>
            </div>
            <div class="action-group">
                <button class="btn-sm" onclick="openEditSection({{ $section->id }},'{{ addslashes($section->title) }}','{{ addslashes($section->subtitle ?? '') }}',{{ $section->rows }},{{ $section->auto_slide ? 'true' : 'false' }})">Edit</button>
                <button class="btn-sm" onclick="document.getElementById('tog-sec-{{ $section->id }}').submit()">{{ $section->is_active ? 'Off' : 'On' }}</button>
                <button class="btn-sm btn-sm-danger" onclick="if(confirm('Hapus section?')) document.getElementById('del-sec-{{ $section->id }}').submit()">Hapus</button>
            </div>
        </div>
        <div class="card-body">
            <p style="font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:10px;">Produk ({{ $section->products->count() }})</p>
            @if($section->products->count() > 0)
            <div class="prod-list">
                @foreach($section->products as $prod)
                <div class="prod-item">
                    <img src="{{ asset($prod->image ?? 'images/placeholder.jpg') }}"
                         class="prod-item-img" alt="{{ $prod->name }}"
                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="prod-item-info">
                        <p class="prod-item-name">{{ $prod->name }}</p>
                        <div class="prod-item-meta">
                            @if($prod->category)<span>{{ $prod->category->name }}</span>@endif
                            <span style="color:#0b7a43;font-weight:500;">Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}</span>
                            @if($prod->hasDiscount())
                                <span style="text-decoration:line-through;color:rgba(11,42,74,.3);">Rp {{ number_format($prod->price,0,',','.') }}</span>
                                <span style="background:#fee;color:#c0392b;padding:1px 5px;border-radius:4px;font-size:9px;">-{{ $prod->discount_percent }}%</span>
                            @endif
                            @if(isset($prod->stock))<span>Stok: {{ $prod->stock ?? '–' }}</span>@endif
                            @if(!$prod->is_active)<span style="color:#c0392b;">(Nonaktif)</span>@endif
                        </div>
                    </div>
                    <form action="{{ route('merchant.store-sections.products.remove', [$section, $prod]) }}" method="POST" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="chip-remove" onclick="return confirm('Hapus produk dari section?')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
            <form action="{{ route('merchant.store-sections.products.add', $section) }}" method="POST" class="add-row">
                @csrf
                <select name="product_id" class="add-select" required>
                    <option value="">— Tambah produk ke section —</option>
                    @foreach($products as $prod)
                    @if(!$section->products->contains($prod->id))
                    <option value="{{ $prod->id }}">
                        {{ $prod->name }} · Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}
                        @if(isset($prod->stock)) · Stok: {{ $prod->stock }}@endif
                        @if(!$prod->is_active) [Nonaktif]@endif
                    </option>
                    @endif
                    @endforeach
                </select>
                <button type="submit" class="btn-add">+ Tambah</button>
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
                <label class="form-label">Gambar Banner *</label>
                <input type="file" name="image" class="form-input" accept="image/*" required
                       onchange="previewImg(this,'addBannerPreview')">
                <p class="form-hint">Rekomendasi 1200×400px. Max 2MB.</p>
                <img id="addBannerPreview" style="display:none;width:100%;max-height:90px;object-fit:cover;border-radius:7px;margin-top:8px;border:.5px solid rgba(11,42,74,.08);">
            </div>
            <div class="form-group">
                <label class="form-label">Posisi Banner</label>
                <select name="position" class="form-select">
                    <option value="top">Atas — Langsung setelah info toko</option>
                    <option value="after_sections">Tengah — Setelah section produk</option>
                    <option value="bottom">Bawah — Setelah katalog produk</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-input" placeholder="Flash Sale!">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Diskon s/d 50%">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Link</label>
                    <input type="text" name="link" class="form-input" placeholder="/products">
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" class="form-input" placeholder="Lihat Semua">
                </div>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="addAutoSlide" class="toggle" checked>
                <label for="addAutoSlide" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('addBannerModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-primary" style="flex:2;">Simpan Banner</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editBannerModal">
    <div class="modal-box">
        <p class="modal-title">Edit Banner</p>
        <form id="editBannerForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div style="margin-bottom:14px;">
                <p class="form-label">Gambar Saat Ini</p>
                <img id="eb-current" style="width:100%;max-height:100px;object-fit:cover;border-radius:8px;border:.5px solid rgba(11,42,74,.08);" src="" alt="">
            </div>

            <div class="form-group">
                <label class="form-label">Ganti Gambar <span style="font-weight:400;text-transform:none;">(kosongkan jika tidak diganti)</span></label>
                <input type="file" name="image" class="form-input" accept="image/*"
                       onchange="previewImg(this,'eb-new')">
                <img id="eb-new" style="display:none;width:100%;max-height:80px;object-fit:cover;border-radius:7px;margin-top:7px;border:.5px solid rgba(11,42,74,.08);">
            </div>

            <div class="form-group">
                <label class="form-label">Posisi Banner</label>
                <select name="position" id="eb-pos" class="form-select">
                    <option value="top">Atas — Langsung setelah info toko</option>
                    <option value="after_sections">Tengah — Setelah section produk</option>
                    <option value="bottom">Bawah — Setelah katalog produk</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" id="eb-title" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" id="eb-sub" class="form-input">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Link</label>
                    <input type="text" name="link" id="eb-link" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" id="eb-btn" class="form-input">
                </div>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="eb-auto" class="toggle">
                <label for="eb-auto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('editBannerModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-primary" style="flex:2;">Simpan Perubahan</button>
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
                <input type="text" name="subtitle" class="form-input" placeholder="Pilihan terbaik">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jumlah Baris</label>
                    <select name="rows" class="form-select">
                        <option value="1">1 baris (slider)</option>
                        <option value="2">2 baris (grid)</option>
                        <option value="3">3 baris (penuh)</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="auto_slide" id="newSecAuto" class="toggle">
                        <label for="newSecAuto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('addSectionModal').classList.remove('show')">Batal</button>
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
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jumlah Baris</label>
                    <select name="rows" id="editSecRows" class="form-select">
                        <option value="1">1 baris (slider)</option>
                        <option value="2">2 baris (grid)</option>
                        <option value="3">3 baris (penuh)</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="auto_slide" id="editSecAuto" class="toggle">
                        <label for="editSecAuto" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('editSectionModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-primary" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

@php
$bannersJson = $banners->map(fn($b) => [
    'id'          => $b->id,
    'title'       => $b->title,
    'subtitle'    => $b->subtitle,
    'link'        => $b->link,
    'button_text' => $b->button_text,
    'auto_slide'  => $b->auto_slide,
    'position'    => $b->position ?? 'top',
    'image'       => $b->image ? asset($b->image) : null,
])->keyBy('id');
@endphp

<script>
function switchTab(tab) {
    document.getElementById('tab-banner').style.display  = tab === 'banner'  ? 'block' : 'none';
    document.getElementById('tab-section').style.display = tab === 'section' ? 'block' : 'none';
    document.getElementById('tab-btn-banner').classList.toggle('active',  tab === 'banner');
    document.getElementById('tab-btn-section').classList.toggle('active', tab === 'section');
}

function previewImg(input, id) {
    const el = document.getElementById(id);
    if (!el || !input.files || !input.files[0]) return;
    const r = new FileReader();
    r.onload = e => { el.src = e.target.result; el.style.display = 'block'; };
    r.readAsDataURL(input.files[0]);
}

const bData = @json($bannersJson);
function openEditBanner(id) {
    const b = bData[id];
    document.getElementById('eb-title').value  = b.title       ?? '';
    document.getElementById('eb-sub').value    = b.subtitle    ?? '';
    document.getElementById('eb-link').value   = b.link        ?? '';
    document.getElementById('eb-btn').value    = b.button_text ?? '';
    document.getElementById('eb-auto').checked = !!b.auto_slide;
    document.getElementById('eb-pos').value    = b.position    ?? 'top';
    const curr = document.getElementById('eb-current');
    if (b.image) { curr.src = b.image; curr.style.display = 'block'; }
    else curr.style.display = 'none';
    const newPrev = document.getElementById('eb-new');
    newPrev.src = ''; newPrev.style.display = 'none';
    document.getElementById('editBannerForm').action = '/merchant/banners/' + id;
    document.getElementById('editBannerModal').classList.add('show');
}

function openEditSection(id, title, subtitle, rows, autoSlide) {
    document.getElementById('editSecTitle').value  = title;
    document.getElementById('editSecSub').value    = subtitle;
    document.getElementById('editSecRows').value   = rows;
    document.getElementById('editSecAuto').checked = autoSlide;
    document.getElementById('editSectionForm').action = '/merchant/store-sections/' + id;
    document.getElementById('editSectionModal').classList.add('show');
}

(function(){
    let dragSrc = null;
    document.querySelectorAll('.banner-sortable-merchant').forEach(container => {
        container.addEventListener('dragstart', e => {
            const item = e.target.closest('.banner-item');
            if (!item) return;
            dragSrc = item;
            setTimeout(() => item.style.opacity = '.35', 0);
        });
        container.addEventListener('dragend', e => {
            const item = e.target.closest('.banner-item');
            if (item) item.style.opacity = '1';
            container.querySelectorAll('.banner-item').forEach(i => i.classList.remove('dragging-over'));
            const ids = [...container.querySelectorAll('.banner-item')].map(i => i.dataset.id);
            fetch('/merchant/banners/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''
                },
                body: JSON.stringify({ ids, position: container.dataset.pos })
            });
        });
        container.addEventListener('dragover', e => {
            e.preventDefault();
            const over = e.target.closest('.banner-item');
            if (over && over !== dragSrc) {
                container.querySelectorAll('.banner-item').forEach(i => i.classList.remove('dragging-over'));
                over.classList.add('dragging-over');
                const after = (e.clientY - over.getBoundingClientRect().top) > over.getBoundingClientRect().height / 2;
                container.insertBefore(dragSrc, after ? over.nextSibling : over);
            }
        });
    });
})();
</script>

@endsection
