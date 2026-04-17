<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');

.taku-header {
    position: sticky; top: 0; z-index: 100;
    background: rgba(31, 61, 43, 0.92);
    backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(168,191,163,0.2);
    padding: 0 40px; height: 64px;
    display: flex; align-items: center; justify-content: space-between;
    font-family: 'DM Sans', sans-serif;
    transition: height 0.3s ease, background 0.3s ease;
}

.taku-header.scrolled {     
    height: 54px; 
    background: rgba(31, 61, 43, 0.98); 
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.taku-logo {
    font-family: 'Cormorant Garamond', serif; font-weight: 300;
    font-size: 26px; color: #F5F1E8; letter-spacing: 0.18em;
    text-transform: uppercase; text-decoration: none;
    display: flex; align-items: center; gap: 10px; flex: 0 0 auto;
}
.taku-logo-dot { width: 5px; height: 5px; background: #C96A3D; 
    border-radius: 50%; margin-top: 2px; 
}

.taku-nav {
    display: flex; align-items: center; gap: 36px;
    position: absolute; left: 50%; transform: translateX(-50%);
}
.taku-nav a {
    color: rgba(240,235,224,0.7); text-decoration: none;
    font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase;
    font-weight: 400; position: relative; padding-bottom: 3px; transition: color 0.2s;
}
.taku-nav a::after {
    content: ''; position: absolute; bottom: 0; left: 0;
    width: 0; height: 0.5px; background: #C96A3D; transition: width 0.25s ease;
}
.taku-nav a:hover { color: #F5F1E8; }
.taku-nav a:hover::after { width: 100%; }

.taku-right { display: flex; align-items: center; gap: 6px; flex: 0 0 auto; }

.taku-icon-btn {
    background: none; border: none; cursor: pointer;
    color: rgba(240,235,224,0.6);
    display: flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 8px;
    transition: color 0.2s, background 0.2s;
    position: relative; text-decoration: none;
}
.taku-icon-btn:hover { color: #F5F1E8; background: rgba(255,255,255,0.08); }

.taku-cart-badge {
    position: absolute; top: 4px; right: 4px;
    width: 14px; height: 14px; background: #C96A3D;
    border-radius: 50%; font-size: 8px; font-weight: 500;
    color: #1F3D2B; display: flex; align-items: center; justify-content: center;
}

.taku-divider-v { width: 0.5px; height: 20px; background: rgba(255,255,255,0.12); margin: 0 4px; }

.user-toggle { position: relative; }
.user-btn {
    background: none; border: none; cursor: pointer;
    color: rgba(240,235,224,0.6);
    display: flex; align-items: center; gap: 8px;
    padding: 4px 8px; border-radius: 8px;
    transition: color 0.2s, background 0.2s;
    font-family: 'DM Sans', sans-serif;
}
.user-btn:hover { color: #F5F1E8; background: rgba(255,255,255,0.08); }
.user-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(168,191,163,0.2); border: 1px solid rgba(168,191,163,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 500; color: #A8BFA3;
    text-transform: uppercase; flex-shrink: 0;
}
.user-name {
    font-size: 12px; color: rgba(240,235,224,0.7);
    max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.user-dropdown {
    position: absolute; top: calc(100% + 12px); right: 0;
    background: #1F3D2B; border: 0.5px solid rgba(255,255,255,0.1);
    border-radius: 12px; overflow: hidden; min-width: 200px;
    opacity: 0; transform: translateY(-6px) scale(0.97);
    pointer-events: none; transition: opacity 0.2s, transform 0.2s;
    box-shadow: 0 16px 40px rgba(0,0,0,0.35);
}
.user-dropdown.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
.user-dropdown-header { padding: 14px 16px 12px; border-bottom: 0.5px solid rgba(255,255,255,0.07); }
.user-dropdown-name { font-size: 13px; font-weight: 500; color: #F5F1E8; margin-bottom: 2px; }
.user-dropdown-email { font-size: 11px; color: rgba(240,235,224,0.4); }
.user-dropdown-item {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 16px; cursor: pointer;
    color: rgba(240,235,224,0.65); font-size: 12px;
    letter-spacing: 0.06em; text-decoration: none;
    transition: background 0.15s, color 0.15s;
    background: none; border: none; width: 100%;
    font-family: 'DM Sans', sans-serif; text-align: left;
}
.user-dropdown-item:hover { background: rgba(255,255,255,0.06); color: #F5F1E8; }
.user-dropdown-item.danger:hover { color: #e74c3c; }
.user-dropdown-divider { height: 0.5px; background: rgba(255,255,255,0.07); }

.auth-links { display: flex; align-items: center; gap: 6px; }
.auth-link-login {
    background: none; border: 0.5px solid rgba(255,255,255,0.2);
    border-radius: 6px; padding: 6px 14px;
    color: rgba(240,235,224,0.7); font-size: 11px;
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'DM Sans', sans-serif; text-decoration: none;
    transition: color 0.2s, border-color 0.2s;
}
.auth-link-login:hover { color: #F5F1E8; border-color: rgba(255,255,255,0.4); }
.auth-link-register {
    background: #C96A3D; border: none; border-radius: 6px;
    padding: 6px 14px; color: #F5F1E8; font-size: 11px;
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'DM Sans', sans-serif; text-decoration: none;
    font-weight: 500; transition: background 0.2s;
}
.auth-link-register:hover { background: #b85c33; }

.lang-toggle { position: relative; }
.globe-btn {
    background: none; border: none; cursor: pointer;
    color: rgba(240,235,224,0.6);
    display: flex; align-items: center; gap: 6px;
    padding: 4px 6px; border-radius: 6px;
    transition: color 0.2s, background 0.2s; font-family: 'DM Sans', sans-serif;
}
.globe-btn:hover { color: #F5F1E8; background: rgba(255,255,255,0.07); }
.lang-badge { font-size: 10px; letter-spacing: 0.1em; font-weight: 500; color: #C96A3D; }
.lang-dropdown {
    position: absolute; top: calc(100% + 12px); right: 0;
    background: #1F3D2B; border: 0.5px solid rgba(255,255,255,0.1);
    border-radius: 10px; overflow: hidden; min-width: 160px;
    opacity: 0; transform: translateY(-6px) scale(0.97);
    pointer-events: none; transition: opacity 0.2s, transform 0.2s;
    box-shadow: 0 16px 40px rgba(0,0,0,0.35);
}
.lang-dropdown.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: all; }
.lang-option {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 16px; color: rgba(240,235,224,0.65);
    font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;
    text-decoration: none; transition: background 0.15s, color 0.15s;
    background: none; border: none; width: 100%; font-family: 'DM Sans', sans-serif;
}
.lang-option:hover { background: rgba(255,255,255,0.06); color: #F5F1E8; }
.lang-option.active { color: #C96A3D; }
.lang-divider { height: 0.5px; background: rgba(255,255,255,0.07); margin: 0 16px; }
.lang-flag { width: 16px; height: 11px; border-radius: 2px; overflow: hidden; display: flex; flex-direction: column; flex-shrink: 0; }
.flag-id-top { flex: 1; background: #CE1126; }
.flag-id-bot { flex: 1; background: #fff; }
.flag-en { width: 16px; height: 11px; background: #012169; border-radius: 2px; display: grid; place-items: center; font-size: 7px; color: #fff; font-weight: 500; flex-shrink: 0; }

@media(max-width:768px){
    .taku-header { padding: 0 16px; }
    .taku-nav { display: none; }
    .taku-right .user-name { display: none; }
    .mobile-menu-btn { display: flex; }
    .mobile-nav { display: none; position: fixed; top: 64px; left: 0; right: 0; background: #1F3D2B; z-index: 99; padding: 16px 0; border-bottom: 0.5px solid rgba(201,169,110,0.15); }
    .mobile-nav.open { display: block; }
    .mobile-nav a { display: block; padding: 12px 20px; color: rgba(240,235,224,0.7); text-decoration: none; font-family: 'DM Sans', sans-serif; font-size: 13px; letter-spacing: 0.08em; border-bottom: 0.5px solid rgba(255,255,255,0.05); }
    .mobile-nav a:hover { color: #F5F1E8; background: rgba(255,255,255,0.04); }
    .taku-divider-v { display: none; }
}
@media(min-width:769px){
    .mobile-menu-btn { display: none; }
    .mobile-nav { display: none !important; }
}
.mobile-menu-btn { background: none; border: none; cursor: pointer; color: rgba(240,235,224,0.7); align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; transition: background 0.2s; }
.mobile-menu-btn:hover { background: rgba(255,255,255,0.08); }

</style>

<header class="taku-header" id="takuHeader">

    <a href="{{ route('home') }}" class="taku-logo">
        Taku <div class="taku-logo-dot"></div>
    </a>

    <nav class="taku-nav">
        <a href="{{ route('home') }}">{{ __('app.home') }}</a>
        <a href="{{ route('products') }}">{{ __('app.shop') }}</a>
    </nav>

    <div class="taku-right">

        <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileMenu()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        @auth
        <a href="{{ route('cart.index') }}" class="taku-icon-btn" aria-label="{{ __('app.cart') }}">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/>
            </svg>
            @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
            @if($cartCount > 0)
                <span class="taku-cart-badge">{{ $cartCount }}</span>
            @endif
        </a>
        <div class="taku-divider-v"></div>
        @endauth


        @auth
        <div class="user-toggle">
            <button class="user-btn" id="takuUserBtn" onclick="takuToggleUser()">
                @if(Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}"
                        style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid rgba(201,169,110,0.4);"
                        alt="{{ Auth::user()->name }}">
                @else
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                @endif
                <span class="user-name">{{ Auth::user()->name }}</span>
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            <div class="user-dropdown" id="takuUserDropdown">
                <div class="user-dropdown-header">
                    <p class="user-dropdown-name">{{ Auth::user()->name }}</p>
                    <p class="user-dropdown-email">{{ Auth::user()->email }}</p>
                </div>

            <a href="{{ route('orders.index') }}" class="user-dropdown-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Pesanan Saya
            </a>

                        <a href="{{ route('profile') }}" class="user-dropdown-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Profil Saya
            </a>
            <a href="{{ route('wishlist.index') }}" class="user-dropdown-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                </svg>
                Wishlist
            </a>

            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Admin Dashboard
                </a>

                <div class="user-dropdown-divider"></div>

                @elseif(Auth::user()->isMerchant())
                <a href="{{ route('merchant.dashboard') }}" class="user-dropdown-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Dashboard Toko
                </a>
                <div class="user-dropdown-divider"></div>

                @else

                @if(!Auth::user()->store)
                <a href="{{ route('store.register') }}" class="user-dropdown-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Buka Toko
                </a>
                @else

                <a href="{{ route('store.pending') }}" class="user-dropdown-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Status Toko
                    <span style="margin-left:auto; font-size:10px; color:#e67e22;">Pending</span>
                </a>
                @endif
                <div class="user-dropdown-divider"></div>
                @endif

                <a href="{{ route('cart.index') }}" class="user-dropdown-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/>
                    </svg>
                    {{ __('app.cart') }}
                </a>

                <div class="user-dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="user-dropdown-item danger">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        {{ app()->getLocale() === 'en' ? 'Logout' : 'Keluar' }}
                    </button>
                </form>
            </div>
        </div>

        @else
        <div class="auth-links">
            <a href="{{ route('login') }}" class="auth-link-login">
                {{ app()->getLocale() === 'en' ? 'Login' : 'Masuk' }}
            </a>
            <a href="{{ route('register') }}" class="auth-link-register">
                {{ app()->getLocale() === 'en' ? 'Register' : 'Daftar' }}
            </a>
        </div>
        @endauth

        <div class="taku-divider-v"></div>

        <div class="lang-toggle">
            <button class="globe-btn" id="takuGlobeBtn" onclick="takuToggleLang()" aria-label="Language">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M3.6 9h16.8M3.6 15h16.8"/>
                    <path d="M12 3a14.5 14.5 0 010 18M12 3a14.5 14.5 0 000 18"/>
                </svg>
                <span class="lang-badge">{{ strtoupper(app()->getLocale()) }}</span>
            </button>

            <div class="lang-dropdown" id="takuLangDropdown">
                <a href="{{ route('lang.switch', 'id') }}" class="lang-option {{ app()->getLocale() == 'id' ? 'active' : '' }}">
                    <div class="lang-flag"><div class="flag-id-top"></div><div class="flag-id-bot"></div></div>
                    Bahasa Indonesia
                </a>
                <div class="lang-divider"></div>
                <a href="{{ route('lang.switch', 'en') }}" class="lang-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <div class="flag-en">EN</div>
                    English
                </a>
            </div>
        </div>

    </div>
</header>

@auth
@if(!Auth::user()->hasVerifiedEmail())
<div style="background:#fff8e6;border-bottom:1px solid #f0d080;padding:10px 20px;display:flex;align-items:center;justify-content:center;gap:12px;font-family:'DM Sans',sans-serif;font-size:12px;color:#7a5c00;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
    Email kamu belum diverifikasi. Verifikasi diperlukan untuk checkout.
    <form method="POST" action="{{ route('verification.send') }}" style="display:inline;">
        @csrf
        <button type="submit"
            style="background:none;border:none;cursor:pointer;color:#0b2a4a;font-weight:500;font-size:12px;font-family:'DM Sans',sans-serif;text-decoration:underline;padding:0;">
            Kirim ulang email verifikasi
        </button>
    </form>
</div>
@endif
@endauth

<div class="mobile-nav" id="mobileNav">
    <a href="{{ route('home') }}">{{ __('app.home') }}</a>
    <a href="{{ route('products') }}">{{ __('app.shop') }}</a>
    @auth
    <a href="{{ route('orders.index') }}">Pesanan Saya</a>
    <a href="{{ route('profile') }}">Profil</a>
    <a href="{{ route('wishlist.index') }}">Wishlist</a>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
    @elseif(Auth::user()->isMerchant())
        <a href="{{ route('merchant.dashboard') }}">Dashboard Toko</a>
    @endif
    <a href="{{ route('cart.index') }}">Keranjang</a>
    <form action="{{ route('logout') }}" method="POST" style="display:contents;">
        @csrf
        <button type="submit" style="display:block;width:100%;padding:12px 20px;color:rgba(240,235,224,0.5);background:none;border:none;text-align:left;font-family:'DM Sans',sans-serif;font-size:13px;cursor:pointer;border-top:0.5px solid rgba(255,255,255,0.05);">
            Keluar
        </button>
    </form>
    @else
    <a href="{{ route('login') }}">Masuk</a>
    <a href="{{ route('register') }}">Daftar</a>
    @endauth
</div>

<script>
function toggleMobileMenu() {
    const nav = document.getElementById('mobileNav');
    nav.classList.toggle('open');
}

document.addEventListener('click', function(e) {
    const nav = document.getElementById('mobileNav');
    const btn = document.getElementById('mobileMenuBtn');
    if(nav && btn && !nav.contains(e.target) && !btn.contains(e.target)) {
        nav.classList.remove('open');
    }
});
</script>




<script>
let _takuLangOpen = false;
let _takuUserOpen = false;

function takuToggleUser() {
    _takuUserOpen = !_takuUserOpen;
    document.getElementById('takuUserDropdown').classList.toggle('open', _takuUserOpen);
    if (_takuUserOpen) {
        _takuLangOpen = false;
        document.getElementById('takuLangDropdown')?.classList.remove('open');
        document.addEventListener('click', _takuCloseUser);
    }
}
function _takuCloseUser(e) {
    const dd = document.getElementById('takuUserDropdown');
    const btn = document.getElementById('takuUserBtn');
    if (!dd.contains(e.target) && !btn.contains(e.target)) {
        _takuUserOpen = false;
        dd.classList.remove('open');
        document.removeEventListener('click', _takuCloseUser);
    }
}

function takuToggleLang() {
    _takuLangOpen = !_takuLangOpen;
    document.getElementById('takuLangDropdown').classList.toggle('open', _takuLangOpen);
    if (_takuLangOpen) {
        _takuUserOpen = false;
        document.getElementById('takuUserDropdown')?.classList.remove('open');
        document.addEventListener('click', _takuCloseLang);
    }
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
