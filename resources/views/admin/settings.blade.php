@extends('admin.layouts.sidebar')
@section('page-title', 'Pengaturan')
@section('content')

<style>
.settings-layout{display:grid;grid-template-columns:200px 1fr;gap:24px;}
@media(max-width:700px){.settings-layout{grid-template-columns:1fr;}}
.settings-nav{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);overflow:hidden;position:sticky;top:80px;}
.settings-nav-item{display:flex;align-items:center;gap:10px;padding:12px 16px;font-size:12px;color:rgba(11,42,74,.5);cursor:pointer;transition:all .15s;border-left:3px solid transparent;background:none;border-top:none;border-right:none;border-bottom:none;width:100%;font-family:'DM Sans',sans-serif;text-align:left;}
.settings-nav-item:hover{color:#0b2a4a;background:rgba(11,42,74,.03);}
.settings-nav-item.active{color:#0b2a4a;background:rgba(11,42,74,.04);border-left-color:#c9a96e;font-weight:500;}
.settings-nav-divider{height:.5px;background:rgba(11,42,74,.06);}
.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:28px;margin-bottom:20px;}
.card-title{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:20px;padding-bottom:14px;border-bottom:.5px solid rgba(11,42,74,.06);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:600px){.form-row{grid-template-columns:1fr;}}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.form-input{width:100%;padding:10px 13px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus{border-color:#c9a96e;}
.btn-save{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:11px 28px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-save:hover{background:#0d3459;}
.field-error{font-size:12px;color:#c0392b;margin-top:4px;}
.avatar-wrap{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
.avatar-img{width:64px;height:64px;border-radius:50%;object-fit:cover;border:.5px solid rgba(11,42,74,.1);}
.avatar-placeholder{width:64px;height:64px;border-radius:50%;background:rgba(201,169,110,.15);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:24px;color:#c9a96e;border:1px solid rgba(201,169,110,.3);}
</style>

@if(session('success'))
    <div class="admin-flash success" style="margin-bottom:20px;">{{ session('success') }}</div>
@endif

<div class="settings-layout">
    <nav class="settings-nav">
        <button class="settings-nav-item active" onclick="showTab('profile',this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil
        </button>
        <div class="settings-nav-divider"></div>
        <button class="settings-nav-item" onclick="showTab('password',this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Password
        </button>
        <div class="settings-nav-divider"></div>
        <button class="settings-nav-item" onclick="showTab('store',this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Toko Official
        </button>
    </nav>

    <div>
        <div id="tab-profile">
            <div class="card">
                <p class="card-title">Foto Profil</p>
                <div class="avatar-wrap">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}" class="avatar-img" alt="{{ $user->name }}">
                    @else
                        <div class="avatar-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
                    @endif
                    <form action="{{ route('admin.settings.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('avatarInput').click()"
                            style="background:none;border:.5px solid rgba(11,42,74,.2);border-radius:8px;padding:9px 18px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.6);cursor:pointer;font-family:'DM Sans',sans-serif;">
                            Ganti Foto
                        </button>
                        <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:6px;">Max 1MB.</p>
                    </form>
                </div>
            </div>

            <div class="card">
                <p class="card-title">Informasi Pribadi</p>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="profile">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                            @error('name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span style="font-size:10px;color:rgba(11,42,74,.3);text-transform:none;">(tidak dapat diubah)</span></label>
                            <input type="email" class="form-input" value="{{ $user->email }}" disabled style="background:rgba(11,42,74,.03);color:rgba(11,42,74,.4);cursor:not-allowed;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP Pribadi</label>
                        <x-phone-input name="profile_phone" :value="old('profile_phone', $user->phone ?? '')" placeholder="85xxxxxxxxx" />
                        <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;">Nomor pribadi admin — tidak ditampilkan ke publik.</p>
                    </div>
                    <button type="submit" class="btn-save">Simpan</button>
                </form>
            </div>
        </div>

        <div id="tab-password" style="display:none;">
            <div class="card">
                <p class="card-title">Ubah Password</p>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="password">
                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn-save">Ubah Password</button>
                </form>
            </div>
        </div>

        <div id="tab-store" style="display:none;">
            <div class="card">
                <p class="card-title">Info Toko Official</p>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="store">
                    <div class="form-group">
                        <label class="form-label">Nama Toko Official</label>
                        <input type="text" name="official_name" class="form-input"
                            value="{{ old('official_name', $officialName) }}" placeholder="Taku Official">
                        <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;">Tampil di halaman toko dan home.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Nomor WhatsApp Toko
                            <span style="font-size:10px;color:rgba(11,42,74,.3);text-transform:none;letter-spacing:0;font-weight:400;">(dengan kode negara)</span>
                        </label>
                        <x-phone-input name="official_phone" :value="old('official_phone', $officialPhone ?? '')" placeholder="85xxxxxxxxx" />
                        <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;">
                            Dipakai untuk tombol "Chat Support" di halaman toko official.
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Toko</label>
                        <textarea name="official_desc" class="form-input"
                                style="min-height:80px;resize:vertical;"
                                placeholder="Produk kurasi langsung dari tim Taku...">{{ old('official_desc', $officialDesc) }}</textarea>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                        <button type="submit" class="btn-save">Simpan</button>
                        <a href="{{ route('store.official') }}" target="_blank"
                           style="font-size:11px;color:rgba(11,42,74,.45);text-decoration:none;letter-spacing:.08em;text-transform:uppercase;border:.5px solid rgba(11,42,74,.12);border-radius:6px;padding:10px 14px;">
                            ↗ Lihat Toko
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tab, el) {
    ['profile','password','store'].forEach(t => {
        document.getElementById('tab-'+t).style.display = t===tab ? 'block' : 'none';
    });
    document.querySelectorAll('.settings-nav-item').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

@if(session('active_tab'))
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('[onclick*="showTab(\'{{ session('active_tab') }}\'"]');
        if (btn) btn.click();
    });
@endif
</script>

@endsection
