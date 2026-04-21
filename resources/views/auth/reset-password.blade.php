@extends('layouts.app')
@section('robots', 'noindex, nofollow')
@section('title', 'Reset Password — Taku')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.auth-wrap{min-height:calc(100vh - 64px);display:flex;align-items:center;justify-content:center;background:#f8f6f2;padding:40px 16px;font-family:'DM Sans',sans-serif;}
.auth-card{background:white;border-radius:20px;border:0.5px solid rgba(11,42,74,0.08);padding:48px 44px;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(11,42,74,0.06);}
.auth-brand{font-size:10px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:6px;}
.auth-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:28px;color:#0b2a4a;margin-bottom:28px;}
.auth-field{margin-bottom:18px;}
.auth-label{display:block;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(11,42,74,0.45);margin-bottom:8px;}
.auth-input-wrap{position:relative;}
.auth-input{width:100%;padding:12px 14px;border:0.5px solid rgba(11,42,74,0.18);border-radius:8px;font-size:14px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;background:white;}
.auth-input:focus{border-color:#c9a96e;}
.auth-input.has-toggle{padding-right:44px;}
.eye-toggle{position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(11,42,74,0.35);display:flex;align-items:center;padding:0;}
.auth-btn{width:100%;padding:14px;background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.2s;}
.auth-btn:hover{background:#0d3459;}
.field-error{font-size:12px;color:#c0392b;margin-top:4px;}
</style>

<div class="auth-wrap">
    <div class="auth-card">
        <p class="auth-brand">Taku</p>
        <h1 class="auth-title">Reset Password</h1>

        @if($errors->any())
            <div style="background:#fdf0f0;border:0.5px solid #f5c0c0;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="auth-field">
                <label class="auth-label">Email</label>
                <input type="email" name="email" class="auth-input" value="{{ old('email') }}" required autofocus>
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label">Password Baru</label>
                <div class="auth-input-wrap">
                    <input type="password" name="password" id="newPw" class="auth-input has-toggle" placeholder="Min. 8 karakter" required>
                    <button type="button" class="eye-toggle" onclick="toggleEye('newPw',this)" tabindex="-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label">Konfirmasi Password</label>
                <div class="auth-input-wrap">
                    <input type="password" name="password_confirmation" id="confirmPw" class="auth-input has-toggle" placeholder="••••••••" required>
                    <button type="button" class="eye-toggle" onclick="toggleEye('confirmPw',this)" tabindex="-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-btn">Reset Password</button>
        </form>
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
</script>
@endsection
