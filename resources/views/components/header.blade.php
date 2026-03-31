<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');

.taku-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(11, 42, 74, 0.97);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 0.5px solid rgba(255,255,255,0.08);
    padding: 0 40px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: 'DM Sans', sans-serif;
    transition: height 0.3s ease, background 0.3s ease;
}
.taku-header.scrolled { height: 54px; background: rgba(11,42,74,0.99); }

.taku-logo {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 300;
    font-size: 26px;
    color: #f0ebe0;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 0 0 auto;
}
.taku-logo-dot {
    width: 5px; height: 5px;
    background: #c9a96e;
    border-radius: 50%;
    margin-top: 2px;
}

.taku-nav {
    display: flex;
    align-items: center;
    gap: 36px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}
.taku-nav a {
    color: rgba(240,235,224,0.7);
    text-decoration: none;
    font-size: 12px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    font-weight: 400;
    position: relative;
    padding-bottom: 3px;
    transition: color 0.2s;
}
.taku-nav a::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 0; height: 0.5px;
    background: #c9a96e;
    transition: width 0.25s ease;
}
.taku-nav a:hover { color: #f0ebe0; }
.taku-nav a:hover::after { width: 100%; }

.taku-right {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 0 0 auto;
}

.taku-icon-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: rgba(240,235,224,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    border-radius: 8px;
    transition: color 0.2s, background 0.2s;
    position: relative;
    text-decoration: none;
}
.taku-icon-btn:hover { color: #f0ebe0; background: rgba(255,255,255,0.08); }

.taku-cart-badge {
    position: absolute;
    top: 4px; right: 4px;
    width: 14px; height: 14px;
    background: #c9a96e;
    border-radius: 50%;
    font-size: 8px;
    font-weight: 500;
    color: #0b2a4a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', sans-serif;
}

.taku-divider-v {
    width: 0.5px;
    height: 20px;
    background: rgba(255,255,255,0.12);
    margin: 0 4px;
}

.lang-toggle { position: relative; }
.globe-btn {
    background: none; border: none; cursor: pointer;
    color: rgba(240,235,224,0.6);
    display: flex; align-items: center; gap: 6px;
    padding: 4px 6px; border-radius: 6px;
    transition: color 0.2s, background 0.2s;
    font-family: 'DM Sans', sans-serif;
}
.globe-btn:hover { color: #f0ebe0; background: rgba(255,255,255,0.07); }
.lang-badge { font-size: 10px; letter-spacing: 0.1em; font-weight: 500; color: #c9a96e; }

.lang-dropdown {
    position: absolute;
    top: calc(100% + 12px); right: 0;
    background: #0d2e4e;
    border: 0.5px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    overflow: hidden;
    min-width: 160px;
    opacity: 0;
    transform: translateY(-6px) scale(0.97);
    pointer-events: none;
    transition: opacity 0.2s, transform 0.2s;
    box-shadow: 0 16px 40px rgba(0,0,0,0.35);
}
.lang-dropdown.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }

.lang-option {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 16px; cursor: pointer;
    color: rgba(240,235,224,0.65);
    font-size: 12px; letter-spacing: 0.08em;
    text-transform: uppercase;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    background: none; border: none; width: 100%;
    font-family: 'DM Sans', sans-serif;
}
.lang-option:hover { background: rgba(255,255,255,0.06); color: #f0ebe0; }
.lang-option.active { color: #c9a96e; }
.lang-divider { height: 0.5px; background: rgba(255,255,255,0.07); margin: 0 16px; }

.lang-flag { width: 16px; height: 11px; border-radius: 2px; overflow: hidden; display: flex; flex-direction: column; flex-shrink: 0; }
.flag-id-top { flex: 1; background: #CE1126; }
.flag-id-bot { flex: 1; background: #fff; }
.flag-en { width: 16px; height: 11px; background: #012169; border-radius: 2px; display: grid; place-items: center; font-size: 7px; color: #fff; font-weight: 500; flex-shrink: 0; }
</style>

<header class="taku-header" id="takuHeader">

    <a href="/" class="taku-logo">
        Taku
        <div class="taku-logo-dot"></div>
    </a>

    <nav class="taku-nav">
        <a href="/">{{ session('lang') == 'en' ? 'Home' : 'Beranda' }}</a>
        <a href="/products">{{ session('lang') == 'en' ? 'Shop' : 'Toko' }}</a>
    </nav>

    <div class="taku-right">

        <a href="/cart" class="taku-icon-btn" aria-label="Keranjang">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/>
            </svg>
            {{-- Badge jumlah item cart, sesuaikan dengan variabel cart kamu --}}
            {{-- @if(cart_count() > 0)
            <span class="taku-cart-badge">{{ cart_count() }}</span>
            @endif --}}
            <span class="taku-cart-badge">0</span>
        </a>

        <a href="/account" class="taku-icon-btn" aria-label="Akun">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </a>

        <div class="taku-divider-v"></div>


        <div class="lang-toggle">
            <button class="globe-btn" id="takuGlobeBtn" onclick="takuToggleLang()" aria-label="Language">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M3.6 9h16.8M3.6 15h16.8"/>
                    <path d="M12 3a14.5 14.5 0 010 18M12 3a14.5 14.5 0 000 18"/>
                </svg>
                <span class="lang-badge">{{ strtoupper(session('lang', 'id')) }}</span>
            </button>

            <div class="lang-dropdown" id="takuLangDropdown">
                <a href="/lang/id" class="lang-option {{ session('lang') != 'en' ? 'active' : '' }}">
                    <div class="lang-flag">
                        <div class="flag-id-top"></div>
                        <div class="flag-id-bot"></div>
                    </div>
                    Bahasa Indonesia
                </a>
                <div class="lang-divider"></div>
                <a href="/lang/en" class="lang-option {{ session('lang') == 'en' ? 'active' : '' }}">
                    <div class="flag-en">EN</div>
                    English
                </a>
            </div>
        </div>

    </div>
</header>

<script>
let _takuLangOpen = false;

function takuToggleLang() {
    _takuLangOpen = !_takuLangOpen;
    document.getElementById('takuLangDropdown').classList.toggle('open', _takuLangOpen);
    if (_takuLangOpen) document.addEventListener('click', _takuCloseLang);
}

function _takuCloseLang(e) {
    const dd = document.getElementById('takuLangDropdown');
    const btn = document.getElementById('takuGlobeBtn');
    if (!dd.contains(e.target) && !btn.contains(e.target)) {
        _takuLangOpen = false;
        dd.classList.remove('open');
        document.removeEventListener('click', _takuCloseLang);
    }
}

window.addEventListener('scroll', () => {
    document.getElementById('takuHeader').classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });
</script>
