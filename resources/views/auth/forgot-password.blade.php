@extends('layouts.app')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}
.auth-wrap{min-height:calc(100vh - 64px);display:flex;align-items:center;justify-content:center;background:#f8f6f2;padding:40px 16px;font-family:'DM Sans',sans-serif;}
.auth-card{background:white;border-radius:20px;border:0.5px solid rgba(11,42,74,0.08);padding:48px 44px;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(11,42,74,0.06);}
.auth-brand{font-size:10px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a96e;margin-bottom:6px;}
.auth-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:28px;color:#0b2a4a;margin-bottom:8px;line-height:1.1;}
.auth-sub{font-size:13px;color:rgba(11,42,74,0.5);margin-bottom:28px;line-height:1.6;}
.auth-label{display:block;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(11,42,74,0.45);margin-bottom:8px;}
.auth-input{width:100%;padding:12px 14px;border:0.5px solid rgba(11,42,74,0.18);border-radius:8px;font-size:14px;color:#0b2a4a;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;background:white;}
.auth-input:focus{border-color:#c9a96e;}
.auth-btn{width:100%;padding:14px;background:#0b2a4a;color:#f0ebe0;border:none;border-radius:8px;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.2s;margin-top:16px;}
.auth-btn:hover{background:#0d3459;}
.success-box{background:#f0f7f0;border:0.5px solid #b2d9b2;border-radius:8px;padding:12px 16px;font-size:13px;color:#2d6a2d;margin-bottom:20px;}
.back-link{display:block;text-align:center;margin-top:16px;font-size:12px;color:rgba(11,42,74,0.4);text-decoration:none;letter-spacing:0.06em;transition:color 0.2s;}
.back-link:hover{color:#0b2a4a;}
.field-error{font-size:12px;color:#c0392b;margin-top:4px;}
</style>

<div class="auth-wrap">
    <div class="auth-card">
        <p class="auth-brand">Taku</p>
        <h1 class="auth-title">Lupa Password?</h1>
        <p class="auth-sub">Masukkan email kamu dan kami akan mengirimkan link untuk reset password.</p>

        @if(session('success'))
            <div class="success-box">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div style="background:#fdf0f0;border:0.5px solid #f5c0c0;border-radius:8px;padding:12px 16px;font-size:13px;color:#c0392b;margin-bottom:20px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label class="auth-label">Email</label>
            <input type="email" name="email" class="auth-input" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
            <button type="submit" class="auth-btn">Kirim Link Reset</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Kembali ke halaman login</a>
    </div>
</div>
@endsection
