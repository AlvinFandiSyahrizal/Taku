@extends('admin.layouts.sidebar')
@section('page-title', 'Konten Toko Official')
@section('content')

<style>
*{box-sizing:border-box}
.page-tabs{display:flex;gap:0;margin-bottom:24px;border-bottom:.5px solid rgba(59,46,34,.1);}
.page-tab{padding:10px 22px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(59,46,34,.4);cursor:pointer;border-bottom:2px solid transparent;transition:all .2s;background:none;border-left:none;border-right:none;border-top:none;font-family:'DM Sans',sans-serif;}
.page-tab.active{color:#3b2e22;border-bottom-color:#c9a96e;}
.tab-pane{display:none;}.tab-pane.active{display:block;}

.page-layout{display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:flex-start;}
@media(max-width:960px){.page-layout{grid-template-columns:1fr;}}

.card{background:white;border-radius:14px;border:.5px solid rgba(59,46,34,.08);padding:22px;margin-bottom:18px;}
.card-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(59,46,34,.4);margin-bottom:18px;padding-bottom:12px;border-bottom:.5px solid rgba(59,46,34,.06);}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(59,46,34,.45);margin-bottom:7px;}
.form-input,.form-select{width:100%;padding:9px 12px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;font-size:13px;color:#3b2e22;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus,.form-select:focus{border-color:#c9a96e;}
.form-hint{font-size:11px;color:rgba(59,46,34,.35);margin-top:4px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}

.btn-submit{background:#3b2e22;color:#f5f0e8;border:none;border-radius:8px;padding:10px 22px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-submit:hover{background:#2e2318;}
.btn-sm{font-size:10px;letter-spacing:.07em;text-transform:uppercase;border-radius:6px;padding:5px 9px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(59,46,34,.15);background:none;color:rgba(59,46,34,.55);transition:all .2s;}
.btn-sm:hover{color:#3b2e22;border-color:rgba(59,46,34,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}

.pos-badge{display:inline-block;padding:3px 9px;border-radius:100px;font-size:9px;letter-spacing:.08em;text-transform:uppercase;font-family:'DM Sans',sans-serif;font-weight:500;}
.pos-top{background:#e8f0e4;color:#3d6b2e;}
.pos-mid{background:#e8eaf0;color:#2e3d6b;}
.pos-bot{background:#f0e8e8;color:#6b2e2e;}

.banner-group{margin-bottom:22px;}
.banner-group-label{font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:rgba(59,46,34,.4);margin-bottom:10px;display:flex;align-items:center;gap:8px;}
.banner-group-label::after{content:'';flex:1;height:.5px;background:rgba(59,46,34,.08);}

.banner-item{display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;border:.5px solid rgba(59,46,34,.07);background:#faf8f4;margin-bottom:7px;transition:border-color .2s;cursor:grab;}
.banner-item:active{cursor:grabbing;}
.banner-item.dragging-over{border-color:#c9a96e;background:#fdf6ea;}
.drag-handle{color:rgba(59,46,34,.25);flex-shrink:0;}
.banner-thumb{width:72px;height:44px;border-radius:7px;object-fit:cover;border:.5px solid rgba(59,46,34,.08);flex-shrink:0;background:#ece3d4;}
.banner-info{flex:1;min-width:0;}
.banner-title-text{font-size:13px;font-weight:500;color:#3b2e22;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.banner-meta{font-size:11px;color:rgba(59,46,34,.4);margin-top:2px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.action-group{display:flex;gap:5px;flex-shrink:0;}
.dot-green{display:inline-block;width:6px;height:6px;border-radius:50%;background:#27ae60;margin-right:4px;}
.dot-gray{display:inline-block;width:6px;height:6px;border-radius:50%;background:#bbb;margin-right:4px;}
input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}

.section-card{background:white;border-radius:12px;border:.5px solid rgba(59,46,34,.08);margin-bottom:16px;overflow:hidden;}
.section-card-header{display:flex;align-items:center;gap:12px;padding:15px 18px;border-bottom:.5px solid rgba(59,46,34,.05);}
.section-card-name{font-size:14px;font-weight:500;color:#3b2e22;flex:1;}

.prod-picker-list{display:flex;flex-direction:column;gap:6px;margin-bottom:10px;}
.prod-chip-card{display:flex;align-items:center;gap:10px;padding:8px 10px;background:#faf8f4;border-radius:8px;border:.5px solid rgba(59,46,34,.07);}
.prod-chip-img{width:40px;height:40px;border-radius:6px;object-fit:cover;background:#ece3d4;border:.5px solid rgba(59,46,34,.06);flex-shrink:0;}
.prod-chip-info{flex:1;min-width:0;}
.prod-chip-name{font-size:12px;font-weight:500;color:#3b2e22;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.prod-chip-meta{font-size:10px;color:rgba(59,46,34,.45);margin-top:1px;}
.chip-remove{background:none;border:none;cursor:pointer;color:rgba(59,46,34,.3);padding:4px;display:flex;align-items:center;flex-shrink:0;border-radius:4px;transition:all .2s;}
.chip-remove:hover{color:#c0392b;background:rgba(192,57,43,.06);}
.prod-add-wrap{display:flex;gap:7px;margin-top:4px;}
.add-prod-select{flex:1;padding:8px 11px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;font-size:12px;color:#3b2e22;background:white;font-family:'DM Sans',sans-serif;outline:none;}
.btn-add{background:#3b2e22;color:#f5f0e8;border:none;border-radius:8px;padding:8px 14px;font-size:11px;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;transition:background .2s;}
.btn-add:hover{background:#2e2318;}

.modal-overlay{display:none;position:fixed;inset:0;background:rgba(59,46,34,.4);z-index:200;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:26px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:14px;font-weight:500;color:#3b2e22;margin-bottom:18px;}
.modal-footer{display:flex;gap:10px;margin-top:16px;}
.btn-cancel{flex:1;padding:9px;border:.5px solid rgba(59,46,34,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;}
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
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;padding-bottom:12px;border-bottom:.5px solid rgba(59,46,34,.06);">
            <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(59,46,34,.4);">Semua Banner ({{ $banners->count() }})</p>
            <span style="font-size:11px;color:rgba(59,46,34,.3);">Drag ↕ untuk ubah urutan</span>
        </div>

        @if($banners->isEmpty())
            <p style="font-size:13px;color:rgba(59,46,34,.35);text-align:center;padding:32px 0;">Belum ada banner. Tambahkan banner di sebelah kanan.</p>
        @else
        @php
            $grouped   = $banners->groupBy('position');
            $posLabels = ['top'=>'Atas (Hero)','after_sections'=>'Tengah (Setelah Section)','bottom'=>'Bawah (Setelah Katalog)'];
            $posBadge  = ['top'=>'pos-top','after_sections'=>'pos-mid','bottom'=>'pos-bot'];
        @endphp

        @foreach(['top','after_sections','bottom'] as $pos)
        @if(isset($grouped[$pos]) && $grouped[$pos]->count() > 0)
        <div class="banner-group">
            <p class="banner-group-label">
                <span class="pos-badge {{ $posBadge[$pos] }}">{{ $posLabels[$pos] }}</span>
            </p>
            <div class="banner-sortable" data-position="{{ $pos }}" id="sortable-{{ $pos }}">
            @foreach($grouped[$pos] as $banner)
            <div class="banner-item" draggable="true" data-id="{{ $banner->id }}">
                <form id="del-b-{{$banner->id}}" action="{{route('admin.store-banners.destroy',$banner)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                <form id="tog-b-{{$banner->id}}" action="{{route('admin.store-banners.toggle',$banner)}}" method="POST" style="display:none;">@csrf</form>
                <span class="drag-handle">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="9" cy="5" r="1.2" fill="currentColor"/><circle cx="15" cy="5" r="1.2" fill="currentColor"/><circle cx="9" cy="12" r="1.2" fill="currentColor"/><circle cx="15" cy="12" r="1.2" fill="currentColor"/><circle cx="9" cy="19" r="1.2" fill="currentColor"/><circle cx="15" cy="19" r="1.2" fill="currentColor"/></svg>
                </span>
                <img src="{{ asset($banner->image) }}" class="banner-thumb" alt="">
                <div class="banner-info">
                    <p class="banner-title-text">{{ $banner->title ?? '(Tanpa Judul)' }}</p>
                    <div class="banner-meta">
                        <span><span class="{{ $banner->is_active?'dot-green':'dot-gray' }}"></span>{{ $banner->is_active?'Aktif':'Nonaktif' }}</span>
                        @if($banner->link)<span>🔗 {{ Str::limit($banner->link,26) }}</span>@endif
                    </div>
                </div>
                <div class="action-group">
                    <button class="btn-sm" onclick="openEditBanner({{ $banner->id }})">Edit</button>
                    <button class="btn-sm" onclick="document.getElementById('tog-b-{{$banner->id}}').submit()">
                        {{ $banner->is_active?'Off':'On' }}
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

    <div>
        <div class="card">
            <p class="card-title">Tambah Banner Baru</p>
            <form action="{{ route('admin.store-banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Gambar *</label>
                    <input type="file" name="image" class="form-input" accept="image/*" required
                           onchange="previewImg(this,'addPreview')">
                    <p class="form-hint">Rekomendasi 1200×400px. Max 4MB.</p>
                    <img id="addPreview" style="display:none;width:100%;max-height:100px;object-fit:cover;border-radius:7px;margin-top:8px;border:.5px solid rgba(59,46,34,.08);">
                </div>
                <div class="form-group">
                    <label class="form-label">Posisi Banner</label>
                    <select name="position" class="form-select">
                        <option value="top">Atas — Langsung setelah info toko</option>
                        <option value="after_sections">Tengah — Setelah section produk</option>
                        <option value="bottom">Bawah — Setelah katalog produk</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-input" placeholder="Flash Sale Ramadhan">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Diskon s/d 50%">
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
                    <input type="checkbox" name="auto_slide" id="autoSlide" class="toggle" checked>
                    <label for="autoSlide" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
                </div>
                <button type="submit" class="btn-submit" style="width:100%;">Tambah Banner</button>
            </form>
        </div>

        <div style="background:#fdf6ea;border:.5px solid #e8d9b4;border-radius:10px;padding:14px 16px;font-size:12px;color:#5a4a32;line-height:1.7;">
            <p style="font-weight:500;margin-bottom:6px;font-size:11px;letter-spacing:.08em;text-transform:uppercase;">Panduan Posisi</p>
            <p><span class="pos-badge pos-top">Atas</span> Tepat setelah hero toko. Untuk promo utama.</p><br>
            <p><span class="pos-badge pos-mid">Tengah</span> Setelah section produk pilihan.</p><br>
            <p><span class="pos-badge pos-bot">Bawah</span> Setelah katalog semua produk.</p>
        </div>
    </div>
</div>
</div>

<div id="tab-sections" class="tab-pane">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <p style="font-size:13px;color:rgba(59,46,34,.45);">{{ $sections->count() }} section</p>
        <button class="btn-submit" onclick="document.getElementById('addSectionModal').classList.add('show')">+ Section Baru</button>
    </div>

    @forelse($sections as $section)
    <form id="tog-s-{{$section->id}}" action="{{route('admin.store-sections.toggle',$section)}}" method="POST" style="display:none;">@csrf</form>
    <form id="del-s-{{$section->id}}" action="{{route('admin.store-sections.destroy',$section)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>

    <div class="section-card">
        <div class="section-card-header">
            <div style="flex:1;min-width:0;">
                <p class="section-card-name">{{ $section->title }}</p>
                @if($section->subtitle)<p style="font-size:11px;color:rgba(59,46,34,.4);margin-top:2px;">{{ $section->subtitle }}</p>@endif
                <p style="font-size:10px;color:rgba(59,46,34,.35);margin-top:4px;">
                    <span class="{{ $section->is_active?'dot-green':'dot-gray' }}"></span>
                    {{ $section->is_active?'Aktif':'Nonaktif' }} · {{ $section->products->count() }} produk · {{ $section->rows }} baris
                    @if($section->auto_slide) · Auto slide @endif
                </p>
            </div>
            <div class="action-group">
                <button class="btn-sm" onclick="openEditSection({{ $section->id }},'{{ addslashes($section->title) }}','{{ addslashes($section->subtitle??'') }}',{{ $section->rows }},{{ $section->auto_slide?'true':'false' }})">Edit</button>
                <button class="btn-sm" onclick="document.getElementById('tog-s-{{$section->id}}').submit()">{{ $section->is_active?'Off':'On' }}</button>
                <button class="btn-sm btn-sm-danger" onclick="if(confirm('Hapus section?')) document.getElementById('del-s-{{$section->id}}').submit()">Hapus</button>
            </div>
        </div>
        <div style="padding:14px 18px;">
            <p style="font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(59,46,34,.35);margin-bottom:10px;">Produk ({{ $section->products->count() }})</p>
            @if($section->products->count() > 0)
            <div class="prod-picker-list">
                @foreach($section->products as $prod)
                <div class="prod-chip-card">
                    <img src="{{ asset($prod->image ?? 'images/placeholder.jpg') }}" class="prod-chip-img" alt="{{ $prod->name }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    <div class="prod-chip-info">
                        <p class="prod-chip-name">{{ $prod->name }}</p>
                        <p class="prod-chip-meta">
                            @if($prod->category){{ $prod->category->name }} · @endif
                            Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}
                            @if($prod->hasDiscount()) <span style="color:#c0392b;">-{{ $prod->discount_percent }}%</span>@endif
                            @if(isset($prod->stock)) · Stok: {{ $prod->stock }}@endif
                        </p>
                    </div>
                    <form action="{{ route('admin.store-sections.products.remove',[$section,$prod]) }}" method="POST" style="display:contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="chip-remove" onclick="return confirm('Hapus dari section?')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
            <form action="{{ route('admin.store-sections.products.add',$section) }}" method="POST" class="prod-add-wrap">
                @csrf
                <select name="product_id" class="add-prod-select" required>
                    <option value="">— Pilih produk untuk ditambahkan —</option>
                    @foreach($products as $prod)
                    @if(!$section->products->contains($prod->id))
                    <option value="{{ $prod->id }}">
                        {{ $prod->name }} · Rp {{ number_format($prod->getFinalPrice(),0,',','.') }}
                        @if(isset($prod->stock)) · Stok: {{ $prod->stock }}@endif
                    </option>
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

            {{-- Preview gambar saat ini --}}
            <div style="margin-bottom:14px;">
                <p class="form-label">Gambar Saat Ini</p>
                <img id="eb-current-img" style="width:100%;max-height:110px;object-fit:cover;border-radius:8px;border:.5px solid rgba(59,46,34,.08);" src="" alt="">
            </div>

            <div class="form-group">
                <label class="form-label">Ganti Gambar <span style="font-weight:400;text-transform:none;">(kosongkan jika tidak diganti)</span></label>
                <input type="file" name="image" class="form-input" accept="image/*"
                       onchange="previewImg(this,'eb-new-preview')">
                <img id="eb-new-preview" style="display:none;width:100%;max-height:80px;object-fit:cover;border-radius:7px;margin-top:7px;border:.5px solid rgba(59,46,34,.08);">
            </div>

            <div class="form-group">
                <label class="form-label">Posisi Banner</label>
                <select name="position" id="eb-pos" class="form-select">
                    <option value="top">Atas — Langsung setelah info toko</option>
                    <option value="after_sections">Tengah — Setelah section produk</option>
                    <option value="bottom">Bawah — Setelah katalog produk</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Judul</label>
                <input type="text" name="title" id="eb-title" class="form-input" placeholder="(opsional)">
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" id="eb-sub" class="form-input" placeholder="(opsional)">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Link</label>
                    <input type="text" name="link" id="eb-link" class="form-input" placeholder="/products">
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" id="eb-btn" class="form-input" placeholder="Lihat Semua">
                </div>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="eb-auto" class="toggle">
                <label for="eb-auto" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel"
                        onclick="document.getElementById('editBannerModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan Perubahan</button>
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
                        <label for="as-auto" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
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
                        <label for="es-auto" style="font-size:13px;color:#3b2e22;cursor:pointer;">Auto slide</label>
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

@php
$bannersJson = $banners->map(fn($b) => [
    'id'          => $b->id,
    'title'       => $b->title,
    'subtitle'    => $b->subtitle,
    'link'        => $b->link,
    'button_text' => $b->button_text,
    'auto_slide'  => $b->auto_slide,
    'position'    => $b->position ?? 'top',
    'image'       => asset($b->image),
])->keyBy('id');
@endphp

<script>
function switchTab(tab, el) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.page-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-'+tab).classList.add('active');
    el.classList.add('active');
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
    document.getElementById('eb-current-img').src = b.image;
    const newPrev = document.getElementById('eb-new-preview');
    newPrev.src = ''; newPrev.style.display = 'none';
    document.getElementById('editBannerForm').action = '/admin/store-banners/' + id;
    document.getElementById('editBannerModal').classList.add('show');
}

function openEditSection(id, title, subtitle, rows, auto) {
    document.getElementById('es-title').value   = title;
    document.getElementById('es-sub').value     = subtitle;
    document.getElementById('es-rows').value    = rows;
    document.getElementById('es-auto').checked  = auto;
    document.getElementById('editSectionForm').action = '/admin/store-sections/' + id;
    document.getElementById('editSectionModal').classList.add('show');
}

(function () {
    let dragSrc = null;
    document.querySelectorAll('.banner-sortable').forEach(container => {
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
            const pos = container.dataset.position;
            const ids = [...container.querySelectorAll('.banner-item')].map(i => i.dataset.id);
            fetch('/admin/store-banners/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''
                },
                body: JSON.stringify({ ids, position: pos })
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
