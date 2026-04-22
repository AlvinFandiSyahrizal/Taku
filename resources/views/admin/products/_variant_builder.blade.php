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
.vb-wrap {
    margin-top: 0;
}
.vb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.vb-title {
    font-size: 11px;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(11,42,74,.45);
    display: flex;
    align-items: center;
    gap: 8px;
}
.vb-title::after {
    content: '';
    width: 60px;
    height: .5px;
    background: rgba(11,42,74,.08);
}
.vb-hint {
    font-size: 11px;
    color: rgba(11,42,74,.35);
}
.vb-table-wrap {
    overflow-x: auto;
}
.vb-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 600px;
}
.vb-table th {
    font-size: 10px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(11,42,74,.4);
    padding: 0 8px 10px;
    text-align: left;
    font-weight: 500;
    white-space: nowrap;
}
.vb-table td {
    padding: 6px 8px;
    vertical-align: middle;
}
.vb-row td {
    border-top: .5px solid rgba(11,42,74,.06);
}

/* Input dalam tabel */
.vb-input {
    width: 100%;
    padding: 8px 10px;
    border: .5px solid rgba(11,42,74,.15);
    border-radius: 6px;
    font-size: 13px;
    color: #0b2a4a;
    font-family: 'DM Sans', sans-serif;
    outline: none;
    transition: border-color .2s;
    background: white;
    box-sizing: border-box;
}
.vb-input:focus { border-color: #c9a96e; }

/* Input + satuan dalam 1 baris */
.vb-size-wrap {
    display: flex;
    gap: 0;
}
.vb-size-wrap .vb-input {
    border-radius: 6px 0 0 6px;
    border-right: none;
    flex: 1;
    min-width: 60px;
}
.vb-unit {
    padding: 8px 10px;
    border: .5px solid rgba(11,42,74,.15);
    border-radius: 0 6px 6px 0;
    font-size: 12px;
    color: #0b2a4a;
    font-family: 'DM Sans', sans-serif;
    background: #f7f5f0;
    cursor: pointer;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    min-width: 66px;
    text-align: center;
    transition: border-color .2s;
}
.vb-unit:focus { border-color: #c9a96e; }

/* Tombol hapus baris */
.vb-del-btn {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: .5px solid rgba(192,57,43,.25);
    background: rgba(192,57,43,.06);
    color: #c0392b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    flex-shrink: 0;
}
.vb-del-btn:hover {
    background: rgba(192,57,43,.14);
    border-color: rgba(192,57,43,.5);
}

/* Tombol tambah baris */
.vb-add-btn {
    margin-top: 12px;
    padding: 9px 18px;
    background: none;
    border: .5px dashed rgba(11,42,74,.25);
    border-radius: 8px;
    font-size: 11px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(11,42,74,.45);
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.vb-add-btn:hover {
    border-color: #c9a96e;
    color: #c9a96e;
    background: rgba(201,169,110,.04);
}

/* Badge stok habis */
.vb-out-badge {
    font-size: 10px;
    background: rgba(192,57,43,.1);
    color: #c0392b;
    padding: 2px 7px;
    border-radius: 100px;
    white-space: nowrap;
}

/* Info kosong */
.vb-empty {
    padding: 20px 0 8px;
    font-size: 12px;
    color: rgba(11,42,74,.35);
    text-align: center;
    border-top: .5px solid rgba(11,42,74,.06);
    display: none;
}
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
                    <th style="width:160px;">Tinggi</th>
                    <th style="width:160px;">Diameter</th>
                    <th style="width:130px;">Harga (Rp) *</th>
                    <th style="width:90px;">Stok</th>
                    <th style="width:36px;"></th>
                </tr>
            </thead>
            <tbody id="vbTbody">

                {{-- Render existing variants (pada edit) --}}
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
                               class="vb-input" placeholder="50000"
                               value="{{ $v->price }}" min="0" required>
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
    // Hitung index terakhir dari server-rendered rows
    let vbIdx = {{ $existingVariants->count() }};

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
                       class="vb-input" placeholder="50000" min="0" required>
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

        // Fokus ke input harga baris baru
        tr.querySelector('[name*="[price]"]')?.focus();
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

    // Init
    updateEmpty();
})();
</script>
