<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Merchant — Taku</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body { font-family: 'DM Sans', sans-serif; background: #f5f1e8; color: #2c1810; display: flex; min-height: 100vh; }

    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(44,24,16,0.4); z-index: 49; backdrop-filter: blur(2px); }
    .sidebar-overlay.show { display: block; }

    .admin-sidebar {
        width: 240px; flex-shrink: 0;
        background: #2c1810;
        display: flex; flex-direction: column;
        position: fixed; top: 0; left: 0;
        height: 100vh; z-index: 50; overflow-y: auto;
        transition: transform 0.3s ease;
    }

    .sidebar-logo { padding: 24px 20px 20px; border-bottom: 0.5px solid rgba(201,169,110,0.15); flex-shrink: 0; }
    .sidebar-logo-text {
        font-family: 'Cormorant Garamond', serif; font-weight: 300;
        font-size: 22px; color: #f5f1e8; letter-spacing: 0.18em;
        text-transform: uppercase; text-decoration: none;
        display: flex; align-items: center; gap: 8px;
    }
    .sidebar-logo-dot { width: 4px; height: 4px; background: #c96a3d; border-radius: 50%; }
    .sidebar-logo-badge { font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(201,106,61,0.7); margin-top: 4px; }

    .sidebar-store-info { padding: 14px 20px; border-bottom: 0.5px solid rgba(201,169,110,0.1); background: rgba(201,169,110,0.06); }
    .sidebar-store-name { font-size: 12px; font-weight: 500; color: #f5f1e8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sidebar-store-status { font-size: 10px; color: rgba(201,106,61,0.8); letter-spacing: 0.06em; margin-top: 2px; }

    .sidebar-close { display: none; position: absolute; top: 16px; right: 16px; background: rgba(255,255,255,0.08); border: none; cursor: pointer; color: rgba(245,241,232,0.6); width: 28px; height: 28px; border-radius: 6px; align-items: center; justify-content: center; transition: background 0.2s; }
    .sidebar-close:hover { background: rgba(255,255,255,0.15); color: #f5f1e8; }

    .sidebar-nav { padding: 14px 0; flex: 1; overflow-y: auto; }
    .sidebar-section-label { font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(245,241,232,0.3); padding: 0 20px; margin-bottom: 4px; margin-top: 18px; }
    .sidebar-section-label:first-child { margin-top: 4px; }

    .sidebar-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 20px; color: rgba(245,241,232,0.55);
        text-decoration: none; font-size: 13px; letter-spacing: 0.03em;
        transition: color 0.2s, background 0.2s; position: relative;
    }
    .sidebar-link:hover { color: #f5f1e8; background: rgba(255,255,255,0.04); }
    .sidebar-link.active { color: #f5f1e8; background: rgba(201,106,61,0.12); }
    .sidebar-link.active::before { content: ''; position: absolute; left: 0; top: 0; width: 3px; height: 100%; background: #c96a3d; border-radius: 0 2px 2px 0; }
    .sidebar-badge { margin-left: auto; background: #c96a3d; color: #f5f1e8; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 100px; flex-shrink: 0; }

    .sidebar-footer { padding: 16px 20px; border-top: 0.5px solid rgba(201,169,110,0.12); flex-shrink: 0; }
    .sidebar-user { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .sidebar-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(201,106,61,0.2); border: 1px solid rgba(201,106,61,0.3); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 500; color: #c96a3d; text-transform: uppercase; flex-shrink: 0; }
    .sidebar-user-name { font-size: 13px; color: #f5f1e8; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sidebar-user-role { font-size: 10px; color: rgba(245,241,232,0.4); letter-spacing: 0.08em; text-transform: uppercase; }
    .sidebar-logout { display: flex; align-items: center; gap: 8px; color: rgba(245,241,232,0.4); font-size: 12px; cursor: pointer; background: none; border: none; font-family: 'DM Sans', sans-serif; transition: color 0.2s; width: 100%; text-align: left; padding: 0; }
    .sidebar-logout:hover { color: #e74c3c; }

    .admin-main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }
    .admin-topbar { background: white; border-bottom: 0.5px solid rgba(44,24,16,0.08); padding: 0 24px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 40; gap: 12px; }
    .topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .topbar-menu-btn { display: none; background: none; border: none; cursor: pointer; color: rgba(44,24,16,0.5); width: 36px; height: 36px; border-radius: 8px; align-items: center; justify-content: center; transition: background 0.2s; flex-shrink: 0; }
    .topbar-menu-btn:hover { background: rgba(44,24,16,0.05); }
    .admin-topbar-title { font-size: 14px; font-weight: 500; color: #2c1810; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .admin-topbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .topbar-visit-btn { font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(44,24,16,0.5); text-decoration: none; border: 0.5px solid rgba(44,24,16,0.15); border-radius: 6px; padding: 6px 12px; transition: all 0.2s; white-space: nowrap; }
    .topbar-visit-btn:hover { color: #2c1810; border-color: rgba(44,24,16,0.3); }

    .admin-content { padding: 24px; flex: 1; min-width: 0; }
    .admin-flash { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
    .admin-flash.success { background: #f0f7f0; border: 0.5px solid #b2d9b2; color: #2d6a2d; }
    .admin-flash.error { background: #fdf0f0; border: 0.5px solid #f5c0c0; color: #c0392b; }

    @media (max-width: 768px) {
        .admin-sidebar { transform: translateX(-100%); }
        .admin-sidebar.open { transform: translateX(0); }
        .sidebar-close { display: flex; }
        .admin-main { margin-left: 0; }
        .topbar-menu-btn { display: flex; }
        .admin-content { padding: 16px; }
    }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <button class="sidebar-close" onclick="closeSidebar()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <div class="sidebar-logo">
        <a href="{{ route('home') }}" class="sidebar-logo-text">Taku <div class="sidebar-logo-dot"></div></a>
        <p class="sidebar-logo-badge">Merchant Panel</p>
    </div>

    @php $myStore = Auth::user()->store; @endphp
    @if($myStore)
    <div class="sidebar-store-info">
        <p class="sidebar-store-name">{{ $myStore->name }}</p>
        <p class="sidebar-store-status">● Toko Aktif</p>
    </div>
    @endif

    <nav class="sidebar-nav">
        <p class="sidebar-section-label">Utama</p>
        <a href="{{ route('merchant.dashboard') }}" class="sidebar-link {{ request()->routeIs('merchant.dashboard') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>

        <p class="sidebar-section-label">Katalog</p>
        <a href="{{ route('merchant.products.index') }}" class="sidebar-link {{ request()->routeIs('merchant.products*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            Produk Saya
        </a>

        {{-- ── Kategori Toko (merchant punya sendiri) ── --}}
        <a href="{{ route('merchant.categories.index') }}" class="sidebar-link {{ request()->routeIs('merchant.categories*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M3 6h18M3 12h18M3 18h18"/>
                <circle cx="6" cy="6" r="1.5" fill="currentColor" stroke="none"/>
                <circle cx="6" cy="12" r="1.5" fill="currentColor" stroke="none"/>
                <circle cx="6" cy="18" r="1.5" fill="currentColor" stroke="none"/>
            </svg>
            Kategori Toko
        </a>

        <a href="{{ route('merchant.store.appearance') }}" class="sidebar-link {{ request()->routeIs('merchant.store.appearance') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Tampilan Toko
        </a>

        <p class="sidebar-section-label">Transaksi</p>
        <a href="{{ route('merchant.orders.index') }}" class="sidebar-link {{ request()->routeIs('merchant.orders*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Pesanan Masuk
            @php $pendingOrders = \App\Models\Order::where('store_id', Auth::user()->store?->id)->where('status','pending')->count(); @endphp
            @if($pendingOrders > 0)
                <span class="sidebar-badge">{{ $pendingOrders }}</span>
            @endif
        </a>

        <a href="{{ route('merchant.reports.index') }}" class="sidebar-link {{ request()->routeIs('merchant.reports*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            Laporan & Keuangan
        </a>

        <p class="sidebar-section-label">Akun</p>
        <a href="{{ route('merchant.settings') }}" class="sidebar-link {{ request()->routeIs('merchant.settings*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            Pengaturan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            @if(Auth::user()->avatar)
                <img src="{{ asset(Auth::user()->avatar) }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:1px solid rgba(201,106,61,0.3);flex-shrink:0;" alt="{{ Auth::user()->name }}">
            @else
                <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
            @endif
            <div style="min-width:0;">
                <p class="sidebar-user-name">{{ Auth::user()->name }}</p>
                <p class="sidebar-user-role">Merchant</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

<main class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-left">
            <button class="topbar-menu-btn" onclick="openSidebar()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <h1 class="admin-topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="admin-topbar-right">
            @include('merchant.partials.bell')
            <a href="{{ route('store.show', Auth::user()->store?->slug ?? '#') }}" class="topbar-visit-btn" target="_blank">↗ Lihat Toko</a>
        </div>
    </div>

    <div class="admin-content">
        @if(session('success'))
            <div class="admin-flash success">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
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