@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.co-wrap{max-width:1000px;margin:56px auto 80px;padding:0 32px;font-family:'DM Sans',sans-serif;display:flex;gap:48px;align-items:flex-start;}
.co-form-section{flex:1;min-width:300px;}
.co-label-top{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:8px;}
.co-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:36px;color:#0b2a4a;margin-bottom:28px;}

.addr-list{display:flex;flex-direction:column;gap:8px;margin-bottom:20px;}
.addr-option{display:flex;align-items:flex-start;gap:10px;padding:12px 14px;border:.5px solid rgba(11,42,74,.12);border-radius:8px;cursor:pointer;background:white;transition:border-color .2s,background .2s;}
.addr-option:hover{border-color:rgba(11,42,74,.2);}
.addr-option.selected{border-color:rgba(201,169,110,.5);background:rgba(201,169,110,.04);}
.addr-option input[type="radio"]{margin-top:3px;accent-color:#0b2a4a;flex-shrink:0;}
.addr-option-label{font-size:12px;font-weight:500;color:#0b2a4a;}
.addr-option-detail{font-size:11px;color:rgba(11,42,74,.45);margin-top:2px;line-height:1.5;}
.addr-new{display:flex;align-items:center;gap:10px;padding:10px 14px;border:.5px dashed rgba(11,42,74,.15);border-radius:8px;cursor:pointer;}
.addr-new:hover{border-color:rgba(11,42,74,.25);}

.co-section-label{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:12px;padding-bottom:8px;border-bottom:.5px solid rgba(11,42,74,.06);}

.co-field{margin-bottom:18px;}
.co-field-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.5);margin-bottom:8px;}
.co-input{width:100%;padding:11px 13px;border:.5px solid rgba(11,42,74,.18);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;}
.co-input:focus{border-color:#c9a96e;}
.co-input::placeholder{color:rgba(11,42,74,.25);}
.co-textarea{width:100%;padding:11px 13px;border:.5px solid rgba(11,42,74,.18);border-radius:8px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;resize:vertical;min-height:80px;background:white;}
.co-textarea:focus{border-color:#c9a96e;}
.co-error{font-size:12px;color:#c0392b;margin-top:4px;}

.co-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
@media(max-width:500px){.co-row{grid-template-columns:1fr;}}

.verify-banner{background:#fff8e6;border:.5px solid #f0d080;border-radius:8px;padding:11px 14px;font-size:12px;color:#7a5c00;margin-bottom:20px;display:flex;align-items:center;gap:10px;}

.co-submit-btn{width:100%;padding:14px;background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:flex;align-items:center;justify-content:center;gap:10px;margin-top:8px;transition:background .2s;}
.co-submit-btn:hover{background:#112f50;}
.co-back{display:block;text-align:center;margin-top:14px;font-size:11px;color:rgba(11,42,74,.4);text-decoration:none;letter-spacing:.1em;text-transform:uppercase;transition:color .2s;}
.co-back:hover{color:#0b2a4a;}
.co-note{font-size:11px;color:rgba(11,42,74,.4);margin-top:10px;text-align:center;line-height:1.6;}

.co-summary{width:320px;flex-shrink:0;position:sticky;top:84px;}
.co-store-block{background:#f8f6f2;border-radius:14px;padding:18px;border:.5px solid rgba(201,169,110,.2);margin-bottom:14px;}
.co-store-header{display:flex;align-items:center;gap:8px;margin-bottom:12px;padding-bottom:10px;border-bottom:.5px solid rgba(11,42,74,.08);}
.co-store-name{font-size:12px;font-weight:500;color:#0b2a4a;letter-spacing:.04em;}
.co-summary-item{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:10px;}
.co-item-left{display:flex;align-items:center;gap:10px;}
.co-item-img{width:38px;height:38px;object-fit:cover;border-radius:6px;flex-shrink:0;background:#e8e3d8;}
.co-item-name{font-size:12px;font-weight:500;color:#0b2a4a;}
.co-item-qty{font-size:11px;color:rgba(11,42,74,.4);margin-top:1px;}
.co-item-price{font-size:12px;font-weight:500;color:#0b2a4a;white-space:nowrap;}
.co-store-total{display:flex;justify-content:space-between;align-items:baseline;padding-top:10px;border-top:.5px solid rgba(11,42,74,.08);margin-top:10px;}
.co-store-total-label{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.4);}
.co-store-total-amount{font-family:'Cormorant Garamond',serif;font-size:20px;color:#0b2a4a;}
.co-grand-total{background:#0b2a4a;border-radius:12px;padding:16px 18px;display:flex;justify-content:space-between;align-items:baseline;}
.co-grand-label{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(240,235,224,.5);}
.co-grand-amount{font-family:'Cormorant Garamond',serif;font-size:24px;color:#f0ebe0;}

@media(max-width:700px){
    .co-wrap{flex-direction:column-reverse;gap:24px;padding:0 16px;margin-top:32px;}
    .co-summary{width:100%;position:static;}
    .co-title{font-size:28px;margin-bottom:20px;}
}
</style>

<div class="co-wrap">
    <div class="co-form-section">
        <p class="co-label-top">Taku</p>
        <h1 class="co-title">Checkout</h1>

        @if(!Auth::user()->hasVerifiedEmail())
        <div class="verify-banner">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Email belum diverifikasi. Beberapa fitur mungkin terbatas.
        </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf

@php
    $addresses   = Auth::user()->addresses()->get();
    $defaultAddr = $addresses->firstWhere('is_default', true) ?? $addresses->first();
    $hasAddr     = $addresses->count() > 0;
@endphp

@if($hasAddr)
{{-- Punya alamat tersimpan --}}
<p class="co-section-label">Alamat Pengiriman</p>
<div class="addr-list" id="addrList">
    @foreach($addresses as $addr)
    <label class="addr-option {{ $addr->is_default ? 'selected' : '' }}"
           onclick="selectAddr(this)">
        <input type="radio" name="use_address" value="{{ $addr->id }}"
               {{ $addr->is_default ? 'checked' : '' }}
               onchange="fillAddress(
                   '{{ addslashes($addr->recipient) }}',
                   '{{ addslashes($addr->phone) }}',
                   '{{ addslashes($addr->address) }}'
               )">
        <div>
            <p class="addr-option-label">
                {{ $addr->label }}
                @if($addr->is_default)
                    <span style="font-size:10px;background:rgba(201,169,110,.15);color:#b8955a;padding:1px 7px;border-radius:100px;margin-left:4px;font-weight:400;">Utama</span>
                @endif
            </p>
            <p class="addr-option-detail">
                {{ $addr->recipient }} · {{ $addr->phone }}<br>
                {{ $addr->address }}
                @if($addr->city) · {{ $addr->city }} @endif
                @if($addr->postal_code) {{ $addr->postal_code }} @endif
            </p>
        </div>
    </label>
    @endforeach

    {{-- Opsi alamat baru --}}
    <label class="addr-new" id="addrNewLabel" onclick="selectNew(this)">
        <input type="radio" name="use_address" value="new"
               style="accent-color:#0b2a4a;" onchange="showInlineForm(true)">
        <span style="font-size:12px;color:rgba(11,42,74,.5);">+ Masukkan alamat baru</span>
    </label>
</div>

{{-- Form alamat baru (tersembunyi, muncul kalau pilih "alamat baru") --}}
<div id="inlineAddrForm" style="display:none;background:#f9f7f3;border:.5px solid rgba(11,42,74,.1);border-radius:10px;padding:16px;margin-bottom:20px;">
    <p style="font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.4);margin-bottom:14px;">Alamat Baru</p>
    @include('pages.checkout-address-fields')
    <label style="display:flex;align-items:center;gap:8px;margin-top:10px;font-size:12px;color:rgba(11,42,74,.6);cursor:pointer;">
        <input type="checkbox" name="save_new_address" value="1" style="accent-color:#0b2a4a;">
        Simpan alamat ini untuk order berikutnya
    </label>
</div>
<div style="height:.5px;background:rgba(11,42,74,.08);margin:4px 0 20px;"></div>

@else
{{-- Belum punya alamat tersimpan sama sekali --}}
<div style="background:#f9f7f3;border:.5px solid rgba(201,169,110,.2);border-radius:12px;padding:16px 18px;margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a96e" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <p style="font-size:12px;font-weight:500;color:#0b2a4a;">Isi Alamat Pengiriman</p>
    </div>
    <input type="hidden" name="use_address" value="new">
    @include('pages.checkout-address-fields')
    <label style="display:flex;align-items:center;gap:8px;margin-top:12px;font-size:12px;color:rgba(11,42,74,.6);cursor:pointer;">
        <input type="checkbox" name="save_new_address" value="1" style="accent-color:#0b2a4a;" checked>
        Simpan alamat ini ke profil saya
    </label>
</div>
@endif

            <p class="co-section-label">Data Penerima</p>

            <div class="co-field">
                <label class="co-field-label">Nama Penerima *</label>
                <input type="text" name="name" class="co-input"
                       value="{{ old('name', Auth::user()->name) }}"
                       placeholder="Nama lengkap" required>
                @error('name') <p class="co-error">{{ $message }}</p> @enderror
            </div>

            <div class="co-field">
                <label class="co-field-label">Nomor WhatsApp *</label>
                @php
                    $coPhone     = old('phone', Auth::user()->phone ?? '');
                    $coPhoneCode = '+62';
                    $coPhoneNum  = '';
                    foreach (['+62','+60','+65','+63','+66','+84','+1'] as $c) {
                        if (str_starts_with($coPhone, $c)) {
                            $coPhoneCode = $c;
                            $coPhoneNum  = substr($coPhone, strlen($c));
                            break;
                        }
                    }
                    if (!$coPhoneNum && $coPhone) {
                        $coPhoneNum = ltrim(preg_replace('/^0/','',$coPhone));
                    }
                @endphp
                <div style="display:flex;border:.5px solid rgba(11,42,74,.18);border-radius:8px;overflow:hidden;">
                    <select id="coPhoneCode"
                            style="padding:10px 8px;border:none;outline:none;background:#f9f7f4;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;border-right:.5px solid rgba(11,42,74,.1);flex-shrink:0;cursor:pointer;"
                            onchange="combineCoPhone()">
                        <option value="+62" {{ $coPhoneCode==='+62'?'selected':'' }}>🇮🇩 +62</option>
                        <option value="+60" {{ $coPhoneCode==='+60'?'selected':'' }}>🇲🇾 +60</option>
                        <option value="+65" {{ $coPhoneCode==='+65'?'selected':'' }}>🇸🇬 +65</option>
                        <option value="+63" {{ $coPhoneCode==='+63'?'selected':'' }}>🇵🇭 +63</option>
                        <option value="+66" {{ $coPhoneCode==='+66'?'selected':'' }}>🇹🇭 +66</option>
                        <option value="+84" {{ $coPhoneCode==='+84'?'selected':'' }}>🇻🇳 +84</option>
                        <option value="+1"  {{ $coPhoneCode==='+1' ?'selected':'' }}>🇺🇸 +1</option>
                    </select>
                    <input type="text" id="coPhoneNum"
                           style="flex:1;padding:10px 12px;border:none;outline:none;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;background:white;"
                           placeholder="85xxxxxxxxx"
                           value="{{ $coPhoneNum }}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'');combineCoPhone()"
                           required>
                </div>
                <input type="hidden" name="phone" id="coPhoneFull" value="{{ $coPhone }}">
                @error('phone') <p class="co-error">{{ $message }}</p> @enderror
            </div>

<p class="co-section-label" style="margin-top:24px;">Ringkasan Alamat</p>

<div class="co-field">
    <textarea name="address" class="co-textarea" id="coAddress"
              placeholder="Alamat akan terisi otomatis dari pilihan di atas"
              required>{{ old('address', $defaultAddr?->address ?? '') }}</textarea>
    @error('address') <p class="co-error">{{ $message }}</p> @enderror
    <p style="font-size:11px;color:rgba(11,42,74,.4);margin-top:4px;">Kamu bisa edit langsung jika perlu.</p>
</div>

            <div class="co-field">
                <label class="co-field-label">Catatan (opsional)</label>
                <textarea name="note" class="co-textarea" style="min-height:65px;"
                          placeholder="Instruksi khusus untuk penjual...">{{ old('note') }}</textarea>
            </div>

            @php $orderCount = count($grouped); @endphp

            <button type="submit" class="co-submit-btn" onclick="combineCoPhone()">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                </svg>
                {{ $orderCount > 1 ? "Pesan ke {$orderCount} Toko via WA" : 'Pesan via WhatsApp' }}
            </button>

            @if($orderCount > 1)
            <p class="co-note">
                Akan membuat {{ $orderCount }} pesanan dan membuka<br>
                {{ $orderCount }} chat WhatsApp ke masing-masing toko.
            </p>
            @endif
        </form>

        <a href="{{ route('cart.index') }}" class="co-back">← Kembali ke Keranjang</a>
    </div>

    <div class="co-summary">
        @foreach($grouped as $group)
        <div class="co-store-block">
            <div class="co-store-header">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(11,42,74,0.4)" stroke-width="1.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span class="co-store-name">{{ $group['store_name'] }}</span>
            </div>

            @foreach($group['items'] as $item)
            @php $sub = (int)$item['price'] * (int)$item['qty']; @endphp
            <div class="co-summary-item">
                <div class="co-item-left">
                    @if(!empty($item['image']))
                        <img src="{{ asset($item['image']) }}" class="co-item-img" alt="{{ $item['name'] }}">
                    @else
                        <div class="co-item-img"></div>
                    @endif
                    <div>
                        <p class="co-item-name">{{ Str::limit($item['name'], 26) }}</p>
                        <p class="co-item-qty">× {{ $item['qty'] }}</p>
                    </div>
                </div>
                <p class="co-item-price">Rp {{ number_format($sub, 0, ',', '.') }}</p>
            </div>
            @endforeach

            <div class="co-store-total">
                <span class="co-store-total-label">Subtotal</span>
                <span class="co-store-total-amount">Rp {{ number_format($group['subtotal'], 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="co-grand-total">
            <span class="co-grand-label">Total</span>
            <span class="co-grand-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<script>
function combineCoPhone() {
    const code = document.getElementById('coPhoneCode').value;
    const num  = document.getElementById('coPhoneNum').value.replace(/\D/g,'');
    document.getElementById('coPhoneFull').value = num ? (code + num) : '';
}
document.getElementById('checkoutForm').addEventListener('submit', combineCoPhone);

combineCoPhone();

function selectAddr(label, id) {
    document.querySelectorAll('.addr-option').forEach(el => el.classList.remove('selected'));
    label.classList.add('selected');
}
function selectNew(label) {
    document.querySelectorAll('.addr-option, .addr-new').forEach(el => el.classList.remove('selected'));
}

function fillAddress(name, phone, address) {
    document.querySelector('[name="name"]').value = name;

    const codes = ['+62','+60','+65','+63','+66','+84','+1','+44','+61'];
    const codeEl = document.getElementById('coPhoneCode');
    const numEl  = document.getElementById('coPhoneNum');

    let matched = false;
    for (const c of codes) {
        if (phone.startsWith(c)) {
            codeEl.value = c;
            numEl.value  = phone.slice(c.length).replace(/\D/g,'');
            matched = true;
            break;
        }
    }
    if (!matched && phone) {
        codeEl.value = '+62';
        numEl.value  = phone.replace(/^0+/,'').replace(/\D/g,'');
    }
    combineCoPhone();

    document.getElementById('coAddress').value = address;
}

function clearAddress() {
    document.querySelector('[name="name"]').value    = '{{ addslashes(Auth::user()->name) }}';
    document.getElementById('coPhoneNum').value      = '';
    document.getElementById('coPhoneFull').value     = '';
    document.getElementById('coAddress').value       = '';
}

document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('[name="use_address"]:checked');
    if (checked && checked.value !== 'new') {
        checked.dispatchEvent(new Event('change'));
    }
});
</script>

<script>
const CO_BASE = '/api/wilayah';

async function coFetch(path) {
    try {
        const r = await fetch(CO_BASE + '/' + path);
        if (!r.ok) return [];
        const j = await r.json();
        return j.data ?? [];
    } catch { return []; }
}

function coSetOptions(selId, data, vKey, lKey, placeholder) {
    const sel = document.getElementById(selId);
    if (!sel) return;
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item[lKey]; // simpan nama, bukan kode
        opt.dataset.code = item[vKey];
        opt.textContent = item[lKey];
        sel.appendChild(opt);
    });
    sel.disabled = data.length === 0;
}

(async function coInitProvinces() {
    document.getElementById('coLoadingProv').style.display = 'block';
    const data = await coFetch('provinces');
    document.getElementById('coLoadingProv').style.display = 'none';
    coSetOptions('coProvince', data, 'code', 'name', '— Pilih Provinsi —');
})();

async function coLoadRegencies(provName) {
    // Cari kode provinsi dari option yang dipilih
    const sel = document.getElementById('coProvince');
    const opt = sel.querySelector(`option[value="${provName}"]`);
    const code = opt?.dataset.code;
    if (!code) return;

    coSetOptions('coRegency',  [], 'code', 'name', '— Memuat... —');
    coSetOptions('coDistrict', [], 'code', 'name', '— Pilih Kab/Kota dulu —');
    coSetOptions('coVillage',  [], 'code', 'name', '— Pilih Kecamatan dulu —');
    document.getElementById('coRegency').disabled  = true;
    document.getElementById('coDistrict').disabled = true;
    document.getElementById('coVillage').disabled  = true;

    const data = await coFetch('regencies/' + code);
    coSetOptions('coRegency', data, 'code', 'name', '— Pilih Kabupaten/Kota —');
    document.getElementById('coRegency').disabled = false;
    coUpdateAddress();
}

async function coLoadDistricts(regName) {
    const sel = document.getElementById('coRegency');
    const opt = sel.querySelector(`option[value="${regName}"]`);
    const code = opt?.dataset.code;
    if (!code) return;

    coSetOptions('coDistrict', [], 'code', 'name', '— Memuat... —');
    coSetOptions('coVillage',  [], 'code', 'name', '— Pilih Kecamatan dulu —');
    document.getElementById('coDistrict').disabled = true;
    document.getElementById('coVillage').disabled  = true;

    const data = await coFetch('districts/' + code);
    coSetOptions('coDistrict', data, 'code', 'name', '— Pilih Kecamatan —');
    document.getElementById('coDistrict').disabled = false;
    coUpdateAddress();
}

async function coLoadVillages(distName) {
    const sel = document.getElementById('coDistrict');
    const opt = sel.querySelector(`option[value="${distName}"]`);
    const code = opt?.dataset.code;
    if (!code) return;

    coSetOptions('coVillage', [], 'code', 'name', '— Memuat... —');
    document.getElementById('coVillage').disabled = true;

    const data = await coFetch('villages/' + code);
    coSetOptions('coVillage', data, 'code', 'name', '— Pilih Kelurahan/Desa —');
    document.getElementById('coVillage').disabled = false;
    coUpdateAddress();
}

function coUpdateAddress() {
    const street   = document.getElementById('coStreet')?.value?.trim() || '';
    const village  = document.getElementById('coVillage')?.value || '';
    const district = document.getElementById('coDistrict')?.value || '';
    const regency  = document.getElementById('coRegency')?.value || '';
    const province = document.getElementById('coProvince')?.value || '';
    const postal   = document.getElementById('coPostal')?.value || '';

    const parts = [street, village, district, regency, province].filter(Boolean);
    if (postal) parts.push(postal);

    const addr = document.getElementById('coAddress');
    if (addr && parts.length > 0) addr.value = parts.join(', ');
}

function showInlineForm(show) {
    const form = document.getElementById('inlineAddrForm');
    if (form) form.style.display = show ? 'block' : 'none';
}
</script>

@endsection