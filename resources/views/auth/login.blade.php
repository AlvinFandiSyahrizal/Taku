@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.auth-wrap { min-height:calc(100vh - 64px); display:flex; align-items:center; justify-content:center; background:#f8f6f2; padding:40px 16px; font-family:'DM Sans',sans-serif; }
.auth-card { background:white; border-radius:20px; border:0.5px solid rgba(11,42,74,0.08); padding:48px 44px; width:100%; max-width:420px; box-shadow:0 8px 40px rgba(11,42,74,0.06); }
.auth-brand { font-size:10px; letter-spacing:0.22em; text-transform:uppercase; color:#c9a96e; margin-bottom:6px; }
.auth-title { font-family:'Cormorant Garamond',serif; font-weight:400; font-size:32px; color:#0b2a4a; margin-bottom:32px; line-height:1.1; }
.auth-field { margin-bottom:18px; }
.auth-label { display:block; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:rgba(11,42,74,0.45); margin-bottom:8px; }
.auth-input-wrap { position:relative; }
.auth-input { width:100%; padding:12px 14px; border:0.5px solid rgba(11,42,74,0.18); border-radius:8px; font-size:14px; color:#0b2a4a; font-family:'DM Sans',sans-serif; outline:none; transition:border-color 0.2s; background:white; }
.auth-input:focus { border-color:#c9a96e; }
.auth-input::placeholder { color:rgba(11,42,74,0.25); }
.auth-input.has-toggle { padding-right:44px; }
.eye-toggle { position:absolute; right:13px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:rgba(11,42,74,0.35); display:flex; align-items:center; padding:0; transition:color 0.2s; }
.eye-toggle:hover { color:#0b2a4a; }
.auth-error { background:#fdf0f0; border:0.5px solid #f5c0c0; border-radius:8px; padding:12px 16px; font-size:13px; color:#c0392b; margin-bottom:20px; }
.auth-remember { display:flex; align-items:center; gap:8px; font-size:13px; color:rgba(11,42,74,0.5); margin-bottom:8px; cursor:pointer; }
.auth-remember input { cursor:pointer; accent-color:#0b2a4a; }
.auth-forgot { display:block; text-align:right; font-size:12px; color:rgba(11,42,74,0.4); text-decoration:none; margin-bottom:20px; letter-spacing:0.04em; transition:color 0.2s; }
.auth-forgot:hover { color:#c9a96e; }
.auth-btn { width:100%; padding:14px; background:#0b2a4a; color:#f0ebe0; border:none; border-radius:8px; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background 0.2s; }
.auth-btn:hover { background:#0d3459; }
.auth-divider { display:flex; align-items:center; gap:12px; margin:24px 0; color:rgba(11,42,74,0.2); font-size:12px; }
.auth-divider::before,.auth-divider::after { content:''; flex:1; height:0.5px; background:rgba(11,42,74,0.1); }
.auth-link-row { text-align:center; font-size:13px; color:rgba(11,42,74,0.45); }
.auth-link { color:#0b2a4a; text-decoration:none; font-weight:500; border-bottom:0.5px solid rgba(11,42,74,0.3); transition:border-color 0.2s; }
.auth-link:hover { border-color:#c9a96e; color:#c9a96e; }
.field-error { font-size:12px; color:#c0392b; margin-top:5px; }
</style>

<div class="auth-wrap">
    <div class="auth-card">
        <p class="auth-brand">Taku</p>
        <h1 class="auth-title">{{ app()->getLocale()==='en' ? 'Sign In' : 'Masuk' }}</h1>

        @if($errors->any())
            <div class="auth-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="auth-field">
                <label class="auth-label">Email</label>
                <input type="email" name="email" class="auth-input" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label">{{ app()->getLocale()==='en' ? 'Password' : 'Kata Sandi' }}</label>
                <div class="auth-input-wrap">
                    <input type="password" name="password" id="loginPassword" class="auth-input has-toggle" placeholder="••••••••" required>
                    <button type="button" class="eye-toggle" onclick="toggleEye('loginPassword', this)" tabindex="-1">
                        <svg id="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <label class="auth-remember">
                <input type="checkbox" name="remember">
                {{ app()->getLocale()==='en' ? 'Remember me' : 'Ingat saya' }}
            </label>
            <a href="{{ route('password.request') }}" class="auth-forgot">
                {{ app()->getLocale()==='en' ? 'Forgot password?' : 'Lupa password?' }}
            </a>

            <button type="submit" class="auth-btn">
                {{ app()->getLocale()==='en' ? 'Sign In' : 'Masuk' }}
            </button>
        </form>

        <div class="auth-divider">atau</div>
        <p class="auth-link-row">
            {{ app()->getLocale()==='en' ? "Don't have an account?" : 'Belum punya akun?' }}
            <a href="{{ route('register') }}" class="auth-link">{{ app()->getLocale()==='en' ? 'Register' : 'Daftar' }}</a>
        </p>
    </div>
</div>

<script>
function toggleEye(inputId, btn) {
    const input = document.getElementById(inputId);
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    btn.innerHTML = isPass
        ? `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
}
</script>
@endsection