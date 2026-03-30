@extends('layouts.app')

@section('content')

<section style="background:#0b2a4a; color:white; padding:60px 0; text-align:center;">
    <h1>MAU CARI APA WOK</h1>
    <input type="text" placeholder="cepatkan bayar wok" style="padding:10px; width:300px;">
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
            onmouseout="this.style.transform='scale(1)'"
            >

                <img src="{{ asset($item['image']) }}" width="100%" style="height:150px; object-fit:cover;">

                <div style="padding:10px;">
                    <h4>{{ $item['name'] }}</h4>
                    <p style="font-size:14px; color:gray;">{{ $item['desc'] }}</p>
                    <strong style="color:#0b2a4a;">{{ $item['price'] }}</strong>
                </div>

            </div>

        </a>

        @endforeach

    </div>
</section>

<a href="https://wa.me/628xxxxxxxxxx" target="_blank" style="
    position:fixed;
    bottom:20px;
    right:20px;
    background:green;
    color:white;
    padding:15px;
    border-radius:50%;
    text-decoration:none;
">
    💬
</a>

@endsection
