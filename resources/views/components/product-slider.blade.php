@php
$sid        = $sliderId ?? 'slider'.rand(100,999);
$cw         = $cardWidth ?? 200;
$gap        = 16;
$autoSlide  = $autoSlide ?? false;
@endphp

<div class="slider-wrap" id="wrap-{{ $sid }}">
    <div class="slider-viewport">
        <div class="slider-track" id="track-{{ $sid }}" style="gap:{{ $gap }}px;">
            @foreach($products as $product)
            <a href="{{ route('product.show', $product->slug) }}"
               class="prod-card"
               style="width:{{ $cw }}px;">
                <img src="{{ asset($product->image ?? 'images/placeholder.jpg') }}"
                     class="prod-card-img" alt="{{ $product->name }}"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="prod-card-info">
                    <p class="prod-card-store">{{ $product->store?->name ?? 'Taku Official' }}</p>
                    <p class="prod-card-name">{{ $product->name }}</p>
                    <div class="prod-card-price-wrap">
                        <span class="prod-card-price">{{ $product->getFinalPriceFormatted() }}</span>
                        @if($product->hasDiscount())
                            <span class="prod-card-original">{{ $product->getPriceFormatted() }}</span>
                            <span class="prod-card-badge">-{{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @if($products->count() > 4)
    <button class="slider-btn slider-prev" onclick="sliderMove('{{ $sid }}', -1)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="slider-btn slider-next" onclick="sliderMove('{{ $sid }}', 1)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    @endif
</div>

<script>
(function(){
    const cw = {{ $cw }}, gap = {{ $gap }};
    let idx_{{ $sid }} = 0;
    const track = document.getElementById('track-{{ $sid }}');
    const total = track ? track.children.length : 0;

    window['sliderMove'] = window['sliderMove'] || function(sid, dir) {
        eval('sliderMoveImpl_' + sid + '(' + dir + ')');
    };

    window['sliderMoveImpl_{{ $sid }}'] = function(dir) {
        const viewport = document.getElementById('wrap-{{ $sid }}').querySelector('.slider-viewport');
        const visible  = Math.floor(viewport.offsetWidth / (cw + gap));
        const maxIdx   = Math.max(0, total - visible);
        idx_{{ $sid }}  = Math.max(0, Math.min(idx_{{ $sid }} + dir, maxIdx));
        track.style.transform = `translateX(-${idx_{{ $sid }} * (cw + gap)}px)`;
    };

    const origMove = window.sliderMove;
    window.sliderMove = function(sid, dir) {
        const fn = window['sliderMoveImpl_' + sid];
        if(fn) fn(dir);
    };

    @if($autoSlide)
    setInterval(() => {
        const viewport = document.getElementById('wrap-{{ $sid }}')?.querySelector('.slider-viewport');
        if(!viewport) return;
        const visible = Math.floor(viewport.offsetWidth / (cw + gap));
        const maxIdx  = Math.max(0, total - visible);
        idx_{{ $sid }} = idx_{{ $sid }} >= maxIdx ? 0 : idx_{{ $sid }} + 1;
        track.style.transform = `translateX(-${idx_{{ $sid }} * (cw + gap)}px)`;
    }, 3500);
    @endif
})();
</script>
