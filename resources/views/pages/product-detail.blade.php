@extends('layouts.app')

@section('content')

<div class="container" style="margin-top:40px; max-width:1100px; margin-left:auto; margin-right:auto;">

    <div style="display:flex; gap:50px; flex-wrap:wrap; align-items:flex-start;">

        <div style="flex:1; min-width:300px; display:flex; flex-direction:column; align-items:center;">

            <img
                id="mainImage"
                src="{{ asset($product['images'][0]) }}"
                style="
                    width:400px;
                    height:400px;
                    object-fit:cover;
                    border-radius:12px;
                    box-shadow:0 4px 10px rgba(0,0,0,0.1);
                "
            >

            <div style="display:flex; gap:10px; margin-top:15px;">
                @foreach($product['images'] as $img)
                    <img
                        src="{{ asset($img) }}"
                        style="
                            width:70px;
                            height:70px;
                            object-fit:cover;
                            border-radius:8px;
                            cursor:pointer;
                            border:2px solid transparent;
                        "
                        onclick="changeImage('{{ asset($img) }}', this)"
                        onmouseover="this.style.opacity='0.7'"
                        onmouseout="this.style.opacity='1'"
                    >
                @endforeach
            </div>

        </div>


        
        <div style="flex:1; min-width:300px;">

            <h2 style="margin-bottom:10px;">{{ $product['name'] }}</h2>

            <h3 style="color:#0b2a4a; margin-bottom:20px;">
                {{ $product['price'] }}
            </h3>

            <hr>

            <h4 style="margin-top:20px;">Product Details</h4>
            <p style="color:#555; line-height:1.6;">
                {{ $product['detail'] }}
            </p>

            <hr style="margin:20px 0;">

            <div style="display:flex; align-items:center; gap:10px;">
                <button onclick="decrease()" style="padding:6px 12px;">-</button>

                <input
                    type="text"
                    id="qty"
                    value="1"
                    style="width:50px; text-align:center;"
                >

                <button onclick="increase()" style="padding:6px 12px;">+</button>
            </div>

            <div style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;">

                <button style="
                    padding:10px 20px;
                    background:#0b2a4a;
                    color:white;
                    border:none;
                    border-radius:6px;
                    cursor:pointer;
                ">
                    Buy Now
                </button>

                <button style="
                    padding:10px 20px;
                    border:1px solid #0b2a4a;
                    border-radius:6px;
                    cursor:pointer;
                ">
                    Add to Cart
                </button>

            </div>

        </div>

    </div>

    <div style="margin-top:60px;">
        <h3>Produk Lainnya</h3>

        <div style="display:flex; gap:20px; overflow-x:auto; padding-bottom:10px;">

            @foreach($products as $index => $item)
                <a href="/product/{{ $index }}" style="text-decoration:none; color:black;">

                    <div style="
                        min-width:150px;
                        border-radius:10px;
                        overflow:hidden;
                        box-shadow:0 2px 6px rgba(0,0,0,0.1);
                        transition:0.3s;
                    "
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'"
                    >

                        <img
                            src="{{ asset($item['image']) }}"
                            style="width:100%; height:120px; object-fit:cover;"
                        >

                        <div style="padding:8px;">
                            <p style="margin:0;">{{ $item['name'] }}</p>
                        </div>

                    </div>

                </a>
            @endforeach

        </div>
    </div>

</div>


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


<script>
function changeImage(src, el) {
    document.getElementById('mainImage').src = src;

    let thumbs = el.parentElement.querySelectorAll('img');
    thumbs.forEach(img => img.style.border = '2px solid transparent');
    el.style.border = '2px solid #0b2a4a';
}

function increase() {
    let qty = document.getElementById('qty');
    qty.value = parseInt(qty.value) + 1;
}

function decrease() {
    let qty = document.getElementById('qty');
    if (qty.value > 1) qty.value--;
}
</script>

@endsection
