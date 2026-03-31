@extends('layouts.app')

@section('content')

@php $locale = app()->getLocale(); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');

.pd-wrap {
    max-width: 1100px;
    margin: 56px auto 80px;
    padding: 0 32px;
    font-family: 'DM Sans', sans-serif;
}

.pd-flash {
    background: #f0f7f0;
    border: 0.5px solid #b2d9b2;
    border-radius: 8px;
    padding: 12px 20px;
    font-size: 13px;
    color: #2d6a2d;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.pd-flash-dot { width: 6px; height: 6px; border-radius: 50%; background: #2d6a2d; flex-shrink: 0; }

.pd-main { display: flex; gap: 60px; flex-wrap: wrap; align-items: flex-start; }

.pd-images {
    flex: 1; min-width: 300px;
    display: flex; flex-direction: column; align-items: center; gap: 16px;
    position: sticky; top: 84px;
}

.pd-main-img {
    width: 100%; max-width: 420px; aspect-ratio: 1;
    object-fit: cover; border-radius: 16px; display: block;
    transition: opacity 0.3s ease;
}

.pd-thumbnails { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }

.pd-thumb {
    width: 68px; height: 68px; object-fit: cover;
    border-radius: 8px; cursor: pointer;
    border: 1.5px solid transparent; opacity: 0.65;
    transition: opacity 0.2s, border-color 0.2s;
}
.pd-thumb:hover { opacity: 1; }
.pd-thumb.active { border-color: #c9a96e; opacity: 1; }

.pd-info { flex: 1; min-width: 300px; }

.pd-label { font-size: 10px; letter-spacing: 0.22em; text-transform: uppercase; color: #c9a96e; margin-bottom: 12px; }

.pd-name {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 400; font-size: 38px; color: #0b2a4a;
    letter-spacing: 0.02em; line-height: 1.1; margin-bottom: 12px;
}

.pd-price { font-size: 22px; font-weight: 500; color: #0b2a4a; margin-bottom: 28px; }

.pd-divider { height: 0.5px; background: rgba(11,42,74,0.1); margin: 24px 0; }

.pd-detail-label { font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(11,42,74,0.45); margin-bottom: 10px; }

.pd-detail-text { font-size: 14px; color: #555; line-height: 1.8; }

.pd-qty-label { font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(11,42,74,0.45); margin-bottom: 10px; }

.pd-qty-wrap {
    display: inline-flex; align-items: center;
    border: 0.5px solid rgba(11,42,74,0.2); border-radius: 8px; overflow: hidden;
}

.pd-qty-btn {
    background: none; border: none; cursor: pointer;
    width: 40px; height: 40px; font-size: 18px; color: #0b2a4a;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s; font-family: 'DM Sans', sans-serif;
}
.pd-qty-btn:hover { background: rgba(11,42,74,0.05); }

.pd-qty-input {
    width: 52px; text-align: center;
    border: none;
    border-left: 0.5px solid rgba(11,42,74,0.12);
    border-right: 0.5px solid rgba(11,42,74,0.12);
    outline: none; font-size: 14px; font-weight: 500;
    color: #0b2a4a; height: 40px; font-family: 'DM Sans', sans-serif;
}

.pd-actions { display: flex; gap: 12px; margin-top: 28px; flex-wrap: wrap; }

.pd-btn-primary {
    flex: 1; min-width: 140px; padding: 14px 20px;
    background: #0b2a4a; color: #f0ebe0; border: none;
    border-radius: 8px; cursor: pointer; font-size: 11px;
    letter-spacing: 0.14em; text-transform: uppercase;
    font-weight: 500; font-family: 'DM Sans', sans-serif;
    transition: background 0.2s, transform 0.15s;
}
.pd-btn-primary:hover { background: #0d3459; transform: translateY(-1px); }

.pd-btn-secondary {
    flex: 1; min-width: 140px; padding: 14px 20px;
    background: none; color: #0b2a4a;
    border: 0.5px solid #0b2a4a; border-radius: 8px;
    cursor: pointer; font-size: 11px; letter-spacing: 0.14em;
    text-transform: uppercase; font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.2s, transform 0.15s;
}
.pd-btn-secondary:hover { background: rgba(11,42,74,0.04); transform: translateY(-1px); }

.pd-others { margin-top: 80px; padding-top: 40px; border-top: 0.5px solid rgba(11,42,74,0.08); }
.pd-others-label { font-size: 10px; letter-spacing: 0.22em; text-transform: uppercase; color: #c9a96e; margin-bottom: 8px; }
.pd-others-title { font-family: 'Cormorant Garamond', serif; font-weight: 400; font-size: 28px; color: #0b2a4a; margin-bottom: 28px; }

.pd-others-grid {
    display: flex; gap: 16px; overflow-x: auto; padding-bottom: 12px;
    scrollbar-width: thin; scrollbar-color: rgba(201,169,110,0.3) transparent;
}

.pd-other-card {
    min-width: 160px; max-width: 160px; border-radius: 12px; overflow: hidden;
    border: 0.5px solid rgba(11,42,74,0.1); text-decoration: none;
    color: inherit; flex-shrink: 0; display: block;
    transition: transform 0.25s, box-shadow 0.25s;
}
.pd-other-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(11,42,74,0.1); }
.pd-other-card.current { border-color: #c9a96e; pointer-events: none; }

.pd-other-img { width: 100%; height: 130px; object-fit: cover; display: block; }
.pd-other-info { padding: 10px 12px 12px; }
.pd-other-name { font-size: 13px; font-weight: 500; color: #0b2a4a; margin-bottom: 2px; }
.pd-other-price { font-size: 12px; color: #c9a96e; }
</style>

<div class="pd-wrap">

    @if(session('success'))
        <div class="pd-flash">
            <div class="pd-flash-dot"></div>
            {{ session('success') }}
        </div>
    @endif

    <div class="pd-main">

        {{-- GAMBAR --}}
        <div class="pd-images">
            <img id="mainImage" src="{{ asset($product['images'][0]) }}" class="pd-main-img" alt="{{ $product['name'] }}">
            <div class="pd-thumbnails">
                @foreach($product['images'] as $i => $img)
                    <img
                        src="{{ asset($img) }}"
                        class="pd-thumb {{ $i === 0 ? 'active' : '' }}"
                        onclick="changeImage('{{ asset($img) }}', this)"
                        alt="thumb"
                    >
                @endforeach
            </div>
        </div>

        {{-- INFO --}}
        <div class="pd-info">
            <p class="pd-label">Taku</p>
            <h1 class="pd-name">{{ $product['name'] }}</h1>
            <p class="pd-price">{{ $product['price'] }}</p>

            <div class="pd-divider"></div>

            <p class="pd-detail-label">{{ __('app.product_details') }}</p>
            <p class="pd-detail-text">{{ $product['detail'][$locale] }}</p>

            <div class="pd-divider"></div>

            <p class="pd-qty-label">{{ __('app.quantity') }}</p>
            <div class="pd-qty-wrap">
                <button class="pd-qty-btn" type="button" onclick="decrease()">−</button>
                <input type="text" id="qty" value="1" class="pd-qty-input">
                <button class="pd-qty-btn" type="button" onclick="increase()">+</button>
            </div>

            <form id="cartForm" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $id }}">
                <input type="hidden" name="name" value="{{ $product['name'] }}">
                <input type="hidden" name="price" value="{{ $product['price'] }}">
                <input type="hidden" name="image" value="{{ $product['image'] }}">
                <input type="hidden" name="qty" id="formQty" value="1">
                <input type="hidden" name="action" id="formAction" value="add_to_cart">

                <div class="pd-actions">
                    <button type="button" class="pd-btn-primary" onclick="submitCart('buy_now')">
                        {{ __('app.buy_now') }}
                    </button>
                    <button type="button" class="pd-btn-secondary" onclick="submitCart('add_to_cart')">
                        {{ __('app.add_to_cart') }}
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- PRODUK LAINNYA --}}
    <div class="pd-others">
        <p class="pd-others-label">Taku</p>
        <h2 class="pd-others-title">{{ __('app.other_products') }}</h2>
        <div class="pd-others-grid">
            @foreach($products as $index => $item)
                <a href="{{ route('product.show', $index) }}" class="pd-other-card {{ $index == $id ? 'current' : '' }}">
                    <img src="{{ asset($item['image']) }}" class="pd-other-img" alt="{{ $item['name'] }}">
                    <div class="pd-other-info">
                        <p class="pd-other-name">{{ $item['name'] }}</p>
                        <p class="pd-other-price">{{ $item['price'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>

<a href="https://wa.me/6281324683769" target="_blank" style="
    position:fixed; bottom:24px; right:24px;
    background:#25d366; color:white;
    width:52px; height:52px; border-radius:50%;
    text-decoration:none; display:flex;
    align-items:center; justify-content:center;
    font-size:22px; box-shadow:0 4px 16px rgba(0,0,0,0.15);
    transition:transform 0.2s;
" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">💬</a>

<script>
function changeImage(src, el) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.pd-thumb').forEach(img => img.classList.remove('active'));
    el.classList.add('active');
}
function increase() {
    let qty = document.getElementById('qty');
    qty.value = parseInt(qty.value) + 1;
}
function decrease() {
    let qty = document.getElementById('qty');
    if (parseInt(qty.value) > 1) qty.value = parseInt(qty.value) - 1;
}
function submitCart(action) {
    document.getElementById('formQty').value = document.getElementById('qty').value;
    document.getElementById('formAction').value = action;
    document.getElementById('cartForm').submit();
}
</script>

@endsection
