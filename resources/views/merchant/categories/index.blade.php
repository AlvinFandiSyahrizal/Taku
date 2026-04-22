@extends('merchant.layouts.sidebar')
@section('page-title', 'Kategori Toko')
@section('content')

<style>
.page-layout { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: flex-start; }
.card { background: white; border-radius: 14px; border: 0.5px solid rgba(11,42,74,0.08); padding: 24px; }
.card-title { font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 20px; }

.cat-table { width: 100%; border-collapse: collapse; }
.cat-table th { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(11,42,74,0.4); font-weight: 400; padding: 10px 14px; text-align: left; border-bottom: 0.5px solid rgba(11,42,74,0.06); }
.cat-table td { padding: 12px 14px; font-size: 13px; color: #0b2a4a; border-bottom: 0.5px solid rgba(11,42,74,0.04); vertical-align: middle; }
.cat-table tr:last-child td { border-bottom: none; }
.row-parent td { background: rgba(11,42,74,0.015); }
.row-child td { background: white; }

.child-indent { display: flex; align-items: center; gap: 8px; }
.child-indent::before { content: ''; display: inline-block; width: 18px; height: 1px; background: rgba(11,42,74,0.15); margin-left: 8px; flex-shrink: 0; }

.cat-icon { font-size: 18px; line-height: 1; }
.dot-green { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #27ae60; }
.dot-gray  { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #bbb; }

.badge-parent { display: inline-block; font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; background: rgba(11,42,74,0.06); color: rgba(11,42,74,0.5); border-radius: 4px; padding: 2px 6px; margin-left: 6px; }
.badge-sub { display: inline-block; font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; background: rgba(201,169,110,0.12); color: rgba(160,120,50,0.8); border-radius: 4px; padding: 2px 6px; margin-left: 6px; }
.children-count { display: inline-block; font-size: 10px; background: rgba(11,42,74,0.07); color: rgba(11,42,74,0.5); border-radius: 20px; padding: 1px 7px; margin-left: 4px; }

.action-group { display: flex; gap: 6px; flex-wrap: wrap; }
.btn-sm { font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 6px; padding: 5px 10px; cursor: pointer; font-family: 'DM Sans', sans-serif; border: 0.5px solid rgba(11,42,74,0.15); background: none; color: rgba(11,42,74,0.6); transition: all 0.2s; white-space: nowrap; }
.btn-sm:hover { color: #0b2a4a; border-color: rgba(11,42,74,0.3); }
.btn-sm-danger { color: #c0392b; border-color: rgba(192,57,43,0.2); }
.btn-sm-danger:hover { background: #c0392b; color: white; border-color: #c0392b; }
.btn-sm-accent { color: #8b6914; border-color: rgba(201,169,110,0.4); background: rgba(201,169,110,0.07); }
.btn-sm-accent:hover { background: rgba(201,169,110,0.18); border-color: #c9a96e; }

.form-label { display: block; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(11,42,74,0.45); margin-bottom: 8px; }
.form-input { width: 100%; padding: 10px 12px; border: 0.5px solid rgba(11,42,74,0.15); border-radius: 8px; font-size: 13px; color: #0b2a4a; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.2s; background: white; box-sizing: border-box; }
.form-input:focus { border-color: #c9a96e; }
.form-hint { font-size: 11px; color: rgba(11,42,74,0.35); margin-top: 5px; }
.form-group { margin-bottom: 18px; }
.btn-submit { width: 100%; background: #0b2a4a; color: #f0ebe0; border: none; border-radius: 8px; padding: 11px; font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.2s; }
.btn-submit:hover { background: #0d3459; }

.tab-row { display: flex; gap: 0; border: 0.5px solid rgba(11,42,74,0.12); border-radius: 8px; overflow: hidden; margin-bottom: 20px; }
.tab-btn { flex: 1; padding: 8px; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; background: none; border: none; cursor: pointer; color: rgba(11,42,74,0.45); font-family: 'DM Sans', sans-serif; transition: all 0.18s; }
.tab-btn.active { background: #0b2a4a; color: #f0ebe0; }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(11,42,74,0.4); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(2px); }
.modal-overlay.show { display: flex; }
.modal-box { background: white; border-radius: 16px; padding: 28px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(11,42,74,0.18); }
.modal-title { font-size: 15px; font-weight: 500; color: #0b2a4a; margin-bottom: 4px; }
.modal-subtitle { font-size: 12px; color: rgba(11,42,74,0.4); margin-bottom: 22px; }

.alert-success { background: #f0f7f0; border: 0.5px solid #b2d9b2; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #2d6a2d; margin-bottom: 16px; }
.empty-state { text-align: center; padding: 40px; color: rgba(11,42,74,0.3); font-size: 13px; }

.info-box { background: rgba(201,169,110,0.07); border: 0.5px solid rgba(201,169,110,0.3); border-radius: 8px; padding: 12px 14px; font-size: 12px; color: rgba(11,42,74,0.6); margin-bottom: 20px; line-height: 1.6; }
.info-box strong { color: #8b6914; }
</style>

<div class="page-layout">

    {{-- ══ LEFT: TABLE ══ --}}
    <div class="card">
        <p class="card-title">Kategori Toko — {{ $store->name }}</p>

        <div class="info-box">
            <strong>Kategori Toko Sendiri</strong> — Kategori ini hanya tampil di toko kamu dan tidak terlihat oleh merchant lain. Produk kamu bisa dipilihkan ke kategori ini saat buat atau edit produk.
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
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
                    <form id="del-cat-{{ $cat->id }}" action="{{ route('merchant.categories.destroy', $cat) }}" method="POST" style="display:none;">
                        @csrf @method('DELETE')
                    </form>
                    <form id="toggle-cat-{{ $cat->id }}" action="{{ route('merchant.categories.toggle', $cat) }}" method="POST" style="display:none;">
                        @csrf
                    </form>

                    <tr class="row-parent">
                        <td><span class="cat-icon">{{ $cat->icon ?: '—' }}</span></td>
                        <td style="font-weight:600;">
                            {{ $cat->name }}
                            <span class="badge-parent">Utama</span>
                            @if($cat->children && $cat->children->count() > 0)
                                <span class="children-count">{{ $cat->children->count() }} sub</span>
                            @endif
                            <br>
                            <span style="font-size:11px;color:rgba(11,42,74,0.35);font-weight:400;">{{ $cat->slug }}</span>
                        </td>
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
                                <button class="btn-sm btn-sm-accent" onclick="openAddSub({{ $cat->id }}, '{{ addslashes($cat->name) }}')">+ Sub</button>
                                <button class="btn-sm" onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->icon) }}', {{ $cat->sort }}, {{ $cat->parent_id ?? 'null' }}, null)">Edit</button>
                                <button class="btn-sm btn-sm-danger" onclick="confirmDelete('del-cat-{{ $cat->id }}', {{ $cat->children ? $cat->children->count() : 0 }})">Hapus</button>
                            </div>
                        </td>
                    </tr>

                    @if($cat->children && $cat->children->count() > 0)
                        @foreach($cat->children as $child)
                            <form id="del-cat-{{ $child->id }}" action="{{ route('merchant.categories.destroy', $child) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                            <form id="toggle-cat-{{ $child->id }}" action="{{ route('merchant.categories.toggle', $child) }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                            <tr class="row-child">
                                <td style="padding-left:22px;"><span class="cat-icon" style="font-size:15px;">{{ $child->icon ?: '—' }}</span></td>
                                <td>
                                    <div class="child-indent">
                                        <div>
                                            <span style="font-weight:500;">{{ $child->name }}</span>
                                            <span class="badge-sub">Sub</span>
                                            <br>
                                            <span style="font-size:11px;color:rgba(11,42,74,0.35);">{{ $child->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $child->products_count ?? 0 }}</td>
                                <td>
                                    <button class="btn-sm" onclick="document.getElementById('toggle-cat-{{ $child->id }}').submit()">
                                        <span class="{{ $child->is_active ? 'dot-green' : 'dot-gray' }}" style="margin-right:5px;"></span>
                                        {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td>{{ $child->sort }}</td>
                                <td>
                                    <div class="action-group">
                                        <button class="btn-sm" onclick="openEdit({{ $child->id }}, '{{ addslashes($child->name) }}', '{{ addslashes($child->icon) }}', {{ $child->sort }}, {{ $child->parent_id }}, '{{ addslashes($cat->name) }}')">Edit</button>
                                        <button class="btn-sm btn-sm-danger" onclick="confirmDelete('del-cat-{{ $child->id }}', 0)">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                @empty
                    <tr><td colspan="6" class="empty-state">Belum ada kategori. Tambah di panel kanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ══ RIGHT: FORM ══ --}}
    <div class="card">
        <div class="tab-row">
            <button class="tab-btn active" onclick="switchTab('parent')" id="tab-btn-parent">+ Kategori Utama</button>
            <button class="tab-btn" onclick="switchTab('sub')" id="tab-btn-sub">+ Sub-Kategori</button>
        </div>

        <div class="tab-panel active" id="tab-parent">
            <form action="{{ route('merchant.categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="">
                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" class="form-input" placeholder="Contoh: Batik" required value="{{ old('name') }}">
                    @error('name') <p style="font-size:12px;color:#c0392b;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ikon (emoji)</label>
                    <input type="text" name="icon" class="form-input" placeholder="Contoh: 👗" maxlength="4">
                    <p class="form-hint">Opsional. Pakai emoji satu karakter.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort" class="form-input" value="0" min="0">
                    <p class="form-hint">Angka kecil tampil lebih dulu.</p>
                </div>
                <button type="submit" class="btn-submit">Tambah Kategori Utama</button>
            </form>
        </div>

        <div class="tab-panel" id="tab-sub">
            <form action="{{ route('merchant.categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kategori Utama (Parent) *</label>
                    <select name="parent_id" class="form-input" required>
                        <option value="">— Pilih kategori utama —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Sub-Kategori *</label>
                    <input type="text" name="name" class="form-input" placeholder="Contoh: Batik Tulis" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ikon (emoji)</label>
                    <input type="text" name="icon" class="form-input" placeholder="Contoh: 🎨" maxlength="4">
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort" class="form-input" value="0" min="0">
                </div>
                <button type="submit" class="btn-submit">Tambah Sub-Kategori</button>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <p class="modal-title" id="editModalTitle">Edit Kategori</p>
        <p class="modal-subtitle" id="editModalSubtitle"></p>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group" id="editParentGroup" style="display:none;">
                <label class="form-label">Kategori Utama (Parent)</label>
                <select name="parent_id" id="editParentId" class="form-input">
                    <option value="">— Jadikan kategori utama —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
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
                <input type="number" name="sort" id="editSort" class="form-input" min="0">
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeEdit()" style="flex:1;padding:10px;border:0.5px solid rgba(11,42,74,0.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;color:rgba(11,42,74,0.6);">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Sub Modal --}}
<div class="modal-overlay" id="addSubModal">
    <div class="modal-box">
        <p class="modal-title">Tambah Sub-Kategori</p>
        <p class="modal-subtitle" id="addSubParentLabel"></p>
        <form action="{{ route('merchant.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" id="addSubParentId">
            <div class="form-group">
                <label class="form-label">Nama Sub-Kategori *</label>
                <input type="text" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Ikon (emoji)</label>
                <input type="text" name="icon" class="form-input" maxlength="4">
            </div>
            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="sort" class="form-input" value="0" min="0">
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeAddSub()" style="flex:1;padding:10px;border:0.5px solid rgba(11,42,74,0.15);border-radius:8px;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;color:rgba(11,42,74,0.6);">Batal</button>
                <button type="submit" class="btn-submit" style="flex:2;">Tambah</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('tab-btn-' + tab).classList.add('active');
}
function openEdit(id, name, icon, sort, parentId, parentName) {
    document.getElementById('editName').value  = name;
    document.getElementById('editIcon').value  = icon || '';
    document.getElementById('editSort').value  = sort;
    document.getElementById('editForm').action = '/merchant/categories/' + id;
    const parentGroup = document.getElementById('editParentGroup');
    const parentSel   = document.getElementById('editParentId');
    if (parentId) {
        parentGroup.style.display = 'block';
        parentSel.value = parentId;
        document.getElementById('editModalTitle').textContent = 'Edit Sub-Kategori';
        document.getElementById('editModalSubtitle').textContent = 'Di bawah: ' + (parentName || '');
    } else {
        parentGroup.style.display = 'none';
        parentSel.value = '';
        document.getElementById('editModalTitle').textContent = 'Edit Kategori Utama';
        document.getElementById('editModalSubtitle').textContent = '';
    }
    document.getElementById('editModal').classList.add('show');
}
function closeEdit() { document.getElementById('editModal').classList.remove('show'); }
function openAddSub(parentId, parentName) {
    document.getElementById('addSubParentId').value = parentId;
    document.getElementById('addSubParentLabel').textContent = 'Kategori utama: ' + parentName;
    document.getElementById('addSubModal').classList.add('show');
}
function closeAddSub() { document.getElementById('addSubModal').classList.remove('show'); }
function confirmDelete(formId, childrenCount) {
    const msg = childrenCount > 0
        ? 'Kategori ini punya ' + childrenCount + ' sub-kategori. Hapus semuanya?'
        : 'Hapus kategori ini?';
    if (confirm(msg)) document.getElementById(formId).submit();
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('show'); });
});
</script>

@endsection