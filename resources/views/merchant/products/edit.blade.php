@extends('merchant.layouts.sidebar')

@section('page-title', 'Edit Produk')

@section('content')

<style>
.form-card { background: white; border-radius: 14px; border: 0.5px solid rgba(11,42,74,0.08); padding: 32px; max-width: 800px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { margin-bottom: 20px; }
.form-group.full { grid-column: 1 / -1; }
.form-label { display: block; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.45); margin-bottom: 8px; }
.form-input, .form-textarea {
    width: 100%; padding: 11px 14px;
    border: 0.5px solid rgba(11,42,74,0.15); border-radius: 8px;
    font-size: 14px; color: #0b2a4a; font-family: 'DM Sans', sans-serif;
    outline: none; transition: border-color 0.2s; background: white;
    box-sizing: border-box;
}
.form-input:focus, .form-textarea:focus { border-color: #c9a96e; }
.form-textarea { resize: vertical; min-height: 100px; }
.form-hint { font-size: 11px; color: rgba(11,42,74,0.35); margin-top: 5px; }
.field-error { font-size: 12px; color: #c0392b; margin-top: 4px; }

.current-img { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.1); }
.current-label { font-size: 11px; color: rgba(11,42,74,0.4); margin-bottom: 8px; display: block; }

.extra-images { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
.extra-img-wrap { position: relative; display: inline-block; }
.extra-img { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.1); display: block; }
.extra-img-delete {
    position: absolute; top: -6px; right: -6px;
    width: 22px; height: 22px; border-radius: 50%;
    background: #c0392b; color: white; border: 2px solid white;
    cursor: pointer; font-size: 13px; font-weight: bold;
    display: flex; align-items: center; justify-content: center;
    line-height: 1; padding: 0;
}
.extra-img-delete:hover { background: #922b21; }

.image-preview { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
.image-preview-item { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.1); }

.toggle-wrap { display: flex; align-items: center; gap: 12px; }
input[type="checkbox"].toggle { width: 36px; height: 20px; appearance: none; -webkit-appearance: none; background: #e0e0e0; border-radius: 100px; cursor: pointer; transition: background 0.2s; position: relative; flex-shrink: 0; }
input[type="checkbox"].toggle:checked { background: #27ae60; }
input[type="checkbox"].toggle::after { content: ''; position: absolute; width: 16px; height: 16px; border-radius: 50%; background: white; top: 2px; left: 2px; transition: left 0.2s; }
input[type="checkbox"].toggle:checked::after { left: 18px; }

.form-footer { display: flex; gap: 12px; margin-top: 28px; padding-top: 24px; border-top: 0.5px solid rgba(11,42,74,0.06); }
.btn-submit { background: #0b2a4a; color: #f0ebe0; border: none; border-radius: 8px; padding: 12px 28px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.2s; }
.btn-submit:hover { background: #0d3459; }
.btn-cancel { background: none; color: rgba(11,42,74,0.45); border: 0.5px solid rgba(11,42,74,0.15); border-radius: 8px; padding: 12px 28px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; cursor: pointer; font-family: 'DM Sans', sans-serif; text-decoration: none; display: inline-flex; align-items: center; }

.section-divider { height: 0.5px; background: rgba(11,42,74,0.06); margin: 4px 0 20px; }
</style>

@foreach($product->images as $img)
<form id="delete-image-{{ $img->id }}"
      action="{{ route('merchant.products.images.destroy', $img) }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach


<div class="form-card">
    <form action="{{ route('merchant.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if(session('success'))
            <div style="background:#f0f7f0; border:0.5px solid #b2d9b2; border-radius:8px; padding:10px 16px; font-size:13px; color:#2d6a2d; margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-grid">

            <div class="form-group full">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-input" style="appearance:none;">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach(\App\Models\Category::active()->orderBy('sort')->get() as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" class="form-input" value="{{ old('price', $product->price) }}" required min="0">
                @error('price') <p class="field-error">{{ $message }}</p> @enderror
            </div>

<div class="form-group">
    <label class="form-label">Diskon (%)</label>
    <div style="position:relative;">
        <input type="number" name="discount_percent" class="form-input"
               value="{{ old('discount_percent', $product->discount_percent ?? 0) }}"
               min="0" max="100" placeholder="0"
               oninput="updateDiscountPreview(this.value)"
               style="padding-right:40px;">
        <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:13px;color:rgba(11,42,74,.4);">%</span>
    </div>
    <p class="form-hint" id="discountPreview">
        @if(isset($product) && $product->discount_percent > 0)
            Harga setelah diskon: <strong>Rp {{ number_format($product->getFinalPrice(),0,',','.') }}</strong>
        @else
            Kosongkan atau isi 0 jika tidak ada diskon.
        @endif
    </p>
</div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="toggle-wrap" style="margin-top:10px;">
                    <input type="checkbox" name="is_active" class="toggle" id="isActive" {{ $product->is_active ? 'checked' : '' }}>
                    <label for="isActive" style="font-size:14px; color:#0b2a4a; cursor:pointer;">Tampilkan di toko</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" class="form-input" value="{{ old('stock', $product->stock ?? 0) }}" min="0" placeholder="0">
                <p class="form-hint">Isi 0 jika tidak ingin membatasi stok.</p>
            </div>


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
                    <p class="form-hint" style="margin-top:8px;">Upload gambar baru di bawah untuk mengganti gambar ini.</p>
                @endif
                <input type="file" name="image" class="form-input" accept="image/*" onchange="previewMain(this)" style="margin-top:10px;">
                <p class="form-hint">Kosongkan jika tidak ingin mengganti. Max 2MB. Format: JPG, PNG, WEBP</p>
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
  
                            <button type="submit"
                                    form="delete-image-{{ $img->id }}"
                                    class="extra-img-delete"
                                    onclick="return confirm('Hapus gambar ini?')"
                                    title="Hapus gambar">×</button>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="form-hint" style="margin-bottom:12px;">Belum ada gambar tambahan.</p>
                @endif
                <input type="file" name="images[]" class="form-input" accept="image/*" multiple onchange="previewMultiple(this)" style="margin-top:12px;">
                <p class="form-hint">Pilih beberapa gambar sekaligus (tahan Ctrl/Cmd untuk memilih lebih dari satu). Max 2MB per gambar.</p>
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
function previewMain(input) {
    const preview = document.getElementById('mainPreview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(input.files[0]);
        img.className = 'image-preview-item';
        preview.appendChild(img);
    }
}
function previewMultiple(input) {
    const preview = document.getElementById('multiPreview');
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'image-preview-item';
        preview.appendChild(img);
    });
}
</script>

<script>
function updateDiscountPreview(discount) {
    const price = parseInt(document.querySelector('[name="price"]').value) || 0;
    const d = parseInt(discount) || 0;
    const preview = document.getElementById('discountPreview');
    if (d > 0 && price > 0) {
        const final = Math.round(price * (1 - d / 100));
        preview.innerHTML = 'Harga setelah diskon: <strong>Rp ' + final.toLocaleString('id-ID') + '</strong>';
    } else {
        preview.innerHTML = 'Kosongkan atau isi 0 jika tidak ada diskon.';
    }
}
document.querySelector('[name="price"]')?.addEventListener('input', function() {
    updateDiscountPreview(document.querySelector('[name="discount_percent"]')?.value || 0);
});
</script>

@endsection
