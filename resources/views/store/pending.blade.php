<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Toko — Taku</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'DM Sans',sans-serif;background:#f5f1e8;color:#2c1810;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px;}
    .container{width:100%;max-width:480px;}
    .logo{font-family:'Cormorant Garamond',serif;font-weight:300;font-size:18px;color:rgba(44,24,16,0.4);letter-spacing:.18em;text-transform:uppercase;text-align:center;margin-bottom:28px;}
    .card{background:white;border-radius:16px;border:.5px solid rgba(44,24,16,0.08);padding:36px;text-align:center;}
    .icon-wrap{width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
    .store-name{font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:300;color:#2c1810;margin-bottom:10px;}
    .status-badge{display:inline-block;padding:5px 16px;border-radius:100px;font-size:10px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;margin-bottom:16px;}
    .badge-pending{background:rgba(230,126,34,0.1);color:#e67e22;border:.5px solid rgba(230,126,34,0.2);}
    .badge-rejected{background:rgba(192,57,43,0.1);color:#c0392b;border:.5px solid rgba(192,57,43,0.2);}
    .badge-banned{background:rgba(44,24,16,0.08);color:rgba(44,24,16,0.5);border:.5px solid rgba(44,24,16,0.12);}
    .status-text{font-size:13px;color:rgba(44,24,16,0.55);line-height:1.7;margin-bottom:24px;}
    .reject-box{background:#fdf0f0;border:.5px solid rgba(192,57,43,0.2);border-radius:10px;padding:16px;margin-bottom:20px;text-align:left;}
    .reject-label{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:#c0392b;margin-bottom:6px;}
    .reject-reason{font-size:13px;color:rgba(44,24,16,0.7);line-height:1.6;}
    .reject-counter{display:flex;gap:6px;justify-content:center;margin-bottom:20px;}
    .reject-dot{width:8px;height:8px;border-radius:50%;background:rgba(44,24,16,0.1);}
    .reject-dot.used{background:#c0392b;}
    .countdown-box{background:#f9f6f0;border:.5px solid rgba(201,106,61,0.2);border-radius:10px;padding:14px;margin-bottom:20px;font-size:12px;color:rgba(44,24,16,0.55);}
    .countdown-box strong{color:#c96a3d;}
    .actions{display:flex;flex-direction:column;gap:10px;}
    .btn-primary{background:#c96a3d;color:#f5f1e8;border:none;border-radius:8px;padding:12px 24px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-block;transition:background .2s;}
    .btn-primary:hover{background:#b85c33;}
    .btn-outline{background:none;border:.5px solid rgba(44,24,16,0.15);border-radius:8px;padding:12px 24px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(44,24,16,0.5);cursor:pointer;font-family:'DM Sans',sans-serif;text-decoration:none;display:inline-block;transition:all .2s;}
    .btn-outline:hover{color:#2c1810;border-color:rgba(44,24,16,0.3);}
    .btn-danger{background:none;border:.5px solid rgba(192,57,43,0.2);border-radius:8px;padding:10px 24px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#c0392b;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;}
    .btn-danger:hover{background:#c0392b;color:white;}
    .divider{height:.5px;background:rgba(44,24,16,0.06);margin:16px 0;}
    .flash-success{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:11px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;text-align:left;}
    </style>
</head>
<body>
<div class="container">
    <p class="logo">Taku</p>

    @if(session('success'))
    <div class="flash-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        @php
            $isBanned   = $store->isRejectionBanned() || $store->status === 'banned';
            $isRejected = $store->reject_reason && !$isBanned;
            $canEdit    = $store->canResubmit();
            $daysLeft   = $store->daysUntilResubmit();
            $count      = $store->rejection_count;
        @endphp

        <div class="icon-wrap" style="background:{{ $isBanned ? 'rgba(44,24,16,0.06)' : ($isRejected ? 'rgba(192,57,43,0.08)' : 'rgba(201,106,61,0.08)') }}">
            @if($isBanned)
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(44,24,16,0.4)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
            @elseif($isRejected)
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            @else
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c96a3d" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            @endif
        </div>

        <p class="store-name">{{ $store->name }}</p>

        @if($isBanned)
            <span class="status-badge badge-banned">Tidak Dapat Mendaftar</span>
            <p class="status-text">Pengajuan toko kamu telah ditolak sebanyak 3 kali. Akun kamu tidak dapat mengajukan toko baru. Hubungi support jika ada pertanyaan.</p>

        @elseif($isRejected)
            <span class="status-badge badge-rejected">Perlu Perbaikan</span>

            <div class="reject-counter">
                @for($i = 1; $i <= 3; $i++)
                    <div class="reject-dot {{ $i <= $count ? 'used' : '' }}" title="Penolakan ke-{{ $i }}"></div>
                @endfor
            </div>
            <p style="font-size:11px;color:rgba(44,24,16,0.4);margin-bottom:16px;letter-spacing:.04em;">
                {{ $count }}/3 penolakan — {{ 3 - $count }} kesempatan tersisa
            </p>

            <div class="reject-box">
                <p class="reject-label">Catatan dari Admin</p>
                <p class="reject-reason">{{ $store->reject_reason }}</p>
            </div>

            @if($canEdit)
                <p class="status-text">Perbarui informasi toko kamu sesuai catatan admin, lalu ajukan kembali.</p>
                <div class="actions">
                    <a href="{{ route('store.edit') }}" class="btn-primary">Perbarui & Ajukan Ulang</a>
                    <div class="divider"></div>
                    <form action="{{ route('store.cancel') }}" method="POST"
                          onsubmit="return confirm('Batalkan pengajuan toko ini?')">
                        @csrf
                        <button type="submit" class="btn-danger">Batalkan Pengajuan</button>
                    </form>
                </div>
            @else
                <div class="countdown-box">
                    ⏳ Kamu bisa mengajukan ulang dalam <strong>{{ $daysLeft }} hari lagi</strong> (7 hari setelah penolakan).
                </div>
                <div class="actions">
                    <a href="{{ route('home') }}" class="btn-outline">Kembali ke Toko</a>
                    <form action="{{ route('store.cancel') }}" method="POST"
                          onsubmit="return confirm('Batalkan pengajuan toko ini?')">
                        @csrf
                        <button type="submit" class="btn-danger">Batalkan Pengajuan</button>
                    </form>
                </div>
            @endif

        @else
            <span class="status-badge badge-pending">Menunggu Persetujuan</span>
            <p class="status-text">Toko kamu sedang dalam proses peninjauan. Biasanya membutuhkan 1×24 jam. Kami akan menghubungi kamu jika ada update.</p>
            <div class="actions">
                <a href="{{ route('home') }}" class="btn-outline">Kembali ke Toko</a>
                <div class="divider"></div>
                <form action="{{ route('store.cancel') }}" method="POST"
                      onsubmit="return confirm('Yakin ingin membatalkan pengajuan toko?')">
                    @csrf
                    <button type="submit" class="btn-danger">Batalkan Pengajuan</button>
                </form>
            </div>
        @endif
    </div>
</div>
</body>
</html>