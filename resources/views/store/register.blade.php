<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
        {{-- <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script> --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Toko — Taku</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'DM Sans',sans-serif;background:#f5f1e8;color:#2c1810;min-height:100vh;padding:40px 20px;}
    .container{width:100%;max-width:600px;margin:0 auto;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:rgba(44,24,16,.45);text-decoration:none;margin-bottom:24px;letter-spacing:.06em;transition:color .2s;}
    .back-link:hover{color:#2c1810;}
    .card{background:white;border-radius:16px;border:.5px solid rgba(44,24,16,.08);padding:40px;}
    .card-eyebrow{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c96a3d;margin-bottom:12px;}
    .card-title{font-family:'Cormorant Garamond',serif;font-weight:300;font-size:28px;color:#2c1810;margin-bottom:8px;}
    .card-sub{font-size:13px;color:rgba(44,24,16,.5);margin-bottom:28px;line-height:1.7;}
    .steps{display:flex;margin-bottom:28px;border:.5px solid rgba(44,24,16,.1);border-radius:8px;overflow:hidden;}
    .step{flex:1;padding:8px;text-align:center;font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:rgba(44,24,16,.35);background:none;border-right:.5px solid rgba(44,24,16,.08);}
    .step:last-child{border-right:none;}
    .step.done{background:rgba(201,106,61,.06);color:#c96a3d;}
    .form-group{margin-bottom:18px;}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;}
    .form-label{display:block;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(44,24,16,.45);margin-bottom:7px;}
    .form-label span{color:#c96a3d;}
    .form-input,.form-textarea,.form-select{width:100%;padding:11px 14px;border:.5px solid rgba(44,24,16,.15);border-radius:8px;font-size:13px;color:#2c1810;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
    .form-input:focus,.form-textarea:focus,.form-select:focus{border-color:#c96a3d;}
    .form-select:disabled{background:#f5f1e8;color:rgba(44,24,16,.4);cursor:not-allowed;}
    .form-textarea{resize:vertical;min-height:100px;}
    .form-hint{font-size:11px;color:rgba(44,24,16,.35);margin-top:4px;}
    .char-count{float:right;font-size:10px;color:rgba(44,24,16,.3);}
    .field-error{font-size:12px;color:#c0392b;margin-top:4px;}
    .divider{height:.5px;background:rgba(44,24,16,.06);margin:20px 0;}
    .section-label{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(44,24,16,.4);margin-bottom:14px;padding-bottom:8px;border-bottom:.5px solid rgba(44,24,16,.06);}
    .terms-box{background:#f9f6f0;border:.5px solid rgba(201,106,61,.2);border-radius:10px;padding:14px 16px;margin-bottom:18px;}
    .terms-scroll{max-height:120px;overflow-y:auto;font-size:12px;color:rgba(44,24,16,.6);line-height:1.7;margin-bottom:12px;scrollbar-width:thin;}
    .terms-check{display:flex;align-items:flex-start;gap:10px;cursor:pointer;}
    .terms-check input{margin-top:2px;accent-color:#c96a3d;flex-shrink:0;}
    .terms-check-label{font-size:12px;color:rgba(44,24,16,.7);line-height:1.5;}
    .btn-submit{width:100%;background:#c96a3d;color:#f5f1e8;border:none;border-radius:8px;padding:14px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;margin-top:8px;}
    .btn-submit:hover{background:#b85c33;}
    .btn-submit:disabled{background:rgba(44,24,16,.15);cursor:not-allowed;}
    .info-box{background:#f9f6f0;border:.5px solid rgba(201,169,110,.3);border-radius:10px;padding:14px 16px;margin-bottom:24px;font-size:12px;color:rgba(44,24,16,.6);line-height:1.7;}
    .error-box{background:#fdf0f0;border:.5px solid #f5c6c6;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;}
    .loading-indicator{font-size:11px;color:rgba(44,24,16,.4);margin-top:4px;display:none;}
    @media(max-width:600px){
        .form-row,.form-row-3{grid-template-columns:1fr;}
        .card{padding:24px;}
    }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('home') }}" class="back-link">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke toko
    </a>

    <div class="card">
        <p class="card-eyebrow">Merchant Taku</p>
        <h1 class="card-title">Buka Toko Kamu</h1>
        <p class="card-sub">Isi formulir di bawah ini. Pengajuan akan ditinjau admin dalam 1×24 jam.</p>

        <div class="steps">
            <div class="step done">① Isi Data</div>
            <div class="step">② Review Admin</div>
            <div class="step">③ Toko Aktif</div>
        </div>

        <div class="info-box">
            <strong>Yang perlu dipersiapkan:</strong> Nama toko unik, deskripsi minimal 30 karakter, nomor WhatsApp aktif, dan alamat lengkap.
        </div>

        @if($errors->any())
        <div class="error-box">
            <ul style="padding-left:16px;margin:0;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('store.register.post') }}" method="POST" id="registerForm">
            @csrf

            {{-- Nama Toko --}}
            <p class="section-label">Informasi Toko</p>

            <div class="form-group">
                <label class="form-label">Nama Toko <span>*</span></label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name') }}" placeholder="Contoh: Warung Kita"
                       required maxlength="100">
                <p class="form-hint">Nama toko tidak bisa diubah setelah disetujui.</p>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- WhatsApp --}}
            <div class="form-group">
                <label class="form-label">Nomor WhatsApp <span>*</span></label>
                <div style="display:flex;border:.5px solid rgba(44,24,16,.15);border-radius:8px;overflow:hidden;">
                    <select id="storePhoneCode"
                            style="padding:11px 8px;border:none;outline:none;background:#f5f1e8;font-size:13px;color:#2c1810;font-family:'DM Sans',sans-serif;border-right:.5px solid rgba(44,24,16,.1);flex-shrink:0;cursor:pointer;"
                            onchange="combineStorePhone()">
                        <option value="+62">🇮🇩 +62</option>
                        <option value="+60">🇲🇾 +60</option>
                        <option value="+65">🇸🇬 +65</option>
                        <option value="+63">🇵🇭 +63</option>
                        <option value="+66">🇹🇭 +66</option>
                        <option value="+84">🇻🇳 +84</option>
                        <option value="+1">🇺🇸 +1</option>
                    </select>
                    <input type="text" id="storePhoneNum"
                           style="flex:1;padding:11px 12px;border:none;outline:none;font-size:13px;color:#2c1810;font-family:'DM Sans',sans-serif;background:white;"
                           placeholder="85xxxxxxxxx"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'');combineStorePhone()"
                           required>
                </div>
                <input type="hidden" name="phone" id="storePhoneFull"
                       value="{{ old('phone') }}">
                <p class="form-hint">Nomor WhatsApp aktif untuk dihubungi pembeli.</p>
                @error('phone') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label class="form-label">
                    Deskripsi Toko <span>*</span>
                    <span class="char-count" id="descCount">0/500</span>
                </label>
                <textarea name="description" class="form-textarea" id="descInput"
                          placeholder="Ceritakan tentang toko kamu — produk apa yang dijual, keunggulan, dll. (min. 30 karakter)"
                          required minlength="30" maxlength="500"
                          oninput="updateCount(this)">{{ old('description') }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="divider"></div>

            {{-- Alamat --}}
            <p class="section-label">Alamat Toko</p>

            {{-- Provinsi --}}
            <div class="form-group">
                <label class="form-label">Provinsi <span>*</span></label>
                <select name="province" id="provinceSelect" class="form-select"
                        onchange="loadRegencies(this.value)" required>
                    <option value="">— Pilih Provinsi —</option>
                </select>
                <input type="hidden" name="province_name" id="provinceName">
                <p class="loading-indicator" id="loadingProvince">Memuat provinsi...</p>
                @error('province') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Kabupaten/Kota --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kabupaten / Kota <span>*</span></label>
                    <select name="regency" id="regencySelect" class="form-select"
                            onchange="loadDistricts(this.value)" required disabled>
                        <option value="">— Pilih dulu provinsi —</option>
                    </select>
                    <input type="hidden" name="regency_name" id="regencyName">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan <span>*</span></label>
                    <select name="district" id="districtSelect" class="form-select"
                            onchange="loadVillages(this.value)" required disabled>
                        <option value="">— Pilih dulu kabupaten —</option>
                    </select>
                    <input type="hidden" name="district_name" id="districtName">
                </div>
            </div>

            {{-- Kelurahan + Kode Pos --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelurahan / Desa <span>*</span></label>
                    <select name="village" id="villageSelect" class="form-select"
                            required disabled>
                        <option value="">— Pilih dulu kecamatan —</option>
                    </select>
                    <input type="hidden" name="village_name" id="villageName">
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="postal_code" class="form-input"
                           value="{{ old('postal_code') }}" placeholder="12345"
                           maxlength="5" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                </div>
            </div>

            {{-- Jalan + Nomor --}}
            <div class="form-group">
                <label class="form-label">Nama Jalan / Gedung <span>*</span></label>
                <input type="text" name="street" class="form-input"
                       value="{{ old('street') }}"
                       placeholder="Jl. Sudirman, Ruko Blok A" required>
                @error('street') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">No. Rumah / Unit</label>
                    <input type="text" name="building_no" class="form-input"
                           value="{{ old('building_no') }}" placeholder="No. 10, Lt. 2">
                </div>
                <div class="form-group">
                    <label class="form-label">RT / RW</label>
                    <input type="text" name="rt_rw" class="form-input"
                           value="{{ old('rt_rw') }}" placeholder="001/002">
                </div>
            </div>

            {{-- Field city untuk backward compat --}}
            <input type="hidden" name="city" id="cityHidden">

            <div class="divider"></div>

            {{-- Terms --}}
            <div class="terms-box">
                <div class="terms-scroll">
                    <strong style="display:block;margin-bottom:8px;color:#2c1810;">Syarat & Ketentuan Merchant Taku</strong>
                    <p><strong>1. Kewajiban Merchant</strong><br>
                    Merchant wajib menjual produk yang legal, tidak mengandung unsur penipuan, dan sesuai deskripsi.</p><br>
                    <p><strong>2. Konten Toko</strong><br>
                    Merchant dilarang menjual produk yang melanggar hukum atau merugikan konsumen. Taku berhak menghapus produk yang melanggar ketentuan.</p><br>
                    <p><strong>3. Proses Pesanan</strong><br>
                    Merchant wajib memproses pesanan dalam 2×24 jam. Keterlambatan berulang dapat mengakibatkan penonaktifan toko.</p><br>
                    <p><strong>4. Approval</strong><br>
                    Taku berhak menolak pengajuan toko. Penolakan disertai alasan dan merchant dapat mengajukan ulang setelah 7 hari.</p><br>
                    <p><strong>5. Perubahan Ketentuan</strong><br>
                    Taku berhak mengubah syarat ini sewaktu-waktu dengan pemberitahuan.</p>
                </div>
                <label class="terms-check">
                    <input type="checkbox" name="agreed_terms" id="termsCheck"
                           {{ old('agreed_terms') ? 'checked' : '' }}
                           onchange="updateSubmit()">
                    <span class="terms-check-label">
                        Saya telah membaca dan menyetujui <strong>Syarat & Ketentuan</strong> Merchant Taku.
                    </span>
                </label>
                @error('agreed_terms') <p class="field-error" style="margin-top:8px;">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                Ajukan Pendaftaran Toko
            </button>
        </form>
    </div>
</div>

<script>
// ── Phone combine ─────────────────────────────────────────────────
function combineStorePhone() {
    const code = document.getElementById('storePhoneCode').value;
    const num  = document.getElementById('storePhoneNum').value.replace(/\D/g,'');
    document.getElementById('storePhoneFull').value = num ? (code + num) : '';
}
document.getElementById('registerForm').addEventListener('submit', function() {
    combineStorePhone();
    // Gabungkan alamat ke field city
    const regency  = document.getElementById('regencyName').value;
    const province = document.getElementById('provinceName').value;
    document.getElementById('cityHidden').value = [regency, province].filter(Boolean).join(', ');
});

// ── Counter deskripsi ─────────────────────────────────────────────
function updateCount(el) {
    document.getElementById('descCount').textContent = el.value.length + '/500';
    updateSubmit();
}
function updateSubmit() {
    const terms = document.getElementById('termsCheck').checked;
    const desc  = document.getElementById('descInput').value.length >= 30;
    const prov  = document.getElementById('provinceSelect').value;
    const street= document.querySelector('[name="street"]').value.trim();
    document.getElementById('submitBtn').disabled = !(terms && desc && prov && street);
}

const BASE = '/api/wilayah';

async function fetchWilayah(path) {
    try {
        const res = await fetch(BASE + '/' + path);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();
        return json.data ?? [];
    } catch (e) {
        console.error('Wilayah fetch error:', e);
        return [];
    }
}

function setSelectOptions(selectId, data, valueKey, labelKey, placeholder) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item[valueKey];
        opt.textContent = item[labelKey];
        sel.appendChild(opt);
    });
    sel.disabled = data.length === 0;
}

(async function loadProvinces() {
    document.getElementById('loadingProvince').style.display = 'block';
    const data = await fetchWilayah('provinces');
    document.getElementById('loadingProvince').style.display = 'none';
    setSelectOptions('provinceSelect', data, 'code', 'name', '— Pilih Provinsi —');

    const old = '{{ old('province') }}';
    if (old) {
        document.getElementById('provinceSelect').value = old;
        if (old) await loadRegencies(old, '{{ old('regency') }}');
    }
})();

async function loadRegencies(provCode, oldVal = '') {
    const sel = document.getElementById('provinceSelect');
    document.getElementById('provinceName').value = sel.options[sel.selectedIndex]?.text || '';

    setSelectOptions('regencySelect',  [], 'code', 'name', '— Memuat... —');
    setSelectOptions('districtSelect', [], 'code', 'name', '— Pilih dulu kabupaten —');
    setSelectOptions('villageSelect',  [], 'code', 'name', '— Pilih dulu kecamatan —');
    document.getElementById('regencySelect').disabled  = true;
    document.getElementById('districtSelect').disabled = true;
    document.getElementById('villageSelect').disabled  = true;

    if (!provCode) return;

    const data = await fetchWilayah('regencies/' + provCode);
    setSelectOptions('regencySelect', data, 'code', 'name', '— Pilih Kabupaten/Kota —');
    document.getElementById('regencySelect').disabled = false;

    if (oldVal) {
        document.getElementById('regencySelect').value = oldVal;
        await loadDistricts(oldVal, '{{ old('district') }}');
    }
    updateSubmit();
}

async function loadDistricts(regCode, oldVal = '') {
    const sel = document.getElementById('regencySelect');
    document.getElementById('regencyName').value = sel.options[sel.selectedIndex]?.text || '';

    setSelectOptions('districtSelect', [], 'code', 'name', '— Memuat... —');
    setSelectOptions('villageSelect',  [], 'code', 'name', '— Pilih dulu kecamatan —');
    document.getElementById('districtSelect').disabled = true;
    document.getElementById('villageSelect').disabled  = true;

    if (!regCode) return;

    const data = await fetchWilayah('districts/' + regCode);
    setSelectOptions('districtSelect', data, 'code', 'name', '— Pilih Kecamatan —');
    document.getElementById('districtSelect').disabled = false;

    if (oldVal) {
        document.getElementById('districtSelect').value = oldVal;
        await loadVillages(oldVal, '{{ old('village') }}');
    }
}

async function loadVillages(distCode, oldVal = '') {
    const sel = document.getElementById('districtSelect');
    document.getElementById('districtName').value = sel.options[sel.selectedIndex]?.text || '';

    setSelectOptions('villageSelect', [], 'code', 'name', '— Memuat... —');
    document.getElementById('villageSelect').disabled = true;

    if (!distCode) return;

    const data = await fetchWilayah('villages/' + distCode);
    setSelectOptions('villageSelect', data, 'code', 'name', '— Pilih Kelurahan/Desa —');
    document.getElementById('villageSelect').disabled = false;

    if (oldVal) document.getElementById('villageSelect').value = oldVal;
}

document.getElementById('villageSelect').addEventListener('change', function() {
    document.getElementById('villageName').value =
        this.options[this.selectedIndex]?.text || '';
});

// Init desc counter
const descEl = document.getElementById('descInput');
if (descEl.value) updateCount(descEl);
</script>
</body>
</html>
