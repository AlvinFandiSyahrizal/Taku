{{--
    Partial: store-banner-slider
    Props:
      $banners   - Collection<StoreBanner>
      $sliderId  - string, unique ID untuk JS (e.g. 'bannerTop', 'bannerMid', 'bannerBot')
--}}
@if($banners->count() > 0)
@php $uid = $sliderId ?? 'banner_'.uniqid(); @endphp
<div class="store-banner-wrap" id="wrap_{{ $uid }}" style="border-radius:inherit;">
    <div class="store-banner-track" id="track_{{ $uid }}">
        @foreach($banners as $b)
        <div class="store-banner-slide">
            @if($b->image)
                <img src="{{ asset($b->image) }}" alt="{{ $b->title ?? '' }}" loading="lazy">
            @endif
            @if($b->title || $b->button_text)
            <div class="store-banner-overlay">
                <div class="store-banner-content">
                    @if($b->title)<h2 class="store-banner-title">{{ $b->title }}</h2>@endif
                    @if($b->subtitle)<p class="store-banner-sub">{{ $b->subtitle }}</p>@endif
                    @if($b->link && $b->button_text)
                        <a href="{{ $b->link }}" class="store-banner-cta">{{ $b->button_text }}</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($banners->count() > 1)
    <button class="banner-nav-btn banner-prev" onclick="bannerMove('{{ $uid }}', -1)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="banner-nav-btn banner-next" onclick="bannerMove('{{ $uid }}', 1)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="banner-dots" id="dots_{{ $uid }}">
        @foreach($banners as $i => $b)
            <div class="banner-dot {{ $i === 0 ? 'active' : '' }}"
                 onclick="bannerGo('{{ $uid }}', {{ $i }})"></div>
        @endforeach
    </div>
    @endif
</div>

<script>
(function(){
    const uid   = '{{ $uid }}';
    const count = {{ $banners->count() }};
    let idx     = 0;

    window['bannerGo_' + uid] = function(i) {
        idx = i;
        const track = document.getElementById('track_' + uid);
        const dots  = document.querySelectorAll('#dots_' + uid + ' .banner-dot');
        if (track) track.style.transform = 'translateX(-' + (i * 100) + '%)';
        dots.forEach((d, di) => d.classList.toggle('active', di === i));
    };

    window.bannerGo   = window.bannerGo   || function(){};
    window.bannerMove = window.bannerMove || function(){};

    // Override with namespaced version
    const origGo   = window.bannerGo;
    const origMove = window.bannerMove;

    window.bannerGo = function(u, i) {
        if (u === uid) { window['bannerGo_' + uid](i); } else { origGo(u, i); }
    };
    window.bannerMove = function(u, dir) {
        if (u === uid) {
            window['bannerGo_' + uid]((idx + dir + count) % count);
        } else { origMove(u, dir); }
    };

    @if($banners->count() > 1)
    setInterval(() => window['bannerGo_' + uid]((idx + 1) % count), 4500);
    @endif
})();
</script>
@endif