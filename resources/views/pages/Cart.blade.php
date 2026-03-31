@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');

.cart-wrap {
    max-width: 900px;
    margin: 56px auto 80px;
    padding: 0 32px;
    font-family: 'DM Sans', sans-serif;
}

.cart-header-label { font-size: 10px; letter-spacing: 0.22em; text-transform: uppercase; color: #c9a96e; margin-bottom: 8px; }
.cart-title { font-family: 'Cormorant Garamond', serif; font-weight: 400; font-size: 36px; color: #0b2a4a; margin-bottom: 40px; }

.cart-empty {
    text-align: center;
    padding: 80px 0;
    color: rgba(11,42,74,0.4);
}
.cart-empty-icon { font-size: 48px; margin-bottom: 16px; display: block; }
.cart-empty p { font-size: 15px; margin-bottom: 24px; }
.cart-empty-btn {
    display: inline-block;
    padding: 12px 28px;
    background: #0b2a4a;
    color: #f0ebe0;
    text-decoration: none;
    border-radius: 8px;
    font-size: 11px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    font-weight: 500;
    transition: background 0.2s;
}
.cart-empty-btn:hover { background: #0d3459; }

.cart-table { width: 100%; border-collapse: collapse; }
.cart-table th {
    font-size: 10px;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: rgba(11,42,74,0.4);
    font-weight: 400;
    padding: 0 0 16px;
    border-bottom: 0.5px solid rgba(11,42,74,0.1);
    text-align: left;
}
.cart-table th:last-child { text-align: right; }

.cart-row td {
    padding: 20px 0;
    border-bottom: 0.5px solid rgba(11,42,74,0.06);
    vertical-align: middle;
}

.cart-product {
    display: flex;
    align-items: center;
    gap: 16px;
}
.cart-product-img {
    width: 70px; height: 70px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}
.cart-product-name { font-size: 14px; font-weight: 500; color: #0b2a4a; }
.cart-product-price-mobile { font-size: 12px; color: rgba(11,42,74,0.5); margin-top: 2px; }

.cart-price { font-size: 14px; color: #0b2a4a; }

.cart-qty-form { display: flex; align-items: center; gap: 0; }
.cart-qty-wrap {
    display: inline-flex; align-items: center;
    border: 0.5px solid rgba(11,42,74,0.2); border-radius: 6px; overflow: hidden;
}
.cart-qty-btn {
    background: none; border: none; cursor: pointer;
    width: 32px; height: 32px; font-size: 16px; color: #0b2a4a;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s;
}
.cart-qty-btn:hover { background: rgba(11,42,74,0.05); }
.cart-qty-input {
    width: 40px; text-align: center;
    border: none;
    border-left: 0.5px solid rgba(11,42,74,0.12);
    border-right: 0.5px solid rgba(11,42,74,0.12);
    outline: none; font-size: 13px; font-weight: 500;
    color: #0b2a4a; height: 32px; font-family: 'DM Sans', sans-serif;
}

.cart-subtotal { font-size: 14px; font-weight: 500; color: #0b2a4a; text-align: right; }

.cart-remove-btn {
    background: none; border: none; cursor: pointer;
    color: rgba(11,42,74,0.3); font-size: 11px;
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'DM Sans', sans-serif;
    transition: color 0.2s; padding: 4px 0; margin-left: 12px;
}
.cart-remove-btn:hover { color: #c0392b; }

.cart-footer {
    margin-top: 32px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 24px;
}

.cart-clear-btn {
    background: none; border: 0.5px solid rgba(11,42,74,0.2);
    border-radius: 6px; padding: 10px 20px; cursor: pointer;
    font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase;
    color: rgba(11,42,74,0.5); font-family: 'DM Sans', sans-serif;
    transition: color 0.2s, border-color 0.2s;
}
.cart-clear-btn:hover { color: #c0392b; border-color: #c0392b; }

.cart-summary { text-align: right; }
.cart-total-label { font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(11,42,74,0.4); margin-bottom: 6px; }
.cart-total-amount { font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 400; color: #0b2a4a; margin-bottom: 16px; }

.cart-checkout-btn {
    display: inline-block;
    padding: 14px 36px;
    background: #0b2a4a;
    color: #f0ebe0;
    border: none;
    border-radius: 8px;
    font-size: 11px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    font-weight: 500;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.2s, transform 0.15s;
}
.cart-checkout-btn:hover { background: #0d3459; transform: translateY(-1px); }
</style>

<div class="cart-wrap">

    <p class="cart-header-label">Taku</p>
    <h1 class="cart-title">{{ __('app.cart_title') }}</h1>

    @if(empty($cart))
        <div class="cart-empty">
            <span class="cart-empty-icon">🛒</span>
            <p>{{ __('app.cart_empty') }}</p>
            <a href="{{ route('home') }}" class="cart-empty-btn">{{ __('app.cart_shop_now') }}</a>
        </div>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>{{ __('app.cart_product') }}</th>
                    <th>{{ __('app.cart_price') }}</th>
                    <th>{{ __('app.cart_qty') }}</th>
                    <th>{{ __('app.cart_subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                @php
                    $price = (int) preg_replace('/[^0-9]/', '', $item['price']);
                    $subtotal = $price * $item['qty'];
                @endphp
                <tr class="cart-row">
                    <td>
                        <div class="cart-product">
                            <img src="{{ asset($item['image']) }}" class="cart-product-img" alt="{{ $item['name'] }}">
                            <div>
                                <p class="cart-product-name">{{ $item['name'] }}</p>
                                <p class="cart-product-price-mobile">{{ $item['price'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="cart-price">{{ $item['price'] }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="cart-qty-form" id="updateForm{{ $id }}">
                                @csrf
                                <div class="cart-qty-wrap">
                                    <button type="button" class="cart-qty-btn" onclick="cartDecrease('{{ $id }}')">−</button>
                                    <input type="text" name="qty" id="cartQty{{ $id }}" value="{{ $item['qty'] }}" class="cart-qty-input" onchange="submitUpdate('{{ $id }}')">
                                    <button type="button" class="cart-qty-btn" onclick="cartIncrease('{{ $id }}')">+</button>
                                </div>
                            </form>
                            <form action="{{ route('cart.remove', $id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="cart-remove-btn">{{ __('app.cart_remove') }}</button>
                            </form>
                        </div>
                    </td>
                    <td class="cart-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cart-footer">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" class="cart-clear-btn">{{ __('app.cart_clear') }}</button>
            </form>

            <div class="cart-summary">
                <p class="cart-total-label">{{ __('app.cart_total') }}</p>
                <p class="cart-total-amount">Rp {{ number_format($total, 0, ',', '.') }}</p>
                <button class="cart-checkout-btn">{{ __('app.cart_checkout') }}</button>
            </div>
        </div>

    @endif

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
function cartIncrease(id) {
    let input = document.getElementById('cartQty' + id);
    input.value = parseInt(input.value) + 1;
    submitUpdate(id);
}
function cartDecrease(id) {
    let input = document.getElementById('cartQty' + id);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        submitUpdate(id);
    }
}
function submitUpdate(id) {
    document.getElementById('updateForm' + id).submit();
}
</script>

@endsection
