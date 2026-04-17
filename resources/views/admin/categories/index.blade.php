@extends('admin.layouts.sidebar')
@section('page-title', 'Kategori')
@section('content')

<style>
.page-layout { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: flex-start; }
.card { background: white; border-radius: 14px; border: 0.5px solid rgba(11,42,74,0.08); padding: 24px; }
.card-title { font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 20px; }
.cat-table { width: 100%; border-collapse: collapse; }
.cat-table th { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); font-weight: 400; padding: 10px 14px; text-align: left; border-bottom: 0.5px solid rgba(11,42,74,0.06); }
.cat-table td { padding: 12px 14px; font-size: 13px; color: #0b2a4a; border-bottom: 0.5px solid rgba(11,42,74,0.04); vertical-align: middle; }
.cat-table tr:last-child td { border-bottom: none; }
.cat-icon { font-size: 18px; line-height: 1; }
.dot-green { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #27ae60; }
.dot-gray  { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #bbb; }
.action-group { display: flex; gap: 6px; }
.btn-sm { font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 6px; padding: 5px 10px; cursor: pointer; font-family: 'DM Sans', sans-serif; border: 0.5px solid rgba(11,42,74,0.15); background: none; color: rgba(11,42,74,0.6); transition: all 0.2s; }
.btn-sm:hover { color: #0b2a4a; border-color: rgba(11,42,74,0.3); }
.btn-sm-danger { color: #c0392b; border-color: rgba(192,57,43,0.2); }
.btn-sm-danger:hover { background: #c0392b; color: white; border-color: #c0392b; }
.form-label { display: block; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.45); margin-bottom: 8px; }
.form-input { width: 100%; padding: 10px 12px; border: 0.5px solid rgba(11,42,74,0.15); border-radius: 8px; font-size: 13px; color: #0b2a4a; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.2s; background: white; box-sizing: border-box; }
.form-input:focus { border-color: #c9a96e; }
.form-hint { font-size: 11px; color: rgba(11,42,74,0.35); margin-top: 5px; }
.form-group { margin-bottom: 18px; }
.btn-submit { width: 100%; background: #0b2a4a; color: #f0ebe0; border: none; border-radius: 8px; padding: 11px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.2s; }
.btn-submit:hover { background: #0d3459; }
.empty-state { text-align: center; padding: 40px; color: rgba(11,42,74,0.3); font-size: 13px; }

.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(11,42,74,0.4); z-index: 100; align-items: center; justify-content: center; }
.modal-overlay.show { display: flex; }
.modal-box { background: white; border-radius: 16px; padding: 28px; width: 100%; max-width: 400px; }
.modal-title { font-size: 15px; font-weight: 500; color: #0b2a4a; margin-bottom: 20px; }
</style>

<div class="page-layout">
    <div class="card">
        <p class="card-title">Semua Kategori</p>

        @if(session('success'))
            <div style="background:#f0f7f0;border:0.5px solid #b2d9b2;border-radius:8px;padding:10px 14px;font-size:13px;color:#2d6a2d;margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        <table class="cat-table">
            <thead>
                <tr>
                    <th>Ikon</th>
                    <th>Nama</th>
                    <th>Produk</th>
                    <th>Status</th>
                    <th>Urutan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <form id="del-cat-{{ $cat->id }}" action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                </form>
                <form id="toggle-cat-{{ $cat->id }}" action="{{ route('admin.categories.toggle', $cat) }}" method="POST" style="display:none;">
                    @csrf
                </form>
                <tr>
                    <td><span class="cat-icon">{{ $cat->icon ?: '—' }}</span></td>
                    <td style="font-weight:500;">{{ $cat->name }}<br><span style="font-size:11px;color:rgba(11,42,74,0.4);">{{ $cat->slug }}</span></td>
                    <td>{{ $cat->products_count }}</td>
                    <td>
                        <button class="btn-sm" onclick="document.getElementById('toggle-cat-{{ $cat->id }}').submit()">
                            <span class="{{ $cat->is_active ? 'dot-green' : 'dot-gray' }}" style="margin-right:5px;"></span>
                            {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </td>
                    <td>{{ $cat->sort }}</td>
                    <td>
                        <div class="action-group">
                            <button class="btn-sm" onclick="openEdit({{ $cat->id }},'{{ addslashes($cat->name) }}','{{ $cat->icon }}',{{ $cat->sort }})">
                                Edit
                            </button>
                            <button class="btn-sm btn-sm-danger"
                                    onclick="if(confirm('Hapus kategori ini?')) document.getElementById('del-cat-{{ $cat->id }}').submit()">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-state">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <p class="card-title">Tambah Kategori</p>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Batik" required>
                @error('name') <p style="font-size:12px;color:#c0392b;margin-top:4px;">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Ikon (emoji)</label>
                <input type="text" name="icon" class="form-input" placeholder="Contoh: 👗" maxlength="4">
                <p class="form-hint">Pakai emoji satu karakter. Opsional.</p>
            </div>
            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="sort" class="form-input" placeholder="0" value="0" min="0">
                <p class="form-hint">Angka kecil tampil lebih dulu.</p>
            </div>
            <button type="submit" class="btn-submit">Tambah Kategori</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <p class="modal-title">Edit Kategori</p>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" id="editName" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Ikon (emoji)</label>
                <input type="text" name="icon" id="editIcon" class="form-input" maxlength="4">
            </div>
            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort" id="editSort" class="form-input">
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeEdit()" style="flex:1;padding:10px;border:0.5px solid rgba(11,42,74,0.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, name, icon, sort) {
    document.getElementById('editName').value = name;
    document.getElementById('editIcon').value = icon;
    document.getElementById('editSort').value = sort;
    document.getElementById('editForm').action = '/admin/categories/' + id;
    document.getElementById('editModal').classList.add('show');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('show');
}
</script>

@endsection
