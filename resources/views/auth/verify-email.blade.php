@extends('layouts.app')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --navy: #0b2a4a;
    --gold: #c9a96e;
    --beige: #f5efe6;
    --beige-mid: #ede5d8;
    --navy-mid: rgba(11,42,74,.55);
    --navy-soft: rgba(11,42,74,.08);
    --danger: #c0392b;
    --success-green: #1a7a3c;
}

* { box-sizing: border-box; }

.verify-wrap {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    font-family: 'DM Sans', sans-serif;
    background: var(--beige);
}

.verify-card {
    background: white;
    border-radius: 20px;
    padding: 48px 44px;
    max-width: 480px;
    width: 100%;
    box-shadow: 0 8px 48px rgba(11,42,74,.09);
    border: .5px solid rgba(201,169,110,.2);
    text-align: center;
}

.verify-icon-wrap {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--beige), #ede5d8);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
    border: .5px solid rgba(201,169,110,.25);
}

.verify-label {
    font-size: 10px; letter-spacing: .22em;
    text-transform: uppercase; color: var(--gold); margin-bottom: 8px;
}

.verify-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 30px; font-weight: 400; color: var(--navy);
    margin-bottom: 14px; line-height: 1.2;
}

.verify-desc { font-size: 14px; color: var(--navy-mid); line-height: 1.7; margin-bottom: 8px; }

.verify-email-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--navy-soft); border-radius: 100px;
    padding: 5px 14px; font-size: 13px; font-weight: 500;
    color: var(--navy); margin-bottom: 28px;
}

.verify-divider { height: .5px; background: var(--beige-mid); margin: 24px 0; }

.flash-success {
    background:#f0f7f0; border:.5px solid #b2d9b2; border-radius:10px;
    padding:12px 16px; font-size:13px; color:var(--success-green);
    margin-bottom:20px; display:flex; align-items:flex-start; gap:8px; text-align:left;
}
.flash-warning {
    background:#fffbf0; border:.5px solid #f0d070; border-radius:10px;
    padding:12px 16px; font-size:13px; color:#7a5c00;
    margin-bottom:20px; display:flex; align-items:flex-start; gap:8px; text-align:left;
}
.flash-error {
    background:#fdf0f0; border:.5px solid #f5c0c0; border-radius:10px;
    padding:12px 16px; font-size:13px; color:var(--danger);
    margin-bottom:20px; display:flex; align-items:flex-start; gap:8px; text-align:left;
}

.cooldown-box {
    background: linear-gradient(135deg, #fffbf0, #fff8e8);
    border: .5px solid rgba(201,169,110,.35);
    border-radius: 12px; padding: 18px 20px; margin-bottom: 20px;
}
.cooldown-label {
    font-size: 11px; letter-spacing: .1em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 8px;
}
.cooldown-timer {
    font-family: 'Cormorant Garamond', serif;
    font-size: 44px; color: var(--navy); font-weight: 500;
    letter-spacing: .06em; line-height: 1; margin-bottom: 6px;
}
.cooldown-desc { font-size: 12px; color: var(--navy-mid); margin-bottom: 12px; }
.cooldown-progress-wrap {
    background: var(--beige-mid); border-radius: 100px; height: 4px; overflow: hidden;
}
.cooldown-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--gold), #e8c48a);
    border-radius: 100px;
    transition: width 1s linear;
}

.btn-resend {
    width: 100%; padding: 13px 24px; background: var(--navy);
    color: #f5efe6; border: none; border-radius: 10px;
    font-size: 11px; letter-spacing: .14em; text-transform: uppercase;
    font-weight: 500; font-family: 'DM Sans', sans-serif;
    cursor: pointer; transition: all .25s ease;
    position: relative; overflow: hidden;
}
.btn-resend::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0;
    height: 2px; background: linear-gradient(90deg, transparent, var(--gold), transparent);
    opacity: 0; transition: opacity .3s;
}
.btn-resend:hover:not(:disabled) {
    background: #0d3459;
    box-shadow: 0 8px 24px rgba(11,42,74,.22);
    transform: translateY(-1px);
}
.btn-resend:hover:not(:disabled)::after { opacity: 1; }
.btn-resend:disabled {
    background: var(--beige-mid); color: rgba(11,42,74,.35);
    cursor: not-allowed; transform: none; box-shadow: none;
}

.verify-footer { margin-top: 24px; font-size: 12px; color: var(--navy-mid); }

@media (max-width: 480px) {
    .verify-card { padding: 36px 20px; }
    .verify-title { font-size: 26px; }
    .cooldown-timer { font-size: 36px; }
}
</style>

<div class="verify-wrap">
    <div class="verify-card">

        <div class="verify-icon-wrap">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c9a96e" stroke-width="1.5">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>

        <p class="verify-label">Taku</p>
        <h1 class="verify-title">Verifikasi Email Kamu</h1>
        <p class="verify-desc">Link verifikasi dikirim ke:</p>

        <div class="verify-email-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
            {{ auth()->user()->email }}
        </div>

        <p class="verify-desc" style="margin-bottom:0;">
            Klik link di email untuk mengaktifkan akun.<br>
            Tidak masuk? Cek folder <strong>spam</strong>.
        </p>

        <div class="verify-divider"></div>

        @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('warning'))
        <div class="flash-warning">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('warning') }}
        </div>
        @endif

        @if($errors->any())
        <div class="flash-error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        @php $inCooldown = isset($sisaDetik) && $sisaDetik > 0; @endphp

        @if($inCooldown)
        <div class="cooldown-box" id="cooldownBox">
            <p class="cooldown-label">Kirim ulang tersedia dalam</p>
            <div class="cooldown-timer" id="cooldownTimer">--:--</div>
            <p class="cooldown-desc">Cek inbox atau folder spam sambil menunggu</p>
            <div class="cooldown-progress-wrap">
                <div class="cooldown-progress-bar" id="cooldownBar" style="width:100%"></div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
            @csrf
            <button type="submit" class="btn-resend" id="btnResend"
                    {{ $inCooldown ? 'disabled' : '' }}>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <div class="verify-divider"></div>

        <div class="verify-footer">
            Email salah?
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit"
                    style="background:none;border:none;cursor:pointer;font-size:12px;font-family:'DM Sans',sans-serif;padding:0;color:var(--navy);font-weight:500;transition:color .2s;"
                    onmouseover="this.style.color='#c9a96e'"
                    onmouseout="this.style.color='var(--navy)'">
                    Logout dan daftar ulang
                </button>
            </form>
        </div>

    </div>
</div>

<script>
(function () {
    {{-- Sisa detik dihitung server dari (now - email_resend_at), akurat saat pindah halaman --}}
    const SISA = {{ isset($sisaDetik) ? (int)$sisaDetik : 0 }};
    const TOTAL = 30 * 60;

    if (SISA <= 0) return;

    let detik = SISA;

    const timerEl = document.getElementById('cooldownTimer');
    const barEl   = document.getElementById('cooldownBar');
    const btnEl   = document.getElementById('btnResend');
    const boxEl   = document.getElementById('cooldownBox');

    function fmt(s) {
        return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
    }

    function tick() {
        if (detik <= 0) {
            if (boxEl) boxEl.style.display = 'none';
            if (btnEl) { btnEl.disabled = false; btnEl.textContent = 'Kirim Ulang Email Verifikasi'; }
            return;
        }
        if (timerEl) timerEl.textContent = fmt(detik);
        if (barEl)   barEl.style.width   = Math.max(0, (detik / TOTAL) * 100) + '%';
        detik--;
        setTimeout(tick, 1000);
    }

    tick();
})();
</script>

@endsection
