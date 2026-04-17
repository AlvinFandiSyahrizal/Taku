@extends('admin.layouts.sidebar')
@section('page-title', 'Banner')
@section('content')

<style>
.page-layout{display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:flex-start;}
.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:24px;margin-bottom:20px;}
.card-title{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:20px;padding-bottom:14px;border-bottom:.5px solid rgba(11,42,74,.06);}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.form-input{width:100%;padding:10px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus{border-color:#c9a96e;}
.form-hint{font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;}
.btn-submit{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:11px 24px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-submit:hover{background:#0d3459;}

.banner-list{}
.banner-item{display:flex;align-items:center;gap:16px;padding:14px 0;border-bottom:.5px solid rgba(11,42,74,.06);}
.banner-item:last-child{border-bottom:none;}
.banner-thumb{width:80px;height:48px;border-radius:8px;object-fit:cover;border:.5px solid rgba(11,42,74,.08);flex-shrink:0;background:#f5f1e8;}
.banner-info{flex:1;min-width:0;}
.banner-title-text{font-size:13px;font-weight:500;color:#0b2a4a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.banner-meta{font-size:11px;color:rgba(11,42,74,.4);margin-top:2px;}
.action-group{display:flex;gap:6px;flex-shrink:0;}
.btn-sm{font-size:10px;letter-spacing:.08em;text-transform:uppercase;border-radius:6px;padding:5px 10px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.6);transition:all .2s;}
.btn-sm:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-sm-danger{color:#c0392b;border-color:rgba(192,57,43,.2);}
.btn-sm-danger:hover{background:#c0392b;color:white;border-color:#c0392b;}
.dot-green{display:inline-block;width:6px;height:6px;border-radius:50%;background:#27ae60;margin-right:4px;}
.dot-gray{display:inline-block;width:6px;height:6px;border-radius:50%;background:#bbb;margin-right:4px;}
.toggle-wrap{display:flex;align-items:center;gap:8px;}
input[type="checkbox"].toggle{width:32px;height:18px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:16px;}

.modal-overlay{display:none;position:fixed;inset:0;background:rgba(11,42,74,.4);z-index:100;align-items:center;justify-content:center;}
.modal-overlay.show{display:flex;}
.modal-box{background:white;border-radius:16px;padding:28px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:15px;font-weight:500;color:#0b2a4a;margin-bottom:20px;}
</style>

<div class="page-layout">
    <div class="card">
        <p class="card-title">Semua Banner</p>

        @if(session('success'))
            <div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:16px;">{{ session('success') }}</div>
        @endif

        @if($banners->isEmpty())
            <p style="font-size:13px;color:rgba(11,42,74,.35);text-align:center;padding:40px 0;">Belum ada banner.</p>
        @else
        <div class="banner-list">
            @foreach($banners as $banner)
            <form id="del-banner-{{$banner->id}}" action="{{route('admin.banners.destroy',$banner)}}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
            <form id="toggle-banner-{{$banner->id}}" action="{{route('admin.banners.toggle',$banner)}}" method="POST" style="display:none;">@csrf</form>

            <div class="banner-item">
                @if($banner->image)
                    <img src="{{ asset($banner->image) }}" class="banner-thumb" alt="">
                @else
                    <div class="banner-thumb" style="display:flex;align-items:center;justify-content:center;color:rgba(11,42,74,.2);font-size:11px;">No Image</div>
                @endif
                <div class="banner-info">
                    <p class="banner-title-text">{{ $banner->title ?? '(Tanpa Judul)' }}</p>
                    <p class="banner-meta">
                        <span class="{{ $banner->is_active ? 'dot-green' : 'dot-gray' }}"></span>
                        {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                        · Sort: {{ $banner->sort }}
                        @if($banner->auto_slide) · Auto @endif
                    </p>
                </div>
                <div class="action-group">
                    <button class="btn-sm" onclick="openEditBanner({{ $banner->id }})">Edit</button>
                    <button class="btn-sm" onclick="document.getElementById('toggle-banner-{{$banner->id}}').submit()">
                        {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
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

    <div class="card">
        <p class="card-title">Tambah Banner</p>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Gambar Banner *</label>
                <input type="file" name="image" class="form-input" accept="image/*" required>
                <p class="form-hint">Rekomendasi: 1200×400px. Max 4MB.</p>
            </div>
            <div class="form-group">
                <label class="form-label">Judul (opsional)</label>
                <input type="text" name="title" class="form-input" placeholder="Flash Sale Hari Ini">
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul (opsional)</label>
                <input type="text" name="subtitle" class="form-input" placeholder="Diskon hingga 50%">
            </div>
            <div class="form-group">
                <label class="form-label">Link (opsional)</label>
                <input type="text" name="link" class="form-input" placeholder="/products?category=batik">
            </div>
            <div class="form-group">
                <label class="form-label">Teks Tombol (opsional)</label>
                <input type="text" name="button_text" class="form-input" placeholder="Lihat Promo">
            </div>
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort" class="form-input" value="0" min="0">
            </div>
            <div class="form-group">
                <div class="toggle-wrap">
                    <input type="checkbox" name="auto_slide" class="toggle" id="autoSlide" checked>
                    <label for="autoSlide" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                </div>
                <p class="form-hint">Kalau ada 2+ banner aktif, otomatis berganti.</p>
            </div>
            <button type="submit" class="btn-submit">Tambah Banner</button>
        </form>
    </div>
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
                <input type="text" name="title" id="editBannerTitle" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Subjudul</label>
                <input type="text" name="subtitle" id="editBannerSubtitle" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Link</label>
                <input type="text" name="link" id="editBannerLink" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Teks Tombol</label>
                <input type="text" name="button_text" id="editBannerBtn" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort" id="editBannerSort" class="form-input">
            </div>
            <div class="form-group">
                <div class="toggle-wrap">
                    <input type="checkbox" name="auto_slide" id="editAutoSlide" class="toggle">
                    <label for="editAutoSlide" style="font-size:13px;color:#0b2a4a;cursor:pointer;">Auto slide</label>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="button" onclick="document.getElementById('editBannerModal').classList.remove('show')"
                    style="flex:1;padding:10px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">
                    Batal
                </button>
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
])->keyBy('id');
@endphp

<script>
const bannersData = @json($bannersJson);
function openEditBanner(id) {
    const b = bannersData[id];
    document.getElementById('editBannerTitle').value    = b.title ?? '';
    document.getElementById('editBannerSubtitle').value = b.subtitle ?? '';
    document.getElementById('editBannerLink').value     = b.link ?? '';
    document.getElementById('editBannerBtn').value      = b.button_text ?? '';
    document.getElementById('editBannerSort').value     = b.sort ?? 0;
    document.getElementById('editAutoSlide').checked    = b.auto_slide;
    document.getElementById('editBannerForm').action    = '/admin/banners/' + id;
    document.getElementById('editBannerModal').classList.add('show');
}
</script>

@endsection

