@extends('layouts.app')
@section('content')
@if(config('services.turnstile.site_key'))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

@endif
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.auth-wrap { min-height:calc(100vh - 64px); display:flex; align-items:center; justify-content:center; background:#f8f6f2; padding:40px 16px; font-family:'DM Sans',sans-serif; }
.auth-card { background:white; border-radius:20px; border:0.5px solid rgba(11,42,74,0.08); padding:48px 44px; width:100%; max-width:420px; box-shadow:0 8px 40px rgba(11,42,74,0.06); }
.auth-brand { font-size:10px; letter-spacing:0.22em; text-transform:uppercase; color:#c9a96e; margin-bottom:6px; }
.auth-title { font-family:'Cormorant Garamond',serif; font-weight:400; font-size:32px; color:#0b2a4a; margin-bottom:6px; line-height:1.1; }
.auth-subtitle { font-size:12px; color:rgba(11,42,74,0.4); margin-bottom:28px; letter-spacing:0.04em; }
.auth-field { margin-bottom:16px; }
.auth-label { display:block; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:rgba(11,42,74,0.45); margin-bottom:8px; }
.auth-input-wrap { position:relative; }
.auth-input { width:100%; padding:12px 14px; border:0.5px solid rgba(11,42,74,0.18); border-radius:8px; font-size:14px; color:#0b2a4a; font-family:'DM Sans',sans-serif; outline:none; transition:border-color 0.2s; background:white; }
.auth-input:focus { border-color:#c9a96e; }
.auth-input.has-toggle { padding-right:44px; }
.auth-input::placeholder { color:rgba(11,42,74,0.25); }
.eye-toggle { position:absolute; right:13px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:rgba(11,42,74,0.35); display:flex; align-items:center; padding:0; transition:color 0.2s; }
.eye-toggle:hover { color:#0b2a4a; }
.auth-error { background:#fdf0f0; border:0.5px solid #f5c0c0; border-radius:8px; padding:12px 16px; font-size:13px; color:#c0392b; margin-bottom:20px; }
.field-error { font-size:12px; color:#c0392b; margin-top:4px; }

.pw-strength { margin-top:8px; display:flex; gap:4px; }
.pw-bar { flex:1; height:3px; border-radius:100px; background:rgba(11,42,74,0.08); transition:background 0.3s; }
.pw-hint { font-size:11px; color:rgba(11,42,74,0.4); margin-top:6px; line-height:1.6; }
.pw-hint span { color:rgba(11,42,74,0.25); }
.pw-hint span.ok { color:#27ae60; }

.auth-btn { width:100%; padding:14px; background:#0b2a4a; color:#f0ebe0; border:none; border-radius:8px; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background 0.2s; margin-top:8px; }
.auth-btn:hover { background:#0d3459; }
.auth-divider { display:flex; align-items:center; gap:12px; margin:24px 0; color:rgba(11,42,74,0.2); font-size:12px; }
.auth-divider::before,.auth-divider::after { content:''; flex:1; height:0.5px; background:rgba(11,42,74,0.1); }
.auth-link-row { text-align:center; font-size:13px; color:rgba(11,42,74,0.45); }
.auth-link { color:#0b2a4a; text-decoration:none; font-weight:500; border-bottom:0.5px solid rgba(11,42,74,0.3); transition:border-color 0.2s; }
.auth-link:hover { border-color:#c9a96e; color:#c9a96e; }
</style>

<div class="auth-wrap">
    <div class="auth-card">
        <p class="auth-brand">Taku</p>
        <h1 class="auth-title">{{ app()->getLocale()==='en' ? 'Create Account' : 'Buat Akun' }}</h1>
        <p class="auth-subtitle">{{ app()->getLocale()==='en' ? 'Join Taku and start shopping' : 'Bergabung dengan Taku dan mulai berbelanja' }}</p>

        @if($errors->any())
            <div class="auth-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="auth-field">
                <label class="auth-label">{{ app()->getLocale()==='en' ? 'Full Name' : 'Nama Lengkap' }}</label>
                <input type="text" name="name" class="auth-input" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label">Email</label>
                <input type="email" name="email" class="auth-input" value="{{ old('email') }}" placeholder="nama@email.com" required>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label">{{ app()->getLocale()==='en' ? 'Password' : 'Kata Sandi' }}</label>
                <div class="auth-input-wrap">
                    <input type="password" name="password" id="regPassword" class="auth-input has-toggle"
                           placeholder="Min. 8 karakter" required oninput="checkStrength(this.value)">
                    <button type="button" class="eye-toggle" onclick="toggleEye('regPassword',this)" tabindex="-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="pw-strength">
                    <div class="pw-bar" id="bar1"></div>
                    <div class="pw-bar" id="bar2"></div>
                    <div class="pw-bar" id="bar3"></div>
                    <div class="pw-bar" id="bar4"></div>
                </div>
                <p class="pw-hint">
                    <span id="req-len">✓ Min. 8 karakter</span> ·
                    <span id="req-upper">✓ Huruf besar</span> ·
                    <span id="req-num">✓ Angka</span> ·
                    <span id="req-sym">✓ Simbol (@$!%*#?&)</span>
                </p>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label">{{ app()->getLocale()==='en' ? 'Confirm Password' : 'Konfirmasi Kata Sandi' }}</label>
                <div class="auth-input-wrap">
                    <input type="password" name="password_confirmation" id="regPasswordConfirm"
                           class="auth-input has-toggle" placeholder="••••••••" required>
                    <button type="button" class="eye-toggle" onclick="toggleEye('regPasswordConfirm',this)" tabindex="-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
                <div class="form-group" style="margin-top:8px;">
                    <div class="cf-turnstile"
                        data-sitekey="{{ config('services.turnstile.site_key') }}"
                        data-theme="light"
                        data-language="id">
                    </div>
                    @error('cf-turnstile-response')
                        <p class="field-error">Verifikasi gagal. Coba lagi.</p>
                    @enderror
                </div>
            <button type="submit" class="auth-btn">
                {{ app()->getLocale()==='en' ? 'Create Account' : 'Buat Akun' }}
            </button>
        </form>

        <div class="auth-divider">atau</div>
        <p class="auth-link-row">
            {{ app()->getLocale()==='en' ? 'Already have an account?' : 'Sudah punya akun?' }}
            <a href="{{ route('login') }}" class="auth-link">{{ app()->getLocale()==='en' ? 'Sign In' : 'Masuk' }}</a>
        </p>
    </div>
</div>

<script>
function toggleEye(id, btn) {
    const input = document.getElementById(id);
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    btn.innerHTML = isPass
        ? `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
}

function checkStrength(val) {
    const checks = {
        len:   val.length >= 8,
        upper: /[A-Z]/.test(val),
        num:   /[0-9]/.test(val),
        sym:   /[@$!%*#?&]/.test(val),
    };
    const score = Object.values(checks).filter(Boolean).length;
    const colors = ['', '#c0392b', '#e67e22', '#f0c040', '#27ae60'];
    const bars   = [document.getElementById('bar1'),document.getElementById('bar2'),document.getElementById('bar3'),document.getElementById('bar4')];

    bars.forEach((b, i) => {
        b.style.background = i < score ? colors[score] : 'rgba(11,42,74,0.08)';
    });

    ['len','upper','num','sym'].forEach(k => {
        const el = document.getElementById('req-'+k);
        el.className = checks[k] ? 'ok' : '';
    });
}
</script>

@endsection
