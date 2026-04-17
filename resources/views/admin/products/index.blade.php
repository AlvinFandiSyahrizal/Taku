@extends('admin.layouts.sidebar')

@section('page-title', 'Produk')

@section('content')

<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.btn-primary {
    background: #0b2a4a; color: #f0ebe0; border: none; border-radius: 8px;
    padding: 10px 20px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase;
    font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif;
    text-decoration: none; transition: background 0.2s; display: inline-block;
}
.btn-primary:hover { background: #0d3459; color: #f0ebe0; }

.flash { background:#f0f7f0; border:0.5px solid #b2d9b2; border-radius:8px; padding:10px 16px; font-size:13px; color:#2d6a2d; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
.flash-dot { width:6px; height:6px; border-radius:50%; background:#2d6a2d; flex-shrink:0; }

.products-table { width: 100%; border-collapse: collapse; background: white; border-radius: 14px; overflow: hidden; border: 0.5px solid rgba(11,42,74,0.08); }
.products-table th {
    font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase;
    color: rgba(11,42,74,0.4); font-weight: 400;
    padding: 14px 20px; text-align: left;
    border-bottom: 0.5px solid rgba(11,42,74,0.06);
    background: #fafaf8;
}
.products-table td {
    padding: 14px 20px; font-size: 13px; color: #0b2a4a;
    border-bottom: 0.5px solid rgba(11,42,74,0.04); vertical-align: middle;
}
.products-table tr:last-child td { border-bottom: none; }
.products-table tr:hover td { background: #fafaf8; }

.product-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 0.5px solid rgba(11,42,74,0.08); display: block; }
.product-thumb-placeholder { width: 48px; height: 48px; border-radius: 8px; background: #f0ede8; display: flex; align-items: center; justify-content: center; color: rgba(11,42,74,0.2); font-size: 20px; }

.badge-active { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; letter-spacing: 0.06em; }
.badge-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.badge-dot.on { background: #27ae60; }
.badge-dot.off { background: #ccc; }
.badge-text.on { color: #27ae60; }
.badge-text.off { color: rgba(11,42,74,0.3); }

.action-btns { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.btn-edit {
    font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase;
    color: #0b2a4a; text-decoration: none; border: 0.5px solid rgba(11,42,74,0.2);
    border-radius: 6px; padding: 5px 12px; transition: border-color 0.2s, background 0.2s;
    white-space: nowrap;
}
.btn-edit:hover { border-color: #0b2a4a; background: rgba(11,42,74,0.04); }
.btn-delete {
    font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase;
    color: rgba(11,42,74,0.35); border: 0.5px solid rgba(11,42,74,0.1);
    border-radius: 6px; padding: 5px 12px; background: none; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: color 0.2s, border-color 0.2s;
    white-space: nowrap;
}
.btn-delete:hover { color: #c0392b; border-color: rgba(192,57,43,0.4); }
.btn-toggle {
    font-size: 11px; background: none; border: 0.5px solid rgba(11,42,74,0.1);
    border-radius: 6px; padding: 5px 12px;
    cursor: pointer; font-family: 'DM Sans', sans-serif; transition: color 0.2s, border-color 0.2s;
    color: rgba(11,42,74,0.4); white-space: nowrap;
}
.btn-toggle:hover { color: #0b2a4a; border-color: rgba(11,42,74,0.25); }

.empty-state { text-align: center; padding: 60px 20px; color: rgba(11,42,74,0.3); }
.empty-state p { font-size: 14px; margin-bottom: 20px; }

.product-name { font-weight: 500; margin-bottom: 2px; }
.product-meta { font-size: 11px; color: rgba(11,42,74,0.35); }
</style>

@if(session('success'))
    <div class="flash"><div class="flash-dot"></div>{{ session('success') }}</div>
@endif

<div class="page-header">
    <div style="font-size:13px; color:rgba(11,42,74,0.4);">{{ $products->count() }} produk</div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">+ Tambah Produk</a>
</div>

<table class="products-table">
    <thead>
        <tr>
            <th style="width:64px;">Foto</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="product-thumb" alt="{{ $product->name }}">
                @else
                    <div class="product-thumb-placeholder">📦</div>
                @endif
            </td>
            <td>
                <p class="product-name">{{ $product->name }}</p>
                <p class="product-meta">{{ $product->images->count() }} gambar tambahan · slug: {{ $product->slug }}</p>
            </td>
            <td style="font-weight:500; color:#c9a96e; white-space:nowrap;">{{ $product->getPriceFormatted() }}</td>
            <td>
                <span class="badge-active">
                    <span class="badge-dot {{ $product->is_active ? 'on' : 'off' }}"></span>
                    <span class="badge-text {{ $product->is_active ? 'on' : 'off' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </span>
            </td>
            <td>
                <div class="action-btns">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-edit">Edit</a>

                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-toggle">
                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Hapus produk \'{{ addslashes($product->name) }}\'? Semua gambar juga akan dihapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="empty-state">
                <p>Belum ada produk. Tambahkan produk pertama!</p>
                <a href="{{ route('admin.products.create') }}" class="btn-primary">+ Tambah Produk</a>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection