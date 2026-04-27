{{--
    Component: product-card.blade.php
    Props: $product — harus sudah load 'images','store'
    Optional: $width (default '200px')
--}}
@php
    $cardWidth = $width ?? '200px';
    $prodImg   = $product->image ?? ($product->images->first()->image ?? null);
    $hasDisc   = $product->hasDiscount();

    $hasVariants = $product->relationLoaded('variants') && $product->variants->isNotEmpty();
    $minPrice    = $hasVariants
        ? 'Mulai Rp '.number_format($product->variants->min('price'),0,',','.')
        : null;
@endphp

<a href="{{ route('product.show', $product->slug) }}"
   style="display:block;flex-shrink:0;width:{{ $cardWidth }};scroll-snap-align:start;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.08);background:white;text-decoration:none;color:inherit;transition:transform .25s,box-shadow .25s;"
   onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 28px rgba(11,42,74,.1)'"
   onmouseout="this.style.transform='';this.style.boxShadow=''">

    {{-- Gambar + badge diskon --}}
    <div style="position:relative;width:100%;aspect-ratio:1;overflow:hidden;background:#f5f1e8;">
        @if($prodImg)
            <img src="{{ asset($prodImg) }}"
                 style="width:100%;height:100%;object-fit:cover;display:block;"
                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                 alt="{{ $product->name }}">
        @else
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:40px;color:rgba(11,42,74,.1);">🌿</div>
        @endif

        {{-- Badge diskon --}}
        @if($hasDisc)
        <div style="position:absolute;top:8px;left:8px;background:#c0392b;color:white;font-size:10px;font-weight:600;padding:3px 8px;border-radius:100px;letter-spacing:.04em;font-family:'DM Sans',sans-serif;line-height:1.4;">
            -{{ $product->discount_percent }}%
        </div>
        @endif

        {{-- Badge stok habis --}}
        @if(!$hasVariants && $product->stock === 0)
        <div style="position:absolute;inset:0;background:rgba(0,0,0,.28);display:flex;align-items:center;justify-content:center;">
            <span style="background:rgba(0,0,0,.55);color:white;font-size:10px;padding:4px 10px;border-radius:100px;font-family:'DM Sans',sans-serif;letter-spacing:.06em;">Stok Habis</span>
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div style="padding:12px 14px 14px;">
        <p style="font-size:10px;color:rgba(11,42,74,.4);letter-spacing:.06em;text-transform:uppercase;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            {{ $product->store?->name ?? 'Taku Official' }}
        </p>
        <p style="font-size:13px;font-weight:500;color:#0b2a4a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            {{ $product->name }}
        </p>
        @if($minPrice)
            <p style="font-size:12px;color:#c9a96e;font-weight:500;">{{ $minPrice }}</p>
        @elseif($hasDisc)
            <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">
                <span style="font-size:13px;color:#c0392b;font-weight:500;">{{ $product->getFinalPriceFormatted() }}</span>
                <span style="font-size:11px;color:rgba(11,42,74,.3);text-decoration:line-through;">{{ $product->getPriceFormatted() }}</span>
            </div>
        @else
            <p style="font-size:13px;color:#c9a96e;font-weight:500;">{{ $product->getFinalPriceFormatted() }}</p>
        @endif
    </div>
</a>
