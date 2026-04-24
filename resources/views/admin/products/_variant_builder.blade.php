{{--
    Partial: _variant_builder.blade.php
    Dipanggil dari admin/merchant products create & edit.

    Variabel yang harus tersedia:
      $product  — bisa null (pada create) atau object (pada edit)
--}}

@php
    $existingVariants = isset($product) ? $product->variants : collect();
@endphp

<style>
/* ── Variant Builder ─────────────────────────────────────────────── */
.vb-wrap { margin-top: 0; }
.vb-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 6px; }
.vb-title { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: rgba(11,42,74,.45); display: flex; align-items: center; gap: 8px; }
.vb-title::after { content: ''; width: 60px; height: .5px; background: rgba(11,42,74,.08); }
.vb-hint { font-size: 11px; color: rgba(11,42,74,.35); }
.vb-table-wrap { overflow-x: auto; }
.vb-table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 700px; }
.vb-table th { font-size: 10px; letter-spacing: .1em; text-transform: uppercase; color: rgba(11,42,74,.4); padding: 0 8px 10px; text-align: left; font-weight: 500; white-space: nowrap; }
.vb-table td { padding: 6px 8px; vertical-align: middle; }
.vb-row td { border-top: .5px solid rgba(11,42,74,.06); }

/* Input dalam tabel */
.vb-input { width: 100%; padding: 8px 10px; border: .5px solid rgba(11,42,74,.15); border-radius: 6px; font-size: 13px; color: #0b2a4a; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color .2s; background: white; box-sizing: border-box; }
.vb-input:focus { border-color: #c9a96e; }

/* Input + satuan */
.vb-size-wrap { display: flex; }
.vb-size-wrap .vb-input { border-radius: 6px 0 0 6px; border-right: none; flex: 1; min-width: 60px; }
.vb-unit { padding: 8px 10px; border: .5px solid rgba(11,42,74,.15); border-radius: 0 6px 6px 0; font-size: 12px; color: #0b2a4a; font-family: 'DM Sans', sans-serif; background: #f7f5f0; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; min-width: 66px; text-align: center; transition: border-color .2s; }
.vb-unit:focus { border-color: #c9a96e; }

/* Diskon input — warna merah kalau diisi */
.vb-discount-wrap { position: relative; }
.vb-discount-wrap .vb-input { padding-right: 26px; }
.vb-discount-suffix { position: absolute; right: 9px; top: 50%; transform: translateY(-50%); font-size: 11px; color: rgba(11,42,74,.35); pointer-events: none; }
.vb-input.has-discount { border-color: rgba(192,57,43,.4); color: #c0392b; background: rgba(192,57,43,.03); }

/* Preview harga final */
.vb-final-price { font-size: 11px; color: #c0392b; margin-top: 3px; display: none; white-space: nowrap; }

/* Tombol hapus */
.vb-del-btn { width: 30px; height: 30px; border-radius: 6px; border: .5px solid rgba(192,57,43,.25); background: rgba(192,57,43,.06); color: #c0392b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s; flex-shrink: 0; }
.vb-del-btn:hover { background: rgba(192,57,43,.14); border-color: rgba(192,57,43,.5); }

/* Tombol tambah */
.vb-add-btn { margin-top: 12px; padding: 9px 18px; background: none; border: .5px dashed rgba(11,42,74,.25); border-radius: 8px; font-size: 11px; letter-spacing: .1em; text-transform: uppercase; color: rgba(11,42,74,.45); cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .2s; display: flex; align-items: center; gap: 6px; }
.vb-add-btn:hover { border-color: #c9a96e; color: #c9a96e; background: rgba(201,169,110,.04); }

/* Info kosong */
.vb-empty { padding: 20px 0 8px; font-size: 12px; color: rgba(11,42,74,.35); text-align: center; border-top: .5px solid rgba(11,42,74,.06); display: none; }
</style>

<div class="vb-wrap">
    <div class="vb-header">
        <span class="vb-title">Variasi Ukuran & Harga</span>
        <span class="vb-hint">Opsional — kosongkan jika produk tidak ada variasi ukuran</span>
    </div>

    <div class="vb-table-wrap">
        <table class="vb-table">
            <thead>
                <tr>
                    <th style="width:155px;">Tinggi</th>
                    <th style="width:155px;">Diameter</th>
                    <th style="width:130px;">Harga (Rp) *</th>
                    <th style="width:90px;">Diskon (%)</th>
                    <th style="width:80px;">Stok</th>
                    <th style="width:36px;"></th>
                </tr>
            </thead>
            <tbody id="vbTbody">

                @foreach($existingVariants as $v)
                <tr class="vb-row" data-idx="{{ $loop->index }}">
                    <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $v->id }}">
                    <td>
                        <div class="vb-size-wrap">
                            <input type="number" name="variants[{{ $loop->index }}][height]"
                                   class="vb-input" placeholder="cth: 30"
                                   value="{{ $v->height ? ($v->height == (int)$v->height ? (int)$v->height : $v->height) : '' }}"
                                   min="0" step="0.01">
                            <select name="variants[{{ $loop->index }}][height_unit]" class="vb-unit">
                                <option value="cm"    {{ ($v->height_unit ?? 'cm') === 'cm'    ? 'selected' : '' }}>cm</option>
                                <option value="meter" {{ ($v->height_unit ?? 'cm') === 'meter' ? 'selected' : '' }}>meter</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="vb-size-wrap">
                            <input type="number" name="variants[{{ $loop->index }}][diameter]"
                                   class="vb-input" placeholder="cth: 15"
                                   value="{{ $v->diameter ? ($v->diameter == (int)$v->diameter ? (int)$v->diameter : $v->diameter) : '' }}"
                                   min="0" step="0.01">
                            <select name="variants[{{ $loop->index }}][diameter_unit]" class="vb-unit">
                                <option value="cm"    {{ ($v->diameter_unit ?? 'cm') === 'cm'    ? 'selected' : '' }}>cm</option>
                                <option value="meter" {{ ($v->diameter_unit ?? 'cm') === 'meter' ? 'selected' : '' }}>meter</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="number" name="variants[{{ $loop->index }}][price]"
                               class="vb-input vb-price-input" placeholder="50000"
                               value="{{ $v->price }}" min="0" required
                               oninput="vbUpdateFinalPrice(this)">
                    </td>
                    <td>
                        <div class="vb-discount-wrap">
                            <input type="number" name="variants[{{ $loop->index }}][discount_percent]"
                                   class="vb-input vb-discount-input {{ $v->discount_percent > 0 ? 'has-discount' : '' }}"
                                   placeholder="0" value="{{ $v->discount_percent ?? 0 }}"
                                   min="0" max="100"
                                   oninput="vbUpdateFinalPrice(this)">
                            <span class="vb-discount-suffix">%</span>
                        </div>
                        @if($v->discount_percent > 0)
                        <p class="vb-final-price" style="display:block;">
                            → Rp {{ number_format($v->getFinalPrice(), 0, ',', '.') }}
                        </p>
                        @else
                        <p class="vb-final-price"></p>
                        @endif
                    </td>
                    <td>
                        <input type="number" name="variants[{{ $loop->index }}][stock]"
                               class="vb-input" placeholder="0"
                               value="{{ $v->stock }}" min="0">
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

    // Update preview harga final saat harga atau diskon berubah
    window.vbUpdateFinalPrice = function(inputEl) {
        const row      = inputEl.closest('tr');
        const priceEl  = row.querySelector('.vb-price-input');
        const discEl   = row.querySelector('.vb-discount-input');
        const finalEl  = row.querySelector('.vb-final-price');
        if (!priceEl || !discEl || !finalEl) return;

        const price    = parseInt(priceEl.value) || 0;
        const discount = parseInt(discEl.value)  || 0;

        // Style discount input
        if (discount > 0) {
            discEl.classList.add('has-discount');
        } else {
            discEl.classList.remove('has-discount');
        }

        // Tampilkan preview harga final
        if (discount > 0 && price > 0) {
            const final = Math.round(price * (1 - discount / 100));
            finalEl.textContent = '→ Rp ' + final.toLocaleString('id-ID');
            finalEl.style.display = 'block';
        } else {
            finalEl.textContent = '';
            finalEl.style.display = 'none';
        }
    };

    window.vbAddRow = function () {
        const tbody = document.getElementById('vbTbody');
        const idx   = vbIdx++;

        const tr = document.createElement('tr');
        tr.className = 'vb-row';
        tr.dataset.idx = idx;
        tr.innerHTML = `
            <td>
                <div class="vb-size-wrap">
                    <input type="number" name="variants[${idx}][height]"
                           class="vb-input" placeholder="cth: 30" min="0" step="0.01">
                    <select name="variants[${idx}][height_unit]" class="vb-unit">
                        <option value="cm">cm</option>
                        <option value="meter">meter</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="vb-size-wrap">
                    <input type="number" name="variants[${idx}][diameter]"
                           class="vb-input" placeholder="cth: 15" min="0" step="0.01">
                    <select name="variants[${idx}][diameter_unit]" class="vb-unit">
                        <option value="cm">cm</option>
                        <option value="meter">meter</option>
                    </select>
                </div>
            </td>
            <td>
                <input type="number" name="variants[${idx}][price]"
                       class="vb-input vb-price-input" placeholder="50000" min="0" required
                       oninput="vbUpdateFinalPrice(this)">
            </td>
            <td>
                <div class="vb-discount-wrap">
                    <input type="number" name="variants[${idx}][discount_percent]"
                           class="vb-input vb-discount-input"
                           placeholder="0" value="0" min="0" max="100"
                           oninput="vbUpdateFinalPrice(this)">
                    <span class="vb-discount-suffix">%</span>
                </div>
                <p class="vb-final-price"></p>
            </td>
            <td>
                <input type="number" name="variants[${idx}][stock]"
                       class="vb-input" placeholder="0" min="0">
            </td>
            <td>
                <button type="button" class="vb-del-btn" onclick="vbRemoveRow(this)" title="Hapus baris">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        updateEmpty();
        tr.querySelector('.vb-price-input')?.focus();
    };

    window.vbRemoveRow = function (btn) {
        btn.closest('tr').remove();
        updateEmpty();
    };

    function updateEmpty() {
        const rows  = document.querySelectorAll('#vbTbody .vb-row');
        const empty = document.getElementById('vbEmpty');
        if (empty) empty.style.display = rows.length === 0 ? 'block' : 'none';
    }

    updateEmpty();
})();
</script>
