@extends('merchant.layouts.sidebar')
@section('page-title', 'Pengaturan')
@section('content')

<style>
.settings-layout{display:grid;grid-template-columns:200px 1fr;gap:24px;align-items:flex-start;}
@media(max-width:700px){.settings-layout{grid-template-columns:1fr;}}
.settings-nav{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);overflow:hidden;position:sticky;top:80px;}
.settings-nav-item{display:flex;align-items:center;gap:10px;padding:12px 16px;font-size:12px;color:rgba(11,42,74,.5);cursor:pointer;transition:all .15s;border-left:3px solid transparent;background:none;border-top:none;border-right:none;border-bottom:none;width:100%;font-family:'DM Sans',sans-serif;text-align:left;}
.settings-nav-item:hover{color:#0b2a4a;background:rgba(11,42,74,.03);}
.settings-nav-item.active{color:#0b2a4a;background:rgba(11,42,74,.04);border-left-color:#c9a96e;font-weight:500;}
.settings-nav-divider{height:.5px;background:rgba(11,42,74,.06);}
.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:28px;margin-bottom:20px;}
.card-title{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:20px;padding-bottom:14px;border-bottom:.5px solid rgba(11,42,74,.06);}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.form-input,.form-textarea{width:100%;padding:10px 13px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.form-input:focus,.form-textarea:focus{border-color:#c9a96e;}
.form-textarea{resize:vertical;min-height:90px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.btn-save{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:11px 28px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-save:hover{background:#0d3459;}
.field-error{font-size:12px;color:#c0392b;margin-top:4px;}
.flash-success{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:12px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;display:flex;align-items:center;gap:8px;}
.flash-error{background:#fdf0f0;border:.5px solid #f5c0c0;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;}

.logo-wrap{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
.logo-img{width:64px;height:64px;border-radius:50%;object-fit:cover;border:.5px solid rgba(11,42,74,.1);}
.logo-placeholder{width:64px;height:64px;border-radius:50%;background:rgba(11,42,74,.06);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:24px;color:#0b2a4a;border:.5px solid rgba(11,42,74,.1);}

.phone-hint{font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;line-height:1.5;}
.phone-preview{display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:6px 12px;background:#f0fff4;border:.5px solid #b2d9b2;border-radius:6px;font-size:12px;color:#2d6a2d;}
</style>

@php $activeTab = session('active_tab', 'profile'); @endphp

@if(session('success'))
    <div class="flash-success">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="flash-error">{{ $errors->first() }}</div>
@endif

<div class="settings-layout">
    <nav class="settings-nav">
        <button class="settings-nav-item {{ $activeTab === 'profile' ? 'active' : '' }}" onclick="showTab('profile',this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil
        </button>
        <div class="settings-nav-divider"></div>
        <button class="settings-nav-item {{ $activeTab === 'store' ? 'active' : '' }}" onclick="showTab('store',this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Info Toko
        </button>
        <div class="settings-nav-divider"></div>
        <button class="settings-nav-item {{ $activeTab === 'password' ? 'active' : '' }}" onclick="showTab('password',this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Password
        </button>
    </nav>

    <div>
        <div id="tab-profile" style="{{ $activeTab === 'profile' ? '' : 'display:none;' }}">
            <div class="card">
                <p class="card-title">Informasi Pribadi</p>
                <form action="{{ route('merchant.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="profile">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                            @error('name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                            @error('email') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP Pribadi</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                        <p class="phone-hint">Nomor pribadi kamu — tidak ditampilkan ke pembeli.</p>
                    </div>
                    <button type="submit" class="btn-save">Simpan</button>
                </form>
            </div>
        </div>

        <div id="tab-store" style="{{ $activeTab === 'store' ? '' : 'display:none;' }}">
            <div class="card">
                <p class="card-title">Logo Toko</p>
                <div class="logo-wrap">
                    @if($store->logo)
                        <img src="{{ asset($store->logo) }}" class="logo-img" alt="{{ $store->name }}">
                    @else
                        <div class="logo-placeholder">{{ strtoupper(substr($store->name,0,1)) }}</div>
                    @endif
                    <form action="{{ route('merchant.settings.logo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="logo" id="logoInput" accept="image/*" style="display:none;" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('logoInput').click()"
                            style="background:none;border:.5px solid rgba(11,42,74,.2);border-radius:8px;padding:9px 18px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.6);cursor:pointer;font-family:'DM Sans',sans-serif;">
                            Ganti Logo
                        </button>
                        <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:6px;">Max 1MB. JPG atau PNG.</p>
                    </form>
                </div>
            </div>

            <div class="card">
                <p class="card-title">Informasi Toko</p>
                <form action="{{ route('merchant.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab" value="store">
                    <div class="form-group">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" name="store_name" class="form-input" value="{{ old('store_name', $store->name) }}" required>
                    </div>
                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp Toko</label>
                            <x-phone-input name="store_phone" :value="old('store_phone', $store->phone ?? '')" placeholder="85xxxxxxxxx" />
                            <p style="font-size:11px;color:rgba(44,24,16,.35);margin-top:4px;">
                                Dipakai untuk tombol chat di halaman toko kamu.
                            </p>
                        </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Toko</label>
                        <textarea name="store_description" class="form-textarea">{{ old('store_description', $store->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn-save">Simpan Info Toko</button>
                </form>
            </div>
        </div>

        <div id="tab-password" style="{{ $activeTab === 'password' ? '' : 'display:none;' }}">
            <div class="card">
                <p class="card-title">Ubah Password</p>
                <form action="{{ route('merchant.settings.update') }}" method="POST">
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
                        @error('password') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn-save">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tab, el) {
    ['profile','store','password'].forEach(t => {
        document.getElementById('tab-'+t).style.display = t === tab ? 'block' : 'none';
    });
    document.querySelectorAll('.settings-nav-item').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

function updatePhonePreview(val) {
    const prev = document.getElementById('phonePreview');
    if (!prev) return;
    if (val.trim()) {
        prev.style.display = 'inline-flex';
        prev.querySelector('span') && (prev.querySelector('span').textContent = 'WA aktif: ' + val.trim());
    }
}
</script>

@endsection