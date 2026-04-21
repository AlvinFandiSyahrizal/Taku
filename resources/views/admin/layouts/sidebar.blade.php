<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — Taku</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', sans-serif;
        background: #f5f0e8;
        color: #3b2e22;
        display: flex;
        min-height: 100vh;
    }

    .sidebar-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(59,46,34,0.45); z-index: 49;
        backdrop-filter: blur(2px);
    }
    .sidebar-overlay.show { display: block; }

    .admin-sidebar {
        width: 248px; flex-shrink: 0;
        background: #2e2318;
        display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0;
        height: 100vh; z-index: 50;
        transition: transform 0.3s ease;
        scrollbar-width: none;
        overflow-y: auto;
    }
    .admin-sidebar::-webkit-scrollbar { display: none; }

    .sidebar-logo {
        padding: 22px 20px 18px; flex-shrink: 0;
        border-bottom: 0.5px solid rgba(212,196,168,0.12);
    }
    .sidebar-logo-text {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 300; font-size: 22px; color: #f5f0e8;
        letter-spacing: 0.18em; text-transform: uppercase;
        text-decoration: none; display: flex; align-items: center; gap: 8px;
    }
    .sidebar-logo-dot { width: 4px; height: 4px; background: #c9a96e; border-radius: 50%; }
    .sidebar-logo-badge {
        font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase;
        color: rgba(201,169,110,0.55); margin-top: 3px;
    }

    .sidebar-close {
        display: none; position: absolute; top: 14px; right: 14px;
        background: rgba(245,240,232,0.07); border: none; cursor: pointer;
        color: rgba(245,240,232,0.5); width: 28px; height: 28px;
        border-radius: 6px; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .sidebar-close:hover { background: rgba(245,240,232,0.12); color: #f5f0e8; }

    .sidebar-nav { padding: 14px 0; flex: 1; }

    .sidebar-separator {
        height: 0.5px; background: rgba(212,196,168,0.1);
        margin: 12px 0;
    }

    .sidebar-group {
        padding: 16px 20px 4px;
    }
    .sidebar-group-label {
        font-size: 8px; letter-spacing: 0.22em; text-transform: uppercase;
        color: rgba(212,196,168,0.4); font-family: 'DM Sans', sans-serif;
    }
    .sidebar-group-title {
        font-family: 'Cormorant Garamond', serif; font-size: 13px; font-weight: 300;
        color: rgba(245,240,232,0.5); letter-spacing: 0.06em; margin-top: 1px;
    }

    .sidebar-section-label {
        font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase;
        color: rgba(245,240,232,0.28); padding: 0 20px;
        margin-bottom: 3px; margin-top: 14px;
    }

    .sidebar-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 20px; color: rgba(245,240,232,0.5);
        text-decoration: none; font-size: 12.5px; letter-spacing: 0.02em;
        transition: color 0.2s, background 0.2s; position: relative;
    }
    .sidebar-link:hover { color: #f5f0e8; background: rgba(245,240,232,0.04); }
    .sidebar-link.active {
        color: #f5f0e8; background: rgba(201,169,110,0.1);
    }
    .sidebar-link.active::before {
        content: ''; position: absolute; left: 0; top: 0;
        width: 3px; height: 100%; background: #c9a96e; border-radius: 0 2px 2px 0;
    }
    .sidebar-link svg { opacity: 0.7; flex-shrink: 0; }
    .sidebar-link.active svg, .sidebar-link:hover svg { opacity: 1; }

    .sidebar-badge {
        margin-left: auto; background: #c9a96e; color: #2e2318;
        font-size: 10px; font-weight: 600; padding: 2px 7px;
        border-radius: 100px; flex-shrink: 0; min-width: 20px; text-align: center;
    }

    .sidebar-footer {
        padding: 14px 18px;
        border-top: 0.5px solid rgba(212,196,168,0.1);
        flex-shrink: 0;
    }
    .sidebar-user { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .sidebar-avatar {
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(201,169,110,0.15); border: 1px solid rgba(201,169,110,0.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 500; color: #c9a96e; text-transform: uppercase; flex-shrink: 0;
    }
    .sidebar-user-name { font-size: 12px; color: #f5f0e8; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sidebar-user-role { font-size: 9px; color: rgba(245,240,232,0.35); letter-spacing: 0.1em; text-transform: uppercase; }
    .sidebar-logout {
        display: flex; align-items: center; gap: 8px;
        color: rgba(245,240,232,0.35); font-size: 11px;
        cursor: pointer; background: none; border: none;
        font-family: 'DM Sans', sans-serif; transition: color 0.2s;
        width: 100%; text-align: left; padding: 0;
    }
    .sidebar-logout:hover { color: #e74c3c; }

    .admin-main {
        margin-left: 248px;
        flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0;
    }

    .admin-topbar {
        background: white; border-bottom: 0.5px solid rgba(59,46,34,0.08);
        padding: 0 24px; height: 56px;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 40; gap: 12px;
    }
    .topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .topbar-menu-btn {
        display: none; background: none; border: none; cursor: pointer;
        color: rgba(59,46,34,0.45); width: 36px; height: 36px;
        border-radius: 8px; align-items: center; justify-content: center;
        transition: background 0.2s; flex-shrink: 0;
    }
    .topbar-menu-btn:hover { background: rgba(59,46,34,0.05); color: #3b2e22; }
    .admin-topbar-title { font-size: 14px; font-weight: 500; color: #3b2e22; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .admin-topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .topbar-visit-btn {
        font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase;
        color: rgba(59,46,34,0.45); text-decoration: none;
        border: 0.5px solid rgba(59,46,34,0.15); border-radius: 6px;
        padding: 6px 12px; transition: all 0.2s; white-space: nowrap;
    }
    .topbar-visit-btn:hover { color: #3b2e22; border-color: rgba(59,46,34,0.3); }

    .notif-btn {
        position: relative; background: none; border: none; cursor: pointer;
        color: rgba(59,46,34,0.45); width: 36px; height: 36px;
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .notif-btn:hover { background: rgba(59,46,34,0.05); color: #3b2e22; }
    .notif-dot {
        position: absolute; top: 7px; right: 7px;
        width: 7px; height: 7px; background: #c0392b;
        border-radius: 50%; border: 1.5px solid white; display: none;
    }
    .notif-dot.show { display: block; }

    .admin-content { padding: 24px; flex: 1; min-width: 0; }

    .admin-flash {
        padding: 11px 15px; border-radius: 8px; margin-bottom: 18px;
        font-size: 13px; display: flex; align-items: center; gap: 8px;
    }
    .admin-flash.success { background: #f0f7f0; border: 0.5px solid #b2d9b2; color: #2d6a2d; }
    .admin-flash.error   { background: #fdf0f0; border: 0.5px solid #f5c0c0; color: #c0392b; }

    @media (max-width: 768px) {
        .admin-sidebar { transform: translateX(-100%); width: 100vw; max-width: 100vw; }
        .admin-sidebar.open { transform: translateX(0); }
        .sidebar-close { display: flex; }
        .admin-main { margin-left: 0; }
        .topbar-menu-btn { display: flex; }
        .topbar-visit-btn { display: none; }
        .admin-content { padding: 14px; }
        .admin-topbar { padding: 0 14px; }
    }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <button class="sidebar-close" onclick="closeSidebar()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>

    <div class="sidebar-logo">
        <a href="{{ route('home') }}" class="sidebar-logo-text">
            Taku <div class="sidebar-logo-dot"></div>
        </a>
        <p class="sidebar-logo-badge">Admin Panel</p>
    </div>

    <nav class="sidebar-nav">

        <div class="sidebar-group">
            <p class="sidebar-group-label">Section 01</p>
            <p class="sidebar-group-title">Toko Official</p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.notifications.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                Notifikasi
                @php $unreadAdmin = \App\Models\Notification::forAdmin()->unread()->count(); @endphp
                @if($unreadAdmin > 0)
                    <span class="sidebar-badge">{{ $unreadAdmin }}</span>
                @endif
        </a>

        <p class="sidebar-section-label">Katalog</p>
        <a href="{{ route('admin.products.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Produk Official
        </a>
        <a href="{{ route('store.official') }}" target="_blank"
           class="sidebar-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Lihat Toko Official ↗
        </a>

        <a href="{{ route('admin.store-content.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.store-content*') ? 'active' : '' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <path d="M3 10h18"/><line x1="8" y1="15" x2="16" y2="15"/>
                </svg>
                Konten Toko
        </a>

        <p class="sidebar-section-label">Transaksi</p>
        <a href="{{ route('admin.orders.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Pesanan Official
            @php $pendingOrders = \App\Models\Order::whereNull('store_id')->where('status','pending')->count(); @endphp
            @if($pendingOrders > 0)
                <span class="sidebar-badge" id="orderBadge">{{ $pendingOrders }}</span>
            @else
                <span class="sidebar-badge" id="orderBadge" style="display:none;">0</span>
            @endif
        </a>
        <a href="{{ route('admin.reports.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            Laporan & Keuangan
        </a>

        <div class="sidebar-separator"></div>

        <div class="sidebar-group">
            <p class="sidebar-group-label">Section 02</p>
            <p class="sidebar-group-title">Konten & Analitik</p>
        </div>

        <p class="sidebar-section-label">Konten Home</p>
        <a href="{{ route('admin.banners.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="5" width="18" height="14" rx="2"/>
                <path d="M3 10h18"/>
            </svg>
            Banner Home
        </a>
        <a href="{{ route('admin.home-sections.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.home-sections*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="4" rx="1"/>
                <rect x="3" y="10" width="18" height="4" rx="1"/>
                <rect x="3" y="17" width="18" height="4" rx="1"/>
            </svg>
            Home Sections
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Kategori
        </a>

        <p class="sidebar-section-label">Analitik</p>
        <a href="{{ route('admin.traffic.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.traffic*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Traffic Web
        </a>

        <div class="sidebar-separator"></div>

        <div class="sidebar-group">
            <p class="sidebar-group-label">Section 03</p>
            <p class="sidebar-group-title">Ekosistem Merchant</p>
        </div>

        <p class="sidebar-section-label">Analytics</p>
        <a href="{{ route('admin.merchants.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.merchants.index') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                <path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Analytics Merchant
        </a>

        <p class="sidebar-section-label">Kelola</p>
        <a href="{{ route('admin.stores.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.stores*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Kelola Toko
            @php $pendingStores = \App\Models\Store::where('status','pending')->count(); @endphp
            @if($pendingStores > 0)
                <span class="sidebar-badge" id="storeBadge">{{ $pendingStores }}</span>
            @else
                <span class="sidebar-badge" id="storeBadge" style="display:none;">0</span>
            @endif
        </a>

        <div class="sidebar-separator"></div>

        <p class="sidebar-section-label">Akun</p>
        <a href="{{ route('admin.settings') }}"
           class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Pengaturan
        </a>

    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            @if(Auth::user()->avatar)
                <img src="{{ asset(Auth::user()->avatar) }}"
                     style="width:30px;height:30px;border-radius:50%;object-fit:cover;border:1px solid rgba(201,169,110,0.25);flex-shrink:0;"
                     alt="{{ Auth::user()->name }}">
            @else
                <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
            @endif
            <div style="min-width:0;">
                <p class="sidebar-user-name">{{ Auth::user()->name }}</p>
                <p class="sidebar-user-role">Admin</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<main class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-left">
            <button class="topbar-menu-btn" onclick="openSidebar()" id="menuBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <h1 class="admin-topbar-title">@yield('page-title','Dashboard')</h1>
        </div>
        <div class="admin-topbar-right">
                    {{-- Ganti notif-btn yang lama dengan ini --}}
                    <div class="admin-topbar-right">
                        @include('admin.partials.bell')
                        <a href="{{ route('home') }}" class="topbar-visit-btn" target="_blank">↗ Lihat Situs</a>
                    </div>
        </div>
    </div>

    <div class="admin-content">
        @if(session('success'))
            <div class="admin-flash success">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="admin-flash error">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</main>

<script>
function openSidebar() {
    document.getElementById('adminSidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}




</script>

</body>
</html>