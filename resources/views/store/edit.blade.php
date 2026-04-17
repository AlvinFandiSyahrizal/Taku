<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Toko — Taku</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'DM Sans',sans-serif;background:#f5f1e8;color:#2c1810;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;}
    .container{width:100%;max-width:520px;}
    .back-link{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:rgba(44,24,16,0.45);text-decoration:none;margin-bottom:24px;letter-spacing:.06em;transition:color .2s;}
    .back-link:hover{color:#2c1810;}
    .card{background:white;border-radius:16px;border:.5px solid rgba(44,24,16,0.08);padding:40px;}
    .card-eyebrow{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c96a3d;margin-bottom:10px;}
    .card-title{font-family:'Cormorant Garamond',serif;font-weight:300;font-size:26px;color:#2c1810;margin-bottom:8px;}
    .card-sub{font-size:13px;color:rgba(44,24,16,0.5);margin-bottom:24px;line-height:1.7;}
    .reject-box{background:#fdf0f0;border:.5px solid rgba(192,57,43,0.2);border-radius:10px;padding:14px 16px;margin-bottom:24px;}
    .reject-label{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:#c0392b;margin-bottom:6px;}
    .reject-reason{font-size:13px;color:rgba(44,24,16,0.7);line-height:1.6;}
    .form-group{margin-bottom:18px;}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-label{display:block;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:rgba(44,24,16,0.45);margin-bottom:7px;}
    .form-label span{color:#c96a3d;}
    .form-input,.form-textarea{width:100%;padding:11px 14px;border:.5px solid rgba(44,24,16,0.15);border-radius:8px;font-size:13px;color:#2c1810;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;box-sizing:border-box;}
    .form-input:focus,.form-textarea:focus{border-color:#c96a3d;}
    .form-textarea{resize:vertical;min-height:100px;}
    .char-count{float:right;font-size:10px;color:rgba(44,24,16,0.3);}
    .field-error{font-size:12px;color:#c0392b;margin-top:4px;}
    .name-display{padding:11px 14px;background:#f9f6f0;border:.5px solid rgba(44,24,16,0.08);border-radius:8px;font-size:13px;color:rgba(44,24,16,0.5);}
    .name-hint{font-size:11px;color:rgba(44,24,16,0.35);margin-top:4px;}
    .btn-submit{width:100%;background:#c96a3d;color:#f5f1e8;border:none;border-radius:8px;padding:14px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;margin-top:8px;}
    .btn-submit:hover{background:#b85c33;}
    @media(max-width:500px){.form-row{grid-template-columns:1fr;}.card{padding:24px;}}
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('store.pending') }}" class="back-link">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke status toko
    </a>

    <div class="card">
        <p class="card-eyebrow">Ajukan Ulang</p>
        <h1 class="card-title">Perbarui Info Toko</h1>
        <p class="card-sub">Perbaiki informasi toko sesuai catatan admin, lalu ajukan kembali.</p>

        <div class="reject-box">
            <p class="reject-label">Catatan dari Admin</p>
            <p class="reject-reason">{{ $store->reject_reason }}</p>
        </div>

        @if($errors->any())
        <div style="background:#fdf0f0;border:.5px solid #f5c6c6;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;">
            <ul style="padding-left:16px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('store.resubmit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Toko</label>
                <div class="name-display">{{ $store->name }}</div>
                <p class="name-hint">Nama toko tidak bisa diubah.</p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp <span>*</span></label>
                    <input type="text" name="phone" class="form-input"
                           value="{{ old('phone', $store->phone) }}" required>
                    @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kota <span>*</span></label>
                    <input type="text" name="city" class="form-input"
                           value="{{ old('city', $store->city) }}" required>
                    @error('city') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Deskripsi Toko <span>*</span>
                    <span class="char-count" id="descCount">{{ strlen(old('description', $store->description)) }}/500</span>
                </label>
                <textarea name="description" class="form-textarea" id="descInput"
                          required minlength="30" maxlength="500"
                          oninput="document.getElementById('descCount').textContent=this.value.length+'/500'">{{ old('description', $store->description) }}</textarea>
                @error('description') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit">Ajukan Ulang</button>
        </form>
    </div>
</div>
</body>
</html>