@extends('merchant.layouts.sidebar')
@section('page-title', 'Edit Produk')
@section('content')

<style>
.form-card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:32px;max-width:860px;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
.form-group{margin-bottom:20px;}
.form-group.full{grid-column:1/-1;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.form-input,.form-textarea{width:100%;padding:11px 14px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:14px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus,.form-textarea:focus{border-color:#c9a96e;}
.form-textarea{resize:vertical;min-height:100px;}
.form-hint{font-size:11px;color:rgba(11,42,74,.35);margin-top:5px;}
.field-error{font-size:12px;color:#c0392b;margin-top:4px;}
.section-divider{height:.5px;background:rgba(11,42,74,.06);margin:4px 0 20px;}
.current-img{width:80px;height:80px;border-radius:8px;object-fit:cover;border:.5px solid rgba(11,42,74,.1);}
.current-label{font-size:11px;color:rgba(11,42,74,.4);margin-bottom:8px;display:block;}
.extra-images{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px;}
.extra-img-wrap{position:relative;display:inline-block;}
.extra-img{width:80px;height:80px;border-radius:8px;object-fit:cover;border:.5px solid rgba(11,42,74,.1);display:block;}
.extra-img-delete{position:absolute;top:-6px;right:-6px;width:22px;height:22px;border-radius:50%;background:#c0392b;color:white;border:2px solid white;cursor:pointer;font-size:13px;font-weight:bold;display:flex;align-items:center;justify-content:center;line-height:1;padding:0;}
.extra-img-delete:hover{background:#922b21;}
.image-preview{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px;}
.image-preview-item{width:80px;height:80px;border-radius:8px;object-fit:cover;border:.5px solid rgba(11,42,74,.1);}
.size-section-label{font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.size-section-label::after{content:'';flex:1;height:.5px;background:rgba(11,42,74,.08);}
.size-input-wrap{display:flex;}
.size-input-wrap .form-input{border-radius:8px 0 0 8px;border-right:none;flex:1;}
.size-unit-select{padding:11px 12px;border:.5px solid rgba(11,42,74,.15);border-radius:0 8px 8px 0;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;background:#f7f5f0;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;min-width:80px;text-align:center;transition:border-color .2s;}
.size-unit-select:focus{border-color:#c9a96e;}
.toggle-wrap{display:flex;align-items:center;gap:12px;}
input[type="checkbox"].toggle{width:36px;height:20px;appearance:none;-webkit-appearance:none;background:#e0e0e0;border-radius:100px;cursor:pointer;transition:background .2s;position:relative;flex-shrink:0;}
input[type="checkbox"].toggle:checked{background:#27ae60;}
input[type="checkbox"].toggle::after{content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:white;top:2px;left:2px;transition:left .2s;}
input[type="checkbox"].toggle:checked::after{left:18px;}
.form-footer{display:flex;gap:12px;margin-top:28px;padding-top:24px;border-top:.5px solid rgba(11,42,74,.06);}
.btn-submit{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:12px 28px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-submit:hover{background:#0d3459;}
.btn-cancel{background:none;color:rgba(11,42,74,.45);border:.5px solid rgba(11,42,74,.15);border-radius:8px;padding:12px 28px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-flex;align-items:center;}
</style>

@foreach($product->images as $img)
<form id="delete-image-{{ $img->id }}" action="{{ route('merchant.products.images.destroy', $img) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endforeach

<div class="form-card">
<form action="{{ route('merchant.products.update', $product) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

@if(session('success'))
<div style="background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:10px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;">{{ session('success') }}</div>
@endif
@if($errors->any())
<div style="background:#fdf0f0;border:.5px solid #f5c6c6;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;">
    <ul style="margin:0;padding-left:16px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="form-grid">

    <div class="form-group full">
        <label class="form-label">Nama Produk *</label>
        <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
        @error('name')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-input" style="appearance:none;">
            <option value="">— Tanpa Kategori —</option>
            @foreach(\App\Models\Category::active()->orderBy('sort')->get() as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                {{ $cat->icon ? $cat->icon.' ' : '' }}{{ $cat->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="form-label">Harga Dasar (Rp) *</label>
        <input type="number" name="price" class="form-input" value="{{ old('price', $product->price) }}" required min="0">
    </div>

    <div class="form-group">
        <label class="form-label">Diskon (%)</label>
        <div style="position:relative;">
            <input type="number" name="discount_percent" class="form-input"
                   value="{{ old('discount_percent', $product->discount_percent ?? 0) }}"
                   min="0" max="100" placeholder="0"
                   oninput="updateDiscountPreview(this.value)" style="padding-right:40px;">
            <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:13px;color:rgba(11,42,74,.4);">%</span>
        </div>
        <p class="form-hint" id="discountPreview">
            @if($product->discount_percent > 0)
                Harga setelah diskon: <strong>Rp {{ number_format($product->getFinalPrice(), 0, ',', '.') }}</strong>
            @else
                Berlaku untuk harga dasar. Variasi punya harga sendiri.
            @endif
        </p>
    </div>

    <div class="form-group">
        <label class="form-label">Status</label>
        <div class="toggle-wrap" style="margin-top:10px;">
            <input type="checkbox" name="is_active" class="toggle" id="isActive" {{ $product->is_active ? 'checked' : '' }}>
            <label for="isActive" style="font-size:14px;color:#0b2a4a;cursor:pointer;">Tampilkan di toko</label>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Stok Dasar</label>
        <input type="number" name="stock" class="form-input" value="{{ old('stock', $product->stock ?? 0) }}" min="0" placeholder="0">
        <p class="form-hint">Untuk produk tanpa variasi. Jika ada variasi, stok diatur per baris.</p>
    </div>

    {{-- ── UKURAN & VARIASI ──────────────────────────────────────── --}}
    <div class="form-group full">
        <div class="size-section-label">Ukuran Tunggal
            <span style="font-size:10px;color:rgba(11,42,74,.3);text-transform:none;letter-spacing:0;">— isi jika produk hanya 1 ukuran</span>
        </div>
        <div class="form-grid" style="gap:16px;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tinggi</label>
                <div class="size-input-wrap">
                    <input type="number" name="height" class="form-input"
                           value="{{ old('height', $product->height ? ($product->height == (int)$product->height ? (int)$product->height : $product->height) : '') }}"
                           placeholder="cth: 30" min="0" step="0.01">
                    <select name="height_unit" class="size-unit-select">
                        <option value="cm"    {{ old('height_unit', $product->height_unit ?? 'cm') === 'cm'    ? 'selected' : '' }}>cm</option>
                        <option value="meter" {{ old('height_unit', $product->height_unit ?? 'cm') === 'meter' ? 'selected' : '' }}>meter</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Diameter</label>
                <div class="size-input-wrap">
                    <input type="number" name="diameter" class="form-input"
                           value="{{ old('diameter', $product->diameter ? ($product->diameter == (int)$product->diameter ? (int)$product->diameter : $product->diameter) : '') }}"
                           placeholder="cth: 15" min="0" step="0.01">
                    <select name="diameter_unit" class="size-unit-select">
                        <option value="cm"    {{ old('diameter_unit', $product->diameter_unit ?? 'cm') === 'cm'    ? 'selected' : '' }}>cm</option>
                        <option value="meter" {{ old('diameter_unit', $product->diameter_unit ?? 'cm') === 'meter' ? 'selected' : '' }}>meter</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group full" style="background:#fafaf8;border:.5px solid rgba(11,42,74,.07);border-radius:12px;padding:20px 24px;">
        @include('merchant.products._variant_builder', ['product' => $product])
    </div>
    {{-- ─────────────────────────────────────────────────────────── --}}

    <div class="form-group">
        <label class="form-label">Deskripsi Singkat — Indonesia</label>
        <input type="text" name="desc_id" class="form-input" value="{{ old('desc_id', $product->desc_id) }}">
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi Singkat — English</label>
        <input type="text" name="desc_en" class="form-input" value="{{ old('desc_en', $product->desc_en) }}">
    </div>
    <div class="form-group full">
        <label class="form-label">Detail Produk — Indonesia</label>
        <textarea name="detail_id" class="form-textarea">{{ old('detail_id', $product->detail_id) }}</textarea>
    </div>
    <div class="form-group full">
        <label class="form-label">Detail Produk — English</label>
        <textarea name="detail_en" class="form-textarea">{{ old('detail_en', $product->detail_en) }}</textarea>
    </div>

    <div class="form-group full">
        <label class="form-label">Gambar Utama</label>
        <div class="section-divider"></div>
        @if($product->image)
            <span class="current-label">Gambar saat ini:</span>
            <img src="{{ asset($product->image) }}" class="current-img" alt="{{ $product->name }}">
            <p class="form-hint" style="margin-top:8px;">Upload baru untuk mengganti.</p>
        @endif
        <input type="file" name="image" class="form-input" accept="image/*" onchange="previewMain(this)" style="margin-top:10px;">
        <p class="form-hint">Max 2MB. Format: JPG, PNG, WEBP</p>
        <div id="mainPreview" class="image-preview"></div>
    </div>

    <div class="form-group full">
        <label class="form-label">Gambar Tambahan</label>
        <div class="section-divider"></div>
        @if($product->images->count() > 0)
            <span class="current-label">Gambar saat ini — klik × untuk hapus:</span>
            <div class="extra-images">
                @foreach($product->images as $img)
                <div class="extra-img-wrap">
                    <img src="{{ asset($img->image) }}" class="extra-img" alt="">
                    <button type="submit" form="delete-image-{{ $img->id }}" class="extra-img-delete" onclick="return confirm('Hapus gambar ini?')">×</button>
                </div>
                @endforeach
            </div>
        @else
            <p class="form-hint" style="margin-bottom:12px;">Belum ada gambar tambahan.</p>
        @endif
        <input type="file" name="images[]" class="form-input" accept="image/*" multiple onchange="previewMultiple(this)" style="margin-top:12px;">
        <p class="form-hint">Tahan Ctrl/Cmd untuk pilih lebih dari satu. Max 2MB per gambar.</p>
        <div id="multiPreview" class="image-preview"></div>
    </div>

</div>

<div class="form-footer">
    <button type="submit" class="btn-submit">Update Produk</button>
    <a href="{{ route('merchant.products.index') }}" class="btn-cancel">Batal</a>
</div>
</form>
</div>

<script>
function previewMain(input){const p=document.getElementById('mainPreview');p.innerHTML='';if(input.files&&input.files[0]){const i=document.createElement('img');i.src=URL.createObjectURL(input.files[0]);i.className='image-preview-item';p.appendChild(i);}}
function previewMultiple(input){const p=document.getElementById('multiPreview');p.innerHTML='';Array.from(input.files).forEach(f=>{const i=document.createElement('img');i.src=URL.createObjectURL(f);i.className='image-preview-item';p.appendChild(i);});}
function updateDiscountPreview(discount){
    const price=parseInt(document.querySelector('[name="price"]').value)||0;
    const d=parseInt(discount)||0;
    const preview=document.getElementById('discountPreview');
    if(d>0&&price>0){const final=Math.round(price*(1-d/100));preview.innerHTML='Harga setelah diskon: <strong>Rp '+final.toLocaleString('id-ID')+'</strong>';}
    else{preview.innerHTML='Berlaku untuk harga dasar. Variasi punya harga sendiri.';}
}
document.querySelector('[name="price"]')?.addEventListener('input',function(){updateDiscountPreview(document.querySelector('[name="discount_percent"]')?.value||0);});
</script>
@endsection
