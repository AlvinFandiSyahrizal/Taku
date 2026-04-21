{{-- Provinsi --}}
<div style="margin-bottom:12px;">
    <label class="co-field-label">Provinsi *</label>
    <select name="province_name" id="coProvince" class="co-input" style="appearance:none;"
            onchange="coLoadRegencies(this.value)" required>
        <option value="">— Pilih Provinsi —</option>
    </select>
    <p id="coLoadingProv" style="font-size:11px;color:rgba(11,42,74,.4);margin-top:4px;display:none;">Memuat...</p>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
    <div>
        <label class="co-field-label">Kabupaten / Kota *</label>
        <select name="regency_name" id="coRegency" class="co-input" style="appearance:none;"
                onchange="coLoadDistricts(this.value)" required disabled>
            <option value="">— Pilih Provinsi dulu —</option>
        </select>
    </div>
    <div>
        <label class="co-field-label">Kecamatan *</label>
        <select name="district_name" id="coDistrict" class="co-input" style="appearance:none;"
                onchange="coLoadVillages(this.value)" required disabled>
            <option value="">— Pilih Kab/Kota dulu —</option>
        </select>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
    <div>
        <label class="co-field-label">Kelurahan / Desa *</label>
        <select name="village_name" id="coVillage" class="co-input" style="appearance:none;"
                onchange="coUpdateAddress()" required disabled>
            <option value="">— Pilih Kecamatan dulu —</option>
        </select>
    </div>
    <div>
        <label class="co-field-label">Kode Pos</label>
        <input type="text" name="postal_code" id="coPostal" class="co-input"
               placeholder="12345" maxlength="5"
               oninput="this.value=this.value.replace(/[^0-9]/g,'');coUpdateAddress()">
    </div>
</div>

<div style="margin-bottom:12px;">
    <label class="co-field-label">Nama Jalan / Detail *</label>
    <input type="text" name="street" id="coStreet" class="co-input"
           placeholder="Jl. Sudirman No. 10, RT 01/RW 02" required
           oninput="coUpdateAddress()">
</div>