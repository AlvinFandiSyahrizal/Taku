@extends('layouts.app')

@section('content')

<section style="
    background: linear-gradient(160deg, #0b2a4a 0%, #0e3459 60%, #112f50 100%);
    min-height: 320px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 24px 48px;
    position: relative;
    overflow: hidden;
">

    <div style="
        position: absolute;
        width: 500px; height: 500px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.06) 0%, transparent 70%);
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
    "></div>

    <div style="display:flex; flex-direction:column; align-items:center; gap:18px; position:relative; z-index:1;">

        <img src="{{ asset('images/gambar1.jpg') }}" style="width:80px; height:auto; object-fit:contain; filter: drop-shadow(0 0 18px rgba(201,169,110,0.25));">

        {{-- Nama toko --}}
        {{-- <div style="
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            font-size: 38px;
            color: #f0ebe0;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            line-height: 1;
        ">TAKU</div> --}}

        <div style="width:36px; height:1px; background: linear-gradient(90deg, transparent, #c9a96e, transparent);"></div>

        {{-- <div style="
            font-size: 10px;
            color: rgba(201,169,110,0.7);
            letter-spacing: 0.28em;
            text-transform: uppercase;
            font-family: 'DM Sans', sans-serif;
        ">{{ session('lang') == 'en' ? 'Your Store' : 'Toko Kamu' }}</div>

    </div> --}}

    <form action="/products" method="GET" style="
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.07);
        border: 0.5px solid rgba(255,255,255,0.18);
        border-radius: 100px;
        padding: 10px 10px 10px 18px;
        gap: 10px;
        width: 340px;
        max-width: 90%;
        position: relative;
        z-index: 1;
        margin-top: 28px;
    ">
        <input type="text" name="q"
            placeholder="{{ session('lang') == 'en' ? 'Search products...' : 'Cari produk...' }}"
            style="background:none; border:none; outline:none; color:white; font-size:12px; font-family:'DM Sans',sans-serif; flex:1; min-width:0;">
        <button type="submit" style="
            background: #c9a96e;
            border: none;
            border-radius: 100px;
            color: #0b2a4a;
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 500;
            padding: 7px 18px;
            cursor: pointer;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        ">{{ session('lang') == 'en' ? 'Search' : 'Cari' }}</button>
    </form>

</section>

<section class="container" style="margin-top:40px;">
    <h2>Produk</h2>

    <div style="display:flex; gap:20px; overflow-x:auto; padding-bottom:10px;">

        @foreach($products as $index => $item)
        <a href="/product/{{ $index }}" style="text-decoration:none; color:black;">
            <div style="
                min-width:200px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 2px 8px rgba(0,0,0,0.1);
                transition:0.3s;
            "
            onmouseover="this.style.transform='scale(1.05)'"
            onmouseout="this.style.transform='scale(1)'">
                <img src="{{ asset($item['image']) }}" width="100%" style="height:150px; object-fit:cover;">
                <div style="padding:10px;">
                    <h4>{{ $item['name'] }}</h4>
                    <p style="font-size:14px; color:gray;">{{ $item['desc'][app()->getLocale()] }}</p>                    <strong style="color:#0b2a4a;">{{ $item['price'] }}</strong>
                </div>
            </div>
        </a>
        @endforeach

    </div>
</section>

<a href="https://wa.me/6281324683769" target="_blank" style="
    position:fixed;
    bottom:20px;
    right:20px;
    background:green;
    color:white;
    padding:15px;
    border-radius:50%;
    text-decoration:none;
">💬</a>

@endsection
