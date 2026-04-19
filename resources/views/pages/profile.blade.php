@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang','id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.profile-wrap{max-width:860px;margin:56px auto 80px;padding:0 24px;font-family:'DM Sans',sans-serif;}
.page-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:6px;}
.page-title{font-family:'Cormorant Garamond',serif;font-size:34px;font-weight:400;color:#0b2a4a;margin-bottom:32px;}
.profile-layout{display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:flex-start;}
@media(max-width:700px){.profile-layout{grid-template-columns:1fr;}}

.profile-nav{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);overflow:hidden;position:sticky;top:84px;}
.profile-nav-item{display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:13px;color:rgba(11,42,74,.55);text-decoration:none;transition:all .15s;border-left:3px solid transparent;}
.profile-nav-item:hover{color:#0b2a4a;background:rgba(11,42,74,.03);}
.profile-nav-item.active{color:#0b2a4a;background:rgba(11,42,74,.04);border-left-color:#c9a96e;font-weight:500;}
.profile-nav-divider{height:.5px;background:rgba(11,42,74,.06);}

.card{background:white;border-radius:14px;border:.5px solid rgba(11,42,74,.08);padding:28px;margin-bottom:20px;}
.card-title{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:20px;padding-bottom:14px;border-bottom:.5px solid rgba(11,42,74,.06);}

.avatar-wrap{display:flex;align-items:center;gap:20px;margin-bottom:24px;}
.avatar-img{width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid rgba(11,42,74,.1);}
.avatar-placeholder{width:72px;height:72px;border-radius:50%;background:rgba(11,42,74,.06);border:.5px solid rgba(11,42,74,.1);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:28px;color:#0b2a4a;}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:600px){.form-row{grid-template-columns:1fr;}}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.form-input,.form-textarea{width:100%;padding:10px 13px;border:.5px solid rgba(11,42,74,.15);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;}
.form-input:focus,.form-textarea:focus{border-color:#c9a96e;}
.form-textarea{resize:vertical;min-height:80px;}
.btn-save{background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;padding:11px 28px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.btn-save:hover{background:#0d3459;}
.field-error{font-size:12px;color:#c0392b;margin-top:4px;}
.flash-success{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:12px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;}
.flash-error-box{background:#fdf0f0;border:.5px solid #f5c0c0;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;}

.address-card{border:.5px solid rgba(11,42,74,.08);border-radius:10px;padding:16px 18px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:flex-start;gap:12px;}
.address-card.default{border-color:rgba(201,169,110,.4);background:rgba(201,169,110,.04);}
.address-label{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:#c9a96e;font-weight:500;margin-bottom:4px;}
.address-name{font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:2px;}
.address-text{font-size:12px;color:rgba(11,42,74,.5);line-height:1.6;}
.address-actions{display:flex;gap:6px;flex-shrink:0;}
.btn-addr{font-size:10px;letter-spacing:.08em;text-transform:uppercase;border-radius:6px;padding:5px 10px;cursor:pointer;font-family:'DM Sans',sans-serif;border:.5px solid rgba(11,42,74,.15);background:none;color:rgba(11,42,74,.5);transition:all .2s;white-space:nowrap;}
.btn-addr:hover{color:#0b2a4a;border-color:rgba(11,42,74,.3);}
.btn-addr-danger:hover{color:#c0392b;border-color:rgba(192,57,43,.3);}
.default-badge{display:inline-block;padding:2px 8px;background:rgba(201,169,110,.12);color:#b8955a;border-radius:100px;font-size:10px;letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px;}
</style>

<div class="profile-wrap">
    <p class="page-label">Taku</p>
    <h1 class="page-title">Akun Saya</h1>

    @if(session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if($errors->any() && !session('success'))
        <div class="flash-error-box">{{ $errors->first() }}</div>
    @endif

    <div class="profile-layout">

        <nav class="profile-nav">
            <a href="#profil" class="profile-nav-item active" onclick="showTab('profil',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Profil
            </a>
            <div class="profile-nav-divider"></div>
            <a href="#alamat" class="profile-nav-item" onclick="showTab('alamat',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Alamat
            </a>
            <div class="profile-nav-divider"></div>
            <a href="#password" class="profile-nav-item" onclick="showTab('password',this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Password
            </a>
            <div class="profile-nav-divider"></div>
            <a href="{{ route('orders.index') }}" class="profile-nav-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Pesanan Saya
            </a>
            <div class="profile-nav-divider"></div>
            <a href="{{ route('wishlist.index') }}" class="profile-nav-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                Wishlist
            </a>
        </nav>

        <div>
            <div id="tab-profil">
                <div class="card">
                    <p class="card-title">Foto Profil</p>
                    <div class="avatar-wrap">
                        @if($user->avatar)
                            <img src="{{ asset($user->avatar) }}" class="avatar-img" alt="{{ $user->name }}">
                        @else
                            <div class="avatar-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
                        @endif
                        <div>
                            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                <button type="button" onclick="document.getElementById('avatarInput').click()"
                                    style="background:none;border:.5px solid rgba(11,42,74,.2);border-radius:8px;padding:9px 18px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.6);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;"
                                    onmouseover="this.style.color='#0b2a4a'" onmouseout="this.style.color='rgba(11,42,74,.6)'">
                                    Ganti Foto
                                </button>
                            </form>
                            <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:6px;">Max 1MB. JPG atau PNG.</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <p class="card-title">Informasi Pribadi</p>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                                @error('name') <p class="field-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email <span style="font-size:10px;color:rgba(11,42,74,0.3);text-transform:none;letter-spacing:0;">(tidak dapat diubah)</span></label>
                                <input type="email" class="form-input" value="{{ $user->email }}" disabled
                                    style="background:rgba(11,42,74,0.03);color:rgba(11,42,74,0.4);cursor:not-allowed;">
                            </div>
                        </div>
                            <div class="form-group">
                                <label class="form-label">Nomor HP / WhatsApp</label>
                                <x-phone-input name="phone" :value="old('phone', $user->phone ?? '')" placeholder="85xxxxxxxxx" />
                                <p style="font-size:11px;color:rgba(11,42,74,.35);margin-top:4px;">
                                    Contoh: 🇮🇩 +62 85xxxxxxxxx
                                </p>
                            </div>
                        <div class="form-group">
                            <label class="form-label">Bio (opsional)</label>
                            <textarea name="bio" class="form-textarea" placeholder="Ceritakan sedikit tentang kamu...">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <button type="submit" class="btn-save">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <div id="tab-alamat" style="display:none;">
                <div class="card">
                    <p class="card-title">Alamat Tersimpan</p>

                    @forelse($addresses as $addr)
                    <div class="address-card {{ $addr->is_default ? 'default' : '' }}">
                        <div>
                            @if($addr->is_default)
                                <span class="default-badge">Utama</span><br>
                            @endif
                            <p class="address-label">{{ $addr->label }}</p>
                            <p class="address-name">{{ $addr->recipient }} · {{ $addr->phone }}</p>
                            <p class="address-text">{{ $addr->address }}@if($addr->city), {{ $addr->city }}@endif</p>
                        </div>
                        <div class="address-actions">
                            @if(!$addr->is_default)
                            <form action="{{ route('addresses.default', $addr) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-addr">Jadikan Utama</button>
                            </form>
                            @endif
                            <form action="{{ route('addresses.destroy', $addr) }}" method="POST"
                                  onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-addr btn-addr-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p style="font-size:13px;color:rgba(11,42,74,.4);margin-bottom:20px;">Belum ada alamat tersimpan.</p>
                    @endforelse
                </div>

                <div class="card">
                    <p class="card-title">Tambah Alamat Baru</p>
                    <form action="{{ route('addresses.store') }}" method="POST" id="addAddrForm">
                        @csrf

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Label *</label>
                                <input type="text" name="label" class="form-input"
                                    placeholder="Rumah / Kantor / dll" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Penerima *</label>
                                <input type="text" name="recipient" class="form-input"
                                    placeholder="Nama lengkap penerima" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. HP Penerima *</label>
                            @php
                                $addrPhoneVal = '';
                                $addrPhoneCode = '+62';
                                $addrPhoneNum  = '';
                            @endphp
                            <div style="display:flex;border:.5px solid rgba(11,42,74,.15);border-radius:8px;overflow:hidden;">
                                <select id="addrPhoneCode"
                                        style="padding:9px 8px;border:none;outline:none;background:#f9f7f4;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;border-right:.5px solid rgba(11,42,74,.1);flex-shrink:0;cursor:pointer;"
                                        onchange="combineAddrPhone()">
                                    <option value="+62">🇮🇩 +62</option>
                                    <option value="+60">🇲🇾 +60</option>
                                    <option value="+65">🇸🇬 +65</option>
                                    <option value="+63">🇵🇭 +63</option>
                                    <option value="+66">🇹🇭 +66</option>
                                    <option value="+84">🇻🇳 +84</option>
                                    <option value="+1">🇺🇸 +1</option>
                                </select>
                                <input type="text" id="addrPhoneNum"
                                    style="flex:1;padding:9px 12px;border:none;outline:none;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;background:white;"
                                    placeholder="85xxxxxxxxx"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');combineAddrPhone()"
                                    required>
                            </div>
                            <input type="hidden" name="phone" id="addrPhoneFull">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Provinsi *</label>
                            <select name="province_code" id="addrProvince" class="form-input"
                                    onchange="addrLoadRegencies(this.value)" required>
                                <option value="">— Pilih Provinsi —</option>
                            </select>
                            <input type="hidden" name="province_name" id="addrProvinceName">
                            <p id="addrLoadingProvince" style="font-size:11px;color:rgba(11,42,74,.4);margin-top:4px;display:none;">Memuat...</p>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Kabupaten / Kota *</label>
                                <select name="regency_code" id="addrRegency" class="form-input"
                                        onchange="addrLoadDistricts(this.value)" required disabled>
                                    <option value="">— Pilih provinsi dulu —</option>
                                </select>
                                <input type="hidden" name="regency_name" id="addrRegencyName">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kecamatan *</label>
                                <select name="district_code" id="addrDistrict" class="form-input"
                                        onchange="addrLoadVillages(this.value)" required disabled>
                                    <option value="">— Pilih kabupaten dulu —</option>
                                </select>
                                <input type="hidden" name="district_name" id="addrDistrictName">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Kelurahan / Desa</label>
                                <select name="village_code" id="addrVillage" class="form-input" disabled>
                                    <option value="">— Pilih kecamatan dulu —</option>
                                </select>
                                <input type="hidden" name="village_name" id="addrVillageName">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-input"
                                    placeholder="12345" maxlength="5"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Jalan / Gedung *</label>
                            <input type="text" name="street" class="form-input"
                                placeholder="Jl. Sudirman No. 10, RT 001/002" required>
                        </div>

                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                            <input type="checkbox" name="is_default" id="isDefault" style="accent-color:#0b2a4a;">
                            <label for="isDefault" style="font-size:13px;color:rgba(11,42,74,.6);cursor:pointer;">
                                Jadikan alamat utama
                            </label>
                        </div>

                        <button type="submit" class="btn-save">Simpan Alamat</button>
                    </form>
                </div>
            </div>



            <div id="tab-password" style="display:none;">
                <div class="card">
                    <p class="card-title">Ubah Password</p>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
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
        </div>
    </div>
</div>


<script>
function combineAddrPhone() {
    const code = document.getElementById('addrPhoneCode').value;
    const num  = document.getElementById('addrPhoneNum').value.replace(/\D/g,'');
    document.getElementById('addrPhoneFull').value = num ? (code + num) : '';
}
document.getElementById('addAddrForm').addEventListener('submit', combineAddrPhone);

async function addrFetch(path) {
    try {
        const r = await fetch('/api/wilayah/' + path);
        const j = await r.json();
        return j.data ?? [];
    } catch { return []; }
}

function addrSetOptions(id, data, valKey, labelKey, placeholder) {
    const sel = document.getElementById(id);
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach(item => {
        const o = document.createElement('option');
        o.value = item[valKey];
        o.textContent = item[labelKey];
        sel.appendChild(o);
    });
    sel.disabled = data.length === 0;
}

(async function() {
    document.getElementById('addrLoadingProvince').style.display = 'block';
    const data = await addrFetch('provinces');
    document.getElementById('addrLoadingProvince').style.display = 'none';
    addrSetOptions('addrProvince', data, 'code', 'name', '— Pilih Provinsi —');
})();

async function addrLoadRegencies(code) {
    const sel = document.getElementById('addrProvince');
    document.getElementById('addrProvinceName').value =
        sel.options[sel.selectedIndex]?.text || '';

    addrSetOptions('addrRegency',  [], 'code', 'name', '— Memuat... —');
    addrSetOptions('addrDistrict', [], 'code', 'name', '— Pilih kabupaten dulu —');
    addrSetOptions('addrVillage',  [], 'code', 'name', '— Pilih kecamatan dulu —');
    document.getElementById('addrDistrict').disabled = true;
    document.getElementById('addrVillage').disabled  = true;

    if (!code) { document.getElementById('addrRegency').disabled = true; return; }
    const data = await addrFetch('regencies/' + code);
    addrSetOptions('addrRegency', data, 'code', 'name', '— Pilih Kabupaten/Kota —');
}

async function addrLoadDistricts(code) {
    const sel = document.getElementById('addrRegency');
    document.getElementById('addrRegencyName').value =
        sel.options[sel.selectedIndex]?.text || '';

    addrSetOptions('addrDistrict', [], 'code', 'name', '— Memuat... —');
    addrSetOptions('addrVillage',  [], 'code', 'name', '— Pilih kecamatan dulu —');
    document.getElementById('addrVillage').disabled = true;

    if (!code) { document.getElementById('addrDistrict').disabled = true; return; }
    const data = await addrFetch('districts/' + code);
    addrSetOptions('addrDistrict', data, 'code', 'name', '— Pilih Kecamatan —');
}

async function addrLoadVillages(code) {
    const sel = document.getElementById('addrDistrict');
    document.getElementById('addrDistrictName').value =
        sel.options[sel.selectedIndex]?.text || '';

    if (!code) { document.getElementById('addrVillage').disabled = true; return; }
    const data = await addrFetch('villages/' + code);
    addrSetOptions('addrVillage', data, 'code', 'name', '— Pilih Kelurahan/Desa —');
}

document.getElementById('addrVillage').addEventListener('change', function() {
    document.getElementById('addrVillageName').value =
        this.options[this.selectedIndex]?.text || '';
});
</script>


<script>

function showTab(tab, el) {
    ['profil','alamat','password'].forEach(t => {
        const tabEl = document.getElementById('tab-'+t);
        if (tabEl) tabEl.style.display = t === tab ? 'block' : 'none';
    });
    document.querySelectorAll('.profile-nav-item').forEach(a => a.classList.remove('active'));
    if (el) el.classList.add('active');
    history.replaceState(null, '', '#' + tab);
}

document.addEventListener('DOMContentLoaded', function() {
    const hash = location.hash.replace('#','');
    const validTabs = ['profil','alamat','password'];
    if (validTabs.includes(hash)) {
        const navEl = document.getElementById('nav-' + hash);
        showTab(hash, navEl);
    }
});
</script>

@endsection
