{{--
    Partial: _variant_builder.blade.php
    Dipanggil dari admin/merchant products create & edit.
    $product — null (create) atau object (edit)
--}}
@php $existingVariants = isset($product) && $product ? $product->variants : collect(); @endphp

<style>
.vb-wrap{margin-top:0;}
.vb-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:6px;}
.vb-title{font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);display:flex;align-items:center;gap:8px;}
.vb-title::after{content:'';width:40px;height:.5px;background:rgba(11,42,74,.08);}
.vb-hint{font-size:11px;color:rgba(11,42,74,.35);}
.vb-table-wrap{overflow-x:auto;}
.vb-table{width:100%;border-collapse:collapse;font-size:13px;min-width:720px;}
.vb-table th{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.4);padding:0 8px 10px;text-align:left;font-weight:500;white-space:nowrap;}
.vb-table td{padding:6px 8px;vertical-align:middle;}
.vb-row td{border-top:.5px solid rgba(11,42,74,.06);}
.vb-input{width:100%;padding:8px 10px;border:.5px solid rgba(11,42,74,.15);border-radius:6px;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
.vb-input:focus{border-color:#c9a96e;}
.vb-input.error{border-color:#c0392b;background:#fdf8f8;}
.vb-size-wrap{display:flex;}
.vb-size-wrap .vb-input{border-radius:6px 0 0 6px;border-right:none;flex:1;min-width:52px;}
.vb-unit{padding:8px 8px;border:.5px solid rgba(11,42,74,.15);border-radius:0 6px 6px 0;font-size:12px;color:#0b2a4a;font-family:'DM Sans',sans-serif;background:#f7f5f0;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;min-width:58px;text-align:center;transition:border-color .2s;}
.vb-unit:focus{border-color:#c9a96e;}

/* Harga + diskon dalam 1 sel */
.vb-price-wrap{display:flex;flex-direction:column;gap:4px;}
.vb-discount-row{display:flex;align-items:center;gap:4px;}
.vb-discount-input{width:52px;padding:4px 6px;border:.5px solid rgba(11,42,74,.12);border-radius:5px;font-size:11px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;text-align:right;}
.vb-discount-input:focus{border-color:#c9a96e;}
.vb-discount-label{font-size:10px;color:rgba(11,42,74,.35);}
.vb-final-price{font-size:10px;color:#c9a96e;font-weight:500;min-height:14px;}

.vb-del-btn{width:30px;height:30px;border-radius:6px;border:.5px solid rgba(192,57,43,.25);background:rgba(192,57,43,.06);color:#c0392b;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;}
.vb-del-btn:hover{background:rgba(192,57,43,.14);border-color:rgba(192,57,43,.5);}
.vb-add-btn{margin-top:12px;padding:9px 18px;background:none;border:.5px dashed rgba(11,42,74,.25);border-radius:8px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(11,42,74,.45);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;display:flex;align-items:center;gap:6px;}
.vb-add-btn:hover{border-color:#c9a96e;color:#c9a96e;background:rgba(201,169,110,.04);}
.vb-empty{padding:20px 0 8px;font-size:12px;color:rgba(11,42,74,.35);text-align:center;border-top:.5px solid rgba(11,42,74,.06);display:none;}

/* Baris baru highlight */
.vb-row.new-row{animation:vbFadeIn .25s ease;}
@keyframes vbFadeIn{from{opacity:0;transform:translateY(-4px);}to{opacity:1;transform:translateY(0);}}
</style>

<div class="vb-wrap">
    <div class="vb-header">
        <span class="vb-title">Variasi Ukuran & Harga</span>
        <span class="vb-hint">Opsional — kosongkan jika produk tidak ada variasi</span>
    </div>

    <div class="vb-table-wrap">
        <table class="vb-table">
            <thead>
                <tr>
                    <th style="width:150px;">Tinggi</th>
                    <th style="width:150px;">Diameter</th>
                    <th style="width:170px;">Harga (Rp) *</th>
                    <th style="width:80px;">Stok</th>
                    <th style="width:36px;"></th>
                </tr>
            </thead>
            <tbody id="vbTbody">

                @foreach($existingVariants as $v)
                @php $i = $loop->index; @endphp
                <tr class="vb-row" data-idx="{{ $i }}">
                    <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $v->id }}">
                    <td>
                        <div class="vb-size-wrap">
                            <input type="number" name="variants[{{ $i }}][height]" class="vb-input"
                                   placeholder="cth: 30" min="0" step="0.01"
                                   value="{{ $v->height ? ($v->height == (int)$v->height ? (int)$v->height : $v->height) : '' }}">
                            <select name="variants[{{ $i }}][height_unit]" class="vb-unit">
                                <option value="cm"    {{ ($v->height_unit ?? 'cm') === 'cm'    ? 'selected' : '' }}>cm</option>
                                <option value="meter" {{ ($v->height_unit ?? 'cm') === 'meter' ? 'selected' : '' }}>meter</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="vb-size-wrap">
                            <input type="number" name="variants[{{ $i }}][diameter]" class="vb-input"
                                   placeholder="cth: 15" min="0" step="0.01"
                                   value="{{ $v->diameter ? ($v->diameter == (int)$v->diameter ? (int)$v->diameter : $v->diameter) : '' }}">
                            <select name="variants[{{ $i }}][diameter_unit]" class="vb-unit">
                                <option value="cm"    {{ ($v->diameter_unit ?? 'cm') === 'cm'    ? 'selected' : '' }}>cm</option>
                                <option value="meter" {{ ($v->diameter_unit ?? 'cm') === 'meter' ? 'selected' : '' }}>meter</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="vb-price-wrap">
                            <input type="number" name="variants[{{ $i }}][price]" class="vb-input vb-price-inp"
                                   placeholder="50000" min="0" value="{{ $v->price }}"
                                   oninput="vbUpdateFinal(this)">
                            <div class="vb-discount-row">
                                <input type="number" name="variants[{{ $i }}][discount_percent]"
                                       class="vb-discount-input vb-disc-inp"
                                       placeholder="0" min="0" max="100"
                                       value="{{ $v->discount_percent ?? 0 }}"
                                       oninput="vbUpdateFinal(this)">
                                <span class="vb-discount-label">% diskon</span>
                            </div>
                            <span class="vb-final-price" id="vbFinal_{{ $i }}">
                                @if($v->discount_percent > 0)
                                    → Rp {{ number_format($v->getFinalPrice(), 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </td>
                    <td>
                        <input type="number" name="variants[{{ $i }}][stock]" class="vb-input"
                               placeholder="0" min="0" value="{{ $v->stock }}">
                    </td>
                    <td>
                        <button type="button" class="vb-del-btn" onclick="vbRemoveRow(this)" title="Hapus baris">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <p class="vb-empty" id="vbEmpty">Belum ada variasi. Klik "+ Tambah Variasi" untuk menambahkan.</p>
    </div>

    <button type="button" class="vb-add-btn" onclick="vbAddRow()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Variasi
    </button>
</div>

<script>
(function () {
    let vbIdx = {{ $existingVariants->count() }};

    window.vbAddRow = function () {
        const tbody = document.getElementById('vbTbody');
        const idx   = vbIdx++;
        const tr    = document.createElement('tr');
        tr.className = 'vb-row new-row';
        tr.dataset.idx = idx;
        tr.innerHTML = `
            <td>
                <div class="vb-size-wrap">
                    <input type="number" name="variants[${idx}][height]" class="vb-input" placeholder="cth: 30" min="0" step="0.01">
                    <select name="variants[${idx}][height_unit]" class="vb-unit">
                        <option value="cm">cm</option><option value="meter">meter</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="vb-size-wrap">
                    <input type="number" name="variants[${idx}][diameter]" class="vb-input" placeholder="cth: 15" min="0" step="0.01">
                    <select name="variants[${idx}][diameter_unit]" class="vb-unit">
                        <option value="cm">cm</option><option value="meter">meter</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="vb-price-wrap">
                    <input type="number" name="variants[${idx}][price]" class="vb-input vb-price-inp"
                           placeholder="50000" min="0" oninput="vbUpdateFinal(this)">
                    <div class="vb-discount-row">
                        <input type="number" name="variants[${idx}][discount_percent]"
                               class="vb-discount-input vb-disc-inp"
                               placeholder="0" min="0" max="100" value="0"
                               oninput="vbUpdateFinal(this)">
                        <span class="vb-discount-label">% diskon</span>
                    </div>
                    <span class="vb-final-price" id="vbFinal_${idx}"></span>
                </div>
            </td>
            <td>
                <input type="number" name="variants[${idx}][stock]" class="vb-input" placeholder="0" min="0">
            </td>
            <td>
                <button type="button" class="vb-del-btn" onclick="vbRemoveRow(this)" title="Hapus baris">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        updateEmpty();
        tr.querySelector('.vb-price-inp')?.focus();
    };

    window.vbRemoveRow = function (btn) {
        btn.closest('tr').remove();
        updateEmpty();
    };

    window.vbUpdateFinal = function (inp) {
        const row      = inp.closest('tr');
        const priceInp = row.querySelector('.vb-price-inp');
        const discInp  = row.querySelector('.vb-disc-inp');
        const idx      = row.dataset.idx;
        const finalEl  = document.getElementById('vbFinal_' + idx);
        if (!finalEl) return;

        const price = parseFloat(priceInp?.value) || 0;
        const disc  = parseFloat(discInp?.value)  || 0;

        if (disc > 0 && price > 0) {
            const final = Math.round(price * (1 - disc / 100));
            finalEl.textContent = '→ Rp ' + final.toLocaleString('id-ID');
            finalEl.style.color = '#c9a96e';
        } else {
            finalEl.textContent = '';
        }

        // Validasi diskon 0-100
        if (disc > 100) { discInp.value = 100; vbUpdateFinal(discInp); }
        if (disc < 0)   { discInp.value = 0;   vbUpdateFinal(discInp); }
    };

    function updateEmpty() {
        const rows  = document.querySelectorAll('#vbTbody .vb-row');
        const empty = document.getElementById('vbEmpty');
        if (empty) empty.style.display = rows.length === 0 ? 'block' : 'none';
    }

    updateEmpty();
})();
</script>
