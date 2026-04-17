@extends('merchant.layouts.sidebar')

@section('page-title', 'Produk Saya')

@section('content')

<style>
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
.btn-add { background: #0b2a4a; color: #f0ebe0; text-decoration: none; border-radius: 8px; padding: 10px 20px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s; }
.btn-add:hover { background: #0d3459; }

.products-table { width: 100%; border-collapse: collapse; background: white; border-radius: 14px; overflow: hidden; border: 0.5px solid rgba(11,42,74,0.08); }
.products-table th { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); font-weight: 400; padding: 14px 20px; text-align: left; border-bottom: 0.5px solid rgba(11,42,74,0.06); }
.products-table td { padding: 12px 20px; font-size: 13px; color: #0b2a4a; border-bottom: 0.5px solid rgba(11,42,74,0.04); vertical-align: middle; }
.products-table tr:last-child td { border-bottom: none; }

.product-thumb { width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 0.5px solid rgba(11,42,74,0.08); }
.product-thumb-empty { width: 48px; height: 48px; border-radius: 8px; background: #f5f1e8; border: 0.5px solid rgba(11,42,74,0.08); display: flex; align-items: center; justify-content: center; }

.status-dot { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; }
.dot { width: 6px; height: 6px; border-radius: 50%; }
.dot-green { background: #27ae60; }
.dot-gray  { background: #bbb; }

.action-group { display: flex; gap: 8px; }
.btn-edit { font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: #0b2a4a; text-decoration: none; border: 0.5px solid rgba(11,42,74,0.15); border-radius: 6px; padding: 5px 12px; transition: all 0.2s; }
.btn-edit:hover { border-color: #0b2a4a; }
.btn-delete { font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: #c0392b; background: none; border: 0.5px solid rgba(192,57,43,0.3); border-radius: 6px; padding: 5px 12px; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
.btn-delete:hover { background: #c0392b; color: white; }

.toggle-form { display: inline; }
.btn-toggle { background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }

.empty-state { text-align: center; padding: 60px 40px; }
.empty-title { font-family: 'Cormorant Garamond', serif; font-size: 24px; color: rgba(11,42,74,0.3); margin-bottom: 8px; }
.empty-sub { font-size: 13px; color: rgba(11,42,74,0.35); margin-bottom: 20px; }
</style>

<div class="page-header">
    <div></div>
    <a href="{{ route('merchant.products.create') }}" class="btn-add">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Produk
    </a>
</div>

<table class="products-table">
    <thead>
        <tr>
            <th></th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <form id="del-{{ $product->id }}" action="{{ route('merchant.products.destroy', $product) }}" method="POST" style="display:none;">
            @csrf @method('DELETE')
        </form>
        <tr>
            <td>
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="product-thumb" alt="{{ $product->name }}">
                @else
                    <div class="product-thumb-empty">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(11,42,74,0.2)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                @endif
            </td>
            <td>
                <p style="font-weight:500;">{{ $product->name }}</p>
                @if($product->desc_id)
                    <p style="font-size:11px; color:rgba(11,42,74,0.4); margin-top:2px;">{{ Str::limit($product->desc_id, 50) }}</p>
                @endif
            </td>
            <td>{{ $product->getPriceFormatted() }}</td>
            <td>
                <form action="{{ route('merchant.products.toggle', $product) }}" method="POST" class="toggle-form">
                    @csrf
                    <button type="submit" class="btn-toggle">
                        <span class="status-dot">
                            <span class="dot {{ $product->is_active ? 'dot-green' : 'dot-gray' }}"></span>
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </button>
                </form>
            </td>
            <td>
                <div class="action-group">
                    <a href="{{ route('merchant.products.edit', $product) }}" class="btn-edit">Edit</a>
                    <button class="btn-delete" onclick="if(confirm('Hapus produk ini?')) document.getElementById('del-{{ $product->id }}').submit()">Hapus</button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">
                <div class="empty-state">
                    <p class="empty-title">Belum ada produk</p>
                    <p class="empty-sub">Mulai tambahkan produk pertama kamu ke toko.</p>
                    <a href="{{ route('merchant.products.create') }}" style="background:#0b2a4a; color:#f0ebe0; text-decoration:none; border-radius:8px; padding:10px 24px; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; display:inline-flex;">
                        Tambah Produk Pertama
                    </a>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection