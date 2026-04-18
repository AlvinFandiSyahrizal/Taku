@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

@if(config('services.turnstile.site_key'))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.auth-wrap{min-height:calc(100vh - 64px);display:flex;align-items:center;justify-content:center;background:#f8f6f2;padding:40px 16px;font-family:'DM Sans',sans-serif;}
.auth-card{background:white;border-radius:20px;border:.5px solid rgba(11,42,74,.08);padding:48px 44px;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(11,42,74,.06);}
.auth-brand{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:6px;}
.auth-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:32px;color:#0b2a4a;margin-bottom:32px;line-height:1.1;}
.auth-field{margin-bottom:18px;}
.auth-label{display:block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(11,42,74,.45);margin-bottom:8px;}
.auth-input-wrap{position:relative;}
.auth-input{width:100%;padding:12px 14px;border:.5px solid rgba(11,42,74,.18);border-radius:8px;font-size:14px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;background:white;}
.auth-input:focus{border-color:#c9a96e;}
.auth-input::placeholder{color:rgba(11,42,74,.25);}
.auth-input.has-toggle{padding-right:44px;}
.eye-toggle{position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(11,42,74,.35);display:flex;align-items:center;padding:0;transition:color .2s;}
.eye-toggle:hover{color:#0b2a4a;}

/* Error dan cooldown */
.auth-error{background:#fdf0f0;border:.5px solid #f5c0c0;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;}
.cooldown-box{background:#fff8e6;border:.5px solid #f0d080;border-radius:8px;padding:12px 16px;font-size:13px;color:#7a5c00;margin-bottom:20px;display:flex;align-items:center;gap:10px;}
.cooldown-timer{font-weight:600;color:#c9a96e;font-size:16px;min-width:28px;text-align:center;}

.auth-remember{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(11,42,74,.5);margin-bottom:8px;cursor:pointer;}
.auth-remember input{cursor:pointer;accent-color:#0b2a4a;}
.auth-forgot{display:block;text-align:right;font-size:12px;color:rgba(11,42,74,.4);text-decoration:none;margin-bottom:20px;letter-spacing:.04em;transition:color .2s;}
.auth-forgot:hover{color:#c9a96e;}
.auth-btn{width:100%;padding:14px;background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;}
.auth-btn:hover:not(:disabled){background:#0d3459;}
.auth-btn:disabled{background:rgba(11,42,74,.3);cursor:not-allowed;}
.auth-divider{display:flex;align-items:center;gap:12px;margin:24px 0;color:rgba(11,42,74,.2);font-size:12px;}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:.5px;background:rgba(11,42,74,.1);}
.auth-link-row{text-align:center;font-size:13px;color:rgba(11,42,74,.45);}
.auth-link{color:#0b2a4a;text-decoration:none;font-weight:500;border-bottom:.5px solid rgba(11,42,74,.3);transition:border-color .2s;}
.auth-link:hover{border-color:#c9a96e;color:#c9a96e;}
.field-error{font-size:12px;color:#c0392b;margin-top:5px;}
</style>

<div class="auth-wrap">
    <div class="auth-card">
        <p class="auth-brand">Taku</p>
        <h1 class="auth-title">{{ app()->getLocale()==='en' ? 'Sign In' : 'Masuk' }}</h1>

        {{-- Error biasa --}}
        @if($errors->has('email') && !str_contains($errors->first('email'), 'detik'))
            <div class="auth-error">{{ $errors->first('email') }}</div>
        @endif

        {{-- Cooldown box --}}
        @if($errors->has('email') && str_contains($errors->first('email'), 'detik'))
        @php
            // Ambil sisa detik dari pesan error
            preg_match('/(\d+) detik/', $errors->first('email'), $m);
            $remainingSeconds = (int)($m[1] ?? 60);
        @endphp
        <div class="cooldown-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Terlalu banyak percobaan. Coba lagi dalam</span>
            <span class="cooldown-timer" id="cooldownTimer">{{ $remainingSeconds }}</span>
            <span>detik</span>
        </div>
        @endif

        {{-- Error Turnstile --}}
        @error('cf-turnstile-response')
            <div class="auth-error">{{ $message }}</div>
        @enderror

        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
            @csrf

            <div class="auth-field">
                <label class="auth-label">Email</label>
                <input type="email" name="email" class="auth-input"
                       value="{{ old('email') }}" placeholder="nama@email.com"
                       required autofocus>
            </div>

            <div class="auth-field">
                <label class="auth-label">{{ app()->getLocale()==='en' ? 'Password' : 'Kata Sandi' }}</label>
                <div class="auth-input-wrap">
                    <input type="password" name="password" id="loginPassword"
                           class="auth-input has-toggle" placeholder="••••••••" required>
                    <button type="button" class="eye-toggle"
                            onclick="toggleEye('loginPassword', this)" tabindex="-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Turnstile --}}
            @if(config('services.turnstile.site_key'))
            <div style="margin-bottom:16px;">
                <div class="cf-turnstile"
                     data-sitekey="{{ config('services.turnstile.site_key') }}"
                     data-theme="light"
                     data-language="id">
                </div>
            </div>
            @endif

            <label class="auth-remember">
                <input type="checkbox" name="remember">
                {{ app()->getLocale()==='en' ? 'Remember me' : 'Ingat saya' }}
            </label>
            <a href="{{ route('password.request') }}" class="auth-forgot">
                {{ app()->getLocale()==='en' ? 'Forgot password?' : 'Lupa password?' }}
            </a>

            <button type="submit" class="auth-btn" id="loginBtn">
                {{ app()->getLocale()==='en' ? 'Sign In' : 'Masuk' }}
            </button>
        </form>

        <div class="auth-divider">atau</div>
        <p class="auth-link-row">
            {{ app()->getLocale()==='en' ? "Don't have an account?" : 'Belum punya akun?' }}
            <a href="{{ route('register') }}" class="auth-link">
                {{ app()->getLocale()==='en' ? 'Register' : 'Daftar' }}
            </a>
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

// Cooldown timer countdown
const timerEl = document.getElementById('cooldownTimer');
const loginBtn = document.getElementById('loginBtn');

if (timerEl) {
    let secs = parseInt(timerEl.textContent);
    loginBtn.disabled = true;

    const tick = setInterval(() => {
        secs--;
        timerEl.textContent = secs;
        if (secs <= 0) {
            clearInterval(tick);
            loginBtn.disabled = false;
            timerEl.closest('.cooldown-box').style.opacity = '0.5';
            loginBtn.textContent = '{{ app()->getLocale()==="en" ? "Try Again" : "Coba Lagi" }}';
        }
    }, 1000);
}
</script>

@endsection