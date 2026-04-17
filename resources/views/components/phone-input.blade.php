@props(['name', 'value' => '', 'required' => false, 'placeholder' => '85xxxxxxxxx'])

@php
$savedCode = '+62';
$savedNum  = '';
$countryCodes = [
    '+62'=>'🇮🇩 +62',
    '+60'=>'🇲🇾 +60',
    '+65'=>'🇸🇬 +65',
    '+63'=>'🇵🇭 +63',
    '+66'=>'🇹🇭 +66',
    '+84'=>'🇻🇳 +84',
    '+1' =>'🇺🇸 +1',
    '+44'=>'🇬🇧 +44',
    '+61'=>'🇦🇺 +61',
];
if ($value) {
    foreach (array_keys($countryCodes) as $code) {
        if (str_starts_with($value, $code)) {
            $savedCode = $code;
            $savedNum  = substr($value, strlen($code));
            break;
        }
    }
    // Format lama tanpa kode negara (08xxx)
    if (!$savedNum && $value) {
        $savedNum = ltrim(preg_replace('/^\+?\d{1,3}/', '', $value), '0');
        if (!$savedNum) $savedNum = ltrim($value, '0');
    }
}
$inputId = 'pi_' . preg_replace('/[^a-z0-9]/i', '_', $name) . '_' . rand(1000,9999);
@endphp

<div style="display:flex;border:.5px solid rgba(11,42,74,.15);border-radius:8px;overflow:hidden;">
    <select id="{{ $inputId }}_code"
            style="padding:10px 8px;border:none;outline:none;background:#f9f7f4;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;border-right:.5px solid rgba(11,42,74,.1);flex-shrink:0;cursor:pointer;"
            onchange="piCombine_{{ $inputId }}()">
        @foreach($countryCodes as $code => $label)
            <option value="{{ $code }}" {{ $savedCode === $code ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <input type="text"
           id="{{ $inputId }}_num"
           style="flex:1;padding:10px 12px;border:none;outline:none;font-size:13px;color:#0b2a4a;font-family:'DM Sans',sans-serif;background:white;"
           placeholder="{{ $placeholder }}"
           value="{{ $savedNum }}"
           oninput="this.value=this.value.replace(/[^0-9]/g,''); piCombine_{{ $inputId }}()"
           {{ $required ? 'required' : '' }}>
</div>

<input type="hidden" name="{{ $name }}" id="{{ $inputId }}_full" value="{{ $value }}">

<div id="{{ $inputId }}_preview"
     style="display:{{ ($savedNum && strlen($savedNum) >= 6) ? 'inline-flex' : 'none' }};
            align-items:center;gap:6px;margin-top:8px;padding:5px 12px;
            background:#f0fff4;border:.5px solid #b2d9b2;border-radius:6px;
            font-size:12px;color:#2d6a2d;font-family:'DM Sans',sans-serif;">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="#25D366">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
    </svg>
    <span id="{{ $inputId }}_preview_text">WA aktif: {{ $savedCode }}{{ $savedNum }}</span>
</div>

<script>
(function () {
    function piCombine_{{ $inputId }}() {
        var code    = document.getElementById('{{ $inputId }}_code').value;
        var num     = document.getElementById('{{ $inputId }}_num').value.replace(/\D/g, '');
        var hidden  = document.getElementById('{{ $inputId }}_full');
        var preview = document.getElementById('{{ $inputId }}_preview');
        var prevTxt = document.getElementById('{{ $inputId }}_preview_text');

        hidden.value = num ? (code + num) : '';

        if (num.length >= 6) {
            preview.style.display = 'inline-flex';
            prevTxt.textContent   = 'WA aktif: ' + code + ' ' + num;
        } else {
            preview.style.display = 'none';
        }
    }

    window['piCombine_{{ $inputId }}'] = piCombine_{{ $inputId }};

    piCombine_{{ $inputId }}();

    var numEl = document.getElementById('{{ $inputId }}_num');
    if (numEl) {
        var form = numEl.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                piCombine_{{ $inputId }}();
            });
        }
    }
})();
</script>
