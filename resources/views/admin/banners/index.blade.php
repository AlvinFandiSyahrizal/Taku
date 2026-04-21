@extends('admin.layouts.sidebar')
@section('page-title', 'Banner Beranda')
@section('content')

<style>
*{box-sizing:border-box}
.page-layout{display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:flex-start;}
@media(max-width:960px){.page-layout{grid-template-columns:1fr;}}
.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:22px;margin-bottom:18px;}
.card-title{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:18px;padding-bottom:12px;border-bottom:.5px solid rgba(11,42,74,.06);}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:7px;}
.form-input,.form-select{width:100%;padding:9px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus,.form-select:focus{border-color:#c9a96e;}
.form-hint{font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.btn-submit{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:10px 22px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-submit:hover{background:#0d3459;}
.btn-sm{font-size:10px;letter-spacing:.07em;text-transform:uppercase;border-radius:6px;padding:5px 9px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.55);transition:all .2s;}
.btn-sm:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}

/* Banner items */
.banner-sortable{min-height:20px;}
.banner-item{display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;border:.5px solid rgba(11,42,74,.07);background:#f8fafc;margin-bottom:7px;transition:box-shadow .2s,border-color .2s;cursor:grab;}
.banner-item.dragging-over{border-color:#c9a96e;background:#fdf6ea;}
.drag-handle{color:rgba(11,42,74,.25);flex-shrink:0;}
.banner-thumb{width:72px;height:44px;border-radius:7px;object-fit:cover;border:.5px solid rgba(11,42,74,.08);flex-shrink:0;background:#eef2f7;}
.banner-thumb-empty{width:72px;height:44px;border-radius:7px;border:.5px solid rgba(11,42,74,.06);background:#f0f4f8;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:9px;color:rgba(11,42,74,.25);}
.banner-info{flex:1;min-width:0;}
.banner-title-text{font-size:13px;font-weight:500;color:#0b2a4a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.banner-meta{font-size:11px;color:rgba(11,42,74,.4);margin-top:2px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.action-group{display:flex;gap:5px;flex-shrink:0;}
.dot-green{display:inline-block;width:6px;height:6px;border-radius:50%;background:#27ae60;margin-right:4px;}
.dot-gray{display:inline-block;width:6px;height:6px;border-radius:50%;background:#bbb;margin-right:4px;}
input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(11,42,74,.4);z-index:200;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:26px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:14px;font-weight:500;color:#0b2a4a;margin-bottom:18px;}
.modal-footer{display:flex;gap:10px;margin-top:16px;}
.btn-cancel{flex:1;padding:9px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;}

.hint-box{background:#f0f4f8;border:.5px solid #dce4ee;border-radius:10px;padding:14px 16px;font-size:12px;color:#2a4a70;line-height:1.7;margin-top:0;}
</style>

@if(session('success'))
<div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="page-layout">

    {{-- LIST --}}
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;padding-bottom:12px;border-bottom:.5px solid rgba(11,42,74,.06);">
            <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);">Semua Banner ({{ $banners->count() }})</p>
            <span style="font-size:11px;color:rgba(11,42,74,.3);">Drag ↕ untuk ubah urutan</span>
        </div>

        @if($banners->isEmpty())
            <p style="font-size:13px;color:rgba(11,42,74,.35);text-align:center;padding:40px 0;">Belum ada banner.</p>
        @else
        <div class="banner-sortable" id="bannerSortable">
            @foreach($banners as $banner)
            <div class="banner-item" draggable="true" data-id="{{ $banner->id }}">
                <form id="del-banner-{{$banner->id}}" action="{{route('admin.banners.destroy',$banner)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                <form id="toggle-banner-{{$banner->id}}" action="{{route('admin.banners.toggle',$banner)}}" method="POST" style="display:none;">@csrf</form>
                <span class="drag-handle" title="Drag untuk reorder">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="6" r="1" fill="currentColor"/><circle cx="15" cy="6" r="1" fill="currentColor"/><circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/><circle cx="9" cy="18" r="1" fill="currentColor"/><circle cx="15" cy="18" r="1" fill="currentColor"/></svg>
                </span>
                @if($banner->image)
                    <img src="{{ asset($banner->image) }}" class="banner-thumb" alt="">
                @else
                    <div class="banner-thumb-empty">No Img</div>
                @endif
                <div class="banner-info">
                    <p class="banner-title-text">{{ $banner->title ?? '(Tanpa Judul)' }}</p>
                    <div class="banner-meta">
                        <span><span class="{{ $banner->is_active ? 'dot-green' : 'dot-gray' }}"></span>{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        <span>#{{ $banner->sort }}</span>
                        @if($banner->auto_slide)<span>Auto</span>@endif
                        @if($banner->link)<span>🔗 {{ Str::limit($banner->link,24) }}</span>@endif
                    </div>
                </div>
                <div class="action-group">
                    <button class="btn-sm" onclick="openEditBanner({{ $banner->id }})">Edit</button>
                    <button class="btn-sm" onclick="document.getElementById('toggle-banner-{{$banner->id}}').submit()">
                        {{ $banner->is_active ? 'Off' : 'On' }}
                    </button>
                    <button class="btn-sm btn-sm-danger"
                            onclick="if(confirm('Hapus banner ini?')) document.getElementById('del-banner-{{$banner->id}}').submit()">
                        Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- FORM --}}
    <div>
        <div class="card">
            <p class="card-title">Tambah Banner</p>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Gambar Banner *</label>
                    <input type="file" name="image" class="form-input" accept="image/*" required
                           onchange="previewImg(this,'addPreview')">
                    <p class="form-hint">Rekomendasi 1200×400px. Max 4MB.</p>
                    <img id="addPreview" style="display:none;width:100%;max-height:100px;object-fit:cover;border-radius:7px;margin-top:8px;border:.5px solid rgba(11,42,74,.08);">
                </div>
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-input" placeholder="Flash Sale Hari Ini">
                </div>
                <div class="form-group">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Diskon hingga 50%">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Link</label>
                        <input type="text" name="link" class="form-input" placeholder="/products">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teks Tombol</label>
                        <input type="text" name="button_text" class="form-input" placeholder="Lihat Promo">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort" class="form-input" value="{{ $banners->count() }}" min="0">
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" name="auto_slide" class="toggle" id="autoSlide" checked>
                    <label for="autoSlide" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                </div>
                <button type="submit" class="btn-submit" style="width:100%;">Tambah Banner</button>
            </form>
        </div>

        <div class="hint-box">
            <p style="font-weight:500;font-size:11px;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;">Tips</p>
            <p>Banner beranda tampil di halaman utama situs. Drag banner di list kiri untuk mengubah urutan tampil. Minimal 2 banner agar auto-slide aktif.</p>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="editBannerModal">
    <div class="modal-box">
        <p class="modal-title">Edit Banner</p>
        <form id="editBannerForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Gambar Baru <span style="font-weight:400;font-size:11px;text-transform:none;">(kosongkan jika tidak diganti)</span></label>
                <input type="file" name="image" class="form-input" accept="image/*"
                       onchange="previewImg(this,'editPreview')">
                <img id="editPreview" style="display:none;width:100%;max-height:100px;object-fit:cover;border-radius:7px;margin-top:8px;border:.5px solid rgba(11,42,74,.08);">
                <img id="editCurrentImg" style="width:100%;max-height:80px;object-fit:cover;border-radius:7px;margin-top:8px;border:.5px solid rgba(11,42,74,.08);">
            </div>
            <div class="form-group">
                <label class="form-label">Judul</label>
                <input type="text" name="title" id="editBannerTitle" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" id="editBannerSubtitle" class="form-input">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Link</label>
                    <input type="text" name="link" id="editBannerLink" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" id="editBannerBtn" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort" id="editBannerSort" class="form-input">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="auto_slide" id="editAutoSlide" class="toggle">
                <label for="editAutoSlide" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="document.getElementById('editBannerModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

@php
$bannersJson = $banners->map(fn($b) => [
    'id' => $b->id, 'title' => $b->title, 'subtitle' => $b->subtitle,
    'link' => $b->link, 'button_text' => $b->button_text,
    'sort' => $b->sort, 'auto_slide' => $b->auto_slide,
    'image' => $b->image ? asset($b->image) : null,
])->keyBy('id');
@endphp

<script>
const bannersData = @json($bannersJson);

function previewImg(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        r.readAsDataURL(input.files[0]);
    }
}

function openEditBanner(id) {
    const b = bannersData[id];
    document.getElementById('editBannerTitle').value    = b.title ?? '';
    document.getElementById('editBannerSubtitle').value = b.subtitle ?? '';
    document.getElementById('editBannerLink').value     = b.link ?? '';
    document.getElementById('editBannerBtn').value      = b.button_text ?? '';
    document.getElementById('editBannerSort').value     = b.sort ?? 0;
    document.getElementById('editAutoSlide').checked    = b.auto_slide;
    const ci = document.getElementById('editCurrentImg');
    if (b.image) { ci.src = b.image; ci.style.display = 'block'; }
    else ci.style.display = 'none';
    document.getElementById('editPreview').style.display = 'none';
    document.getElementById('editBannerForm').action = '/admin/banners/' + id;
    document.getElementById('editBannerModal').classList.add('show');
}

// Drag reorder
(function(){
    let dragSrc = null;
    const container = document.getElementById('bannerSortable');
    if (!container) return;
    container.addEventListener('dragstart', e => {
        const item = e.target.closest('.banner-item');
        if (!item) return;
        dragSrc = item;
        setTimeout(() => item.style.opacity = '.4', 0);
    });
    container.addEventListener('dragend', e => {
        const item = e.target.closest('.banner-item');
        if (item) item.style.opacity = '1';
        container.querySelectorAll('.banner-item').forEach(i => i.classList.remove('dragging-over'));
        const ids = [...container.querySelectorAll('.banner-item')].map(i => i.dataset.id);
        fetch('/admin/banners/reorder', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content ?? ''},
            body: JSON.stringify({ ids })
        });
    });
    container.addEventListener('dragover', e => {
        e.preventDefault();
        const over = e.target.closest('.banner-item');
        if (over && over !== dragSrc) {
            container.querySelectorAll('.banner-item').forEach(i => i.classList.remove('dragging-over'));
            over.classList.add('dragging-over');
            const rect  = over.getBoundingClientRect();
            const after = (e.clientY - rect.top) > rect.height / 2;
            container.insertBefore(dragSrc, after ? over.nextSibling : over);
        }
    });
})();
</script>

@endsection