@extends('layouts.app')

@section('content')

@php
    app()->setLocale(session('lang', 'id'));
    $locale = session('lang', 'id');
@endphp


<section style="
    background: linear-gradient(160deg, #0b2a4a 0%, #0e3459 60%, #112f50 100%);
    min-height: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px 24px 36px;
    position: relative;
    overflow: hidden;
">

    <div style="
        position: absolute;
        width: 400px; height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.05) 0%, transparent 70%);
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
    "></div>

    <div style="display:flex; flex-direction:column; align-items:center; gap:14px; position:relative; z-index:1;">

        <img
            src="{{ asset('images/gambar1.jpg') }}"
            style="
                width:auto;
                height:auto;
                object-fit: cover;
                border-radius: 10px;
                filter: drop-shadow(0 0 14px rgba(201,169,110,0.2));
            "
        >

        <form action="/products" method="GET" style="
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,0.07);
            border: 0.5px solid rgba(255,255,255,0.18);
            border-radius: 100px;
            padding: 8px 8px 8px 16px;
            gap: 10px;
            width: 320px;
            max-width: 90%;
        ">
            <input
                type="text"
                name="q"
                placeholder="{{ session('lang') == 'en' ? 'Search products...' : 'Cari produk...' }}"
                style="background:none; border:none; outline:none; color:white; font-size:12px; font-family:'DM Sans',sans-serif; flex:1; min-width:0;"
            >
            <button type="submit" style="
                background: #c9a96e;
                border: none;
                border-radius: 100px;
                color: #0b2a4a;
                font-size: 10px;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                font-weight: 500;
                padding: 7px 16px;
                cursor: pointer;
                white-space: nowrap;
                font-family: 'DM Sans', sans-serif;
            ">{{ session('lang') == 'en' ? 'Search' : 'Cari' }}</button>
        </form>

    </div>

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
                    <p style="font-size:14px; color:gray;">{{ $item['desc'][session('lang', 'id')] }}</p>                </div>
            </div>
        </a>
        @endforeach

    </div>
</section>

<a href="https://wa.me/6282124511773" target="_blank" style="
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
