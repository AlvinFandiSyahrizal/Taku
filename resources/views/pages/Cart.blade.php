@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}

:root{
    --navy:#0b2a4a;--navy-mid:rgba(11,42,74,.55);--navy-soft:rgba(11,42,74,.08);
    --gold:#c9a96e;--gold-soft:rgba(201,169,110,.12);--gold-border:rgba(201,169,110,.3);
    --beige:#f5efe6;--beige-mid:#ede5d8;--olive:#4a5240;--sand:#d4c5a9;
    --danger:#c0392b;--danger-soft:rgba(192,57,43,.08);
    --success:#1a7a3c;--warning:#e67e22;--warning-soft:rgba(230,126,34,.08);
}

.cart-wrap{max-width:980px;margin:52px auto 130px;padding:0 40px;font-family:'DM Sans',sans-serif;}
@media(max-width:640px){.cart-wrap{padding:0 16px;margin-top:28px;margin-bottom:120px;}}
.cart-header-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:6px;}
.cart-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:38px;color:var(--navy);margin-bottom:32px;}
@media(max-width:640px){.cart-title{font-size:28px;}}

.toast-wrap{position:fixed;top:80px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{background:var(--navy);color:#f0ebe0;padding:12px 18px;border-radius:10px;font-size:13px;opacity:0;transform:translateY(-8px);transition:all .25s;max-width:300px;line-height:1.5;}
.toast.show{opacity:1;transform:translateY(0);}
.toast.error{background:var(--danger);}
.toast.success{background:var(--success);}
.toast.warning{background:var(--warning);color:white;}

.confirm-wrap{position:fixed;inset:0;background:rgba(0,0,0,.22);display:flex;align-items:center;justify-content:center;z-index:9999;opacity:0;pointer-events:none;transition:.25s;}
.confirm-wrap.show{opacity:1;pointer-events:auto;}
.confirm-box{background:white;padding:28px 32px;border-radius:16px;width:300px;text-align:center;box-shadow:0 24px 60px rgba(11,42,74,.15);transform:translateY(16px);transition:.25s;}
.confirm-wrap.show .confirm-box{transform:translateY(0);}
.confirm-box p{font-size:14px;color:var(--navy);margin-bottom:20px;line-height:1.6;}
.confirm-actions{display:flex;gap:10px;}
.btn-cancel-sm{flex:1;padding:10px;border-radius:8px;border:1px solid var(--beige-mid);background:white;cursor:pointer;font-size:12px;color:var(--navy-mid);font-family:'DM Sans',sans-serif;}
.btn-cancel-sm:hover{background:var(--beige);}
.btn-confirm-sm{flex:1;padding:10px;border-radius:8px;border:none;background:var(--navy);color:#f0ebe0;cursor:pointer;font-size:12px;font-family:'DM Sans',sans-serif;}
.btn-confirm-sm:hover{background:var(--olive);}

.flash-ok{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:11px 16px;font-size:13px;color:#1a7a3c;margin-bottom:16px;}
.flash-err{background:#fdf0f0;border:.5px solid #f5c0c0;border-radius:8px;padding:11px 16px;font-size:13px;color:var(--danger);margin-bottom:16px;}

.cart-empty{text-align:center;padding:88px 0;color:rgba(11,42,74,.35);}
.cart-empty-icon{font-size:52px;margin-bottom:18px;display:block;}
.cart-empty p{font-size:15px;margin-bottom:28px;}
.cart-empty-btn{display:inline-block;padding:12px 30px;background:var(--navy);color:#f0ebe0;text-decoration:none;border-radius:8px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;transition:all .2s;}
.cart-empty-btn:hover{background:var(--olive);transform:translateY(-1px);}

.cart-toolbar{display:flex;align-items:center;gap:12px;padding:10px 0;margin-bottom:14px;border-bottom:.5px solid var(--navy-soft);}
.toolbar-check{width:15px;height:15px;accent-color:var(--navy);cursor:pointer;}
.toolbar-label{font-size:12px;color:var(--navy-mid);cursor:pointer;user-select:none;}
.toolbar-label:hover{color:var(--navy);}
.toolbar-sep{width:.5px;height:14px;background:rgba(11,42,74,.12);}
.btn-mode{background:none;border:none;cursor:pointer;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-family:'DM Sans',sans-serif;color:var(--navy-mid);transition:color .2s;padding:4px 0;}
.btn-mode:hover{color:var(--navy);}
.btn-mode.active{color:var(--gold);}
.btn-del-sel{background:none;border:none;cursor:pointer;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-family:'DM Sans',sans-serif;color:var(--danger);padding:4px 0;display:none;}
.btn-del-sel.show{display:inline;}

.store-group{margin-bottom:14px;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.1);box-shadow:0 2px 12px rgba(11,42,74,.04);}
.store-group-header{display:flex;align-items:center;gap:10px;padding:12px 16px;background:linear-gradient(to right,var(--beige),#f9f7f3);border-bottom:.5px solid rgba(11,42,74,.07);}
.store-group-check{width:15px;height:15px;accent-color:var(--navy);cursor:pointer;flex-shrink:0;}
.store-group-name{font-size:13px;font-weight:500;color:var(--navy);text-decoration:none;flex:1;}
.store-group-name:hover{color:var(--gold);}
.store-group-badge{font-size:9px;letter-spacing:.1em;text-transform:uppercase;background:rgba(11,42,74,.06);color:rgba(11,42,74,.45);padding:2px 9px;border-radius:100px;}
.store-toggle{border:none;background:none;cursor:pointer;font-size:14px;color:var(--gold);transition:.3s;}

.cart-table{width:100%;border-collapse:collapse;background:white;}
.cart-table th{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.35);font-weight:400;padding:10px 16px;border-bottom:.5px solid rgba(11,42,74,.06);text-align:left;}
.cart-table th:last-child,.cart-subtotal{text-align:right;}
.cart-row td{padding:14px 16px;border-bottom:.5px solid rgba(11,42,74,.04);vertical-align:middle;}
.cart-row:last-child td{border-bottom:none;}
.cart-row{transition:background .2s;}
.cart-row:hover{background:rgba(201,169,110,.03);}
.item-check{width:15px;height:15px;accent-color:var(--navy);cursor:pointer;}

.cart-product{display:flex;align-items:center;gap:12px;}
.cart-product-img{width:58px;height:58px;object-fit:cover;border-radius:9px;flex-shrink:0;border:.5px solid rgba(11,42,74,.08);}
.cart-product-name{font-size:13px;font-weight:500;color:var(--navy);text-decoration:none;display:block;line-height:1.4;transition:color .2s;}
.cart-product-name:hover{color:var(--gold);}

.price-final{font-size:13px;font-weight:500;white-space:nowrap;color:var(--navy);}
.price-final.discounted{color:var(--danger);}
.price-original{font-size:11px;color:rgba(11,42,74,.3);text-decoration:line-through;white-space:nowrap;}
.price-disc-tag{display:inline-block;font-size:9px;background:var(--danger-soft);color:var(--danger);padding:1px 5px;border-radius:100px;margin-top:2px;}

.stale-badge{display:inline-flex;align-items:center;gap:4px;background:var(--warning-soft);border:.5px solid rgba(230,126,34,.3);border-radius:100px;padding:2px 8px;font-size:10px;color:var(--warning);font-weight:500;margin-top:4px;cursor:pointer;transition:background .15s;}
.stale-badge:hover{background:rgba(230,126,34,.15);}
.stale-badge svg{flex-shrink:0;}

/* ── Pill trigger ── */
.vp-trigger{
    display:inline-flex;align-items:center;gap:6px;
    padding:5px 10px;border:.5px solid rgba(11,42,74,.2);border-radius:100px;
    font-size:11px;font-family:'DM Sans',sans-serif;color:var(--navy);
    background:white;cursor:pointer;transition:all .18s;white-space:nowrap;
    max-width:200px;overflow:hidden;text-overflow:ellipsis;
}
.vp-trigger:hover{border-color:var(--gold);background:var(--gold-soft);}
.vp-trigger.has-variant{border-color:var(--gold-border);background:var(--gold-soft);}
.vp-trigger.needs-pick{border-color:rgba(230,126,34,.5);background:var(--warning-soft);color:var(--warning);}
.vp-trigger-label{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.vp-trigger-arrow{font-size:9px;opacity:.5;flex-shrink:0;transition:transform .18s;}
.vp-disc-pill{background:var(--danger);color:white;font-size:8px;font-weight:600;padding:1px 4px;border-radius:100px;flex-shrink:0;}

.single-size-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(74,82,64,.07);border:.5px solid rgba(74,82,64,.18);border-radius:100px;padding:4px 10px;font-size:11px;color:var(--olive);white-space:nowrap;}

/* ══════════════════════════════════════════════════════
   POPOVER FIX — pakai position:fixed + JS positioning
   Ini solusi untuk bug "popover terpotong di dalam tabel"
   karena table cell tidak bisa jadi positioned ancestor
   yang benar untuk absolute children
══════════════════════════════════════════════════════ */
.vp-popover{
    position:fixed;          /* ← FIX: fixed bukan absolute */
    z-index:9990;
    background:white;
    border:.5px solid rgba(11,42,74,.12);
    border-radius:12px;
    padding:12px;
    box-shadow:0 8px 32px rgba(11,42,74,.12);
    min-width:220px;
    max-width:320px;
    display:none;            /* hidden by default */
}
.vp-popover.vp-open{
    display:block;
    animation:vpPopIn .15s ease;
}
@keyframes vpPopIn{from{opacity:0;transform:translateY(-4px);}to{opacity:1;transform:translateY(0);}}
.vp-popover-title{font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.35);margin-bottom:10px;padding-bottom:8px;border-bottom:.5px solid rgba(11,42,74,.06);}
.vp-chips{display:flex;flex-wrap:wrap;gap:6px;}

/* Chip */
.vc-chip{display:inline-flex;flex-direction:column;align-items:flex-start;gap:1px;padding:6px 10px;border:.5px solid rgba(11,42,74,.18);border-radius:8px;background:white;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s;position:relative;min-width:80px;text-align:left;}
.vc-chip:hover:not(.vc-chip-out){border-color:var(--gold);background:var(--gold-soft);}
.vc-chip-active{border-color:var(--gold);background:var(--gold-soft);box-shadow:0 0 0 1px var(--gold);}
.vc-chip-out{opacity:.4;cursor:not-allowed;background:#f7f7f7;border-style:dashed;}
.vc-chip-label{font-size:11px;font-weight:500;color:var(--navy);line-height:1.3;}
.vc-chip-active .vc-chip-label::before{content:'✓ ';color:var(--gold);font-size:10px;}
.vc-chip-price{font-size:10px;color:var(--gold);line-height:1.2;}
.vc-chip-price.has-disc{color:var(--danger);}
.vc-chip-orig{font-size:9px;color:rgba(11,42,74,.3);text-decoration:line-through;line-height:1.2;}
.vc-chip-stock{font-size:9px;color:var(--warning);line-height:1.2;}
.vc-chip-out-lbl{font-size:9px;color:var(--danger);line-height:1.2;}
.vc-disc-badge{position:absolute;top:-6px;right:-6px;background:var(--danger);color:white;font-size:8px;font-weight:600;padding:1px 4px;border-radius:100px;}

/* vp-wrap tidak perlu position:relative lagi karena popover pakai fixed */
.vp-wrap{display:inline-block;}

.cart-qty-wrap{display:inline-flex;align-items:center;border:.5px solid rgba(11,42,74,.18);border-radius:8px;overflow:hidden;}
.cart-qty-btn{background:none;border:none;cursor:pointer;width:30px;height:30px;font-size:16px;color:var(--navy);display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;}
.cart-qty-btn:hover:not(:disabled){background:var(--navy);color:white;}
.cart-qty-btn:disabled{opacity:.3;cursor:not-allowed;}
.cart-qty-val{width:34px;text-align:center;font-size:13px;font-weight:500;color:var(--navy);border:none;border-left:.5px solid rgba(11,42,74,.1);border-right:.5px solid rgba(11,42,74,.1);height:30px;font-family:'DM Sans',sans-serif;background:white;line-height:30px;}
.cart-remove-btn{background:none;border:none;cursor:pointer;color:rgba(11,42,74,.25);font-size:11px;letter-spacing:.08em;text-transform:uppercase;font-family:'DM Sans',sans-serif;transition:color .2s;padding:3px 0;display:block;margin-top:5px;}
.cart-remove-btn:hover{color:var(--danger);}
.cart-subtotal{font-size:13px;font-weight:500;color:var(--navy);white-space:nowrap;}
.stock-out-badge{display:inline-block;font-size:10px;color:var(--danger);background:var(--danger-soft);padding:2px 8px;border-radius:100px;border:.5px solid rgba(192,57,43,.2);margin-top:3px;}

.cart-card{padding:14px 16px 12px;border-bottom:.5px solid rgba(11,42,74,.04);background:white;transition:background .2s;}
.cart-card:last-child{border-bottom:none;}
.cart-card:hover{background:rgba(201,169,110,.03);}
.cart-card-top{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.cart-card-img{width:56px;height:56px;object-fit:cover;border-radius:9px;flex-shrink:0;border:.5px solid rgba(11,42,74,.08);}
.cart-card-info{flex:1;min-width:0;}
.cart-card-bottom{display:flex;align-items:center;justify-content:space-between;padding-left:25px;}

.cart-desktop{display:table;width:100%;}
.cart-mobile{display:none;}
@media(max-width:640px){.cart-desktop{display:none;}.cart-mobile{display:block;}}

.cart-sticky-footer{position:sticky;bottom:0;background:rgba(245,239,230,.94);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-top:1px solid var(--beige-mid);border-radius:16px 16px 0 0;padding:18px 28px;margin-top:20px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;z-index:50;box-shadow:0 -8px 32px rgba(11,42,74,.07);}
.footer-summary{display:flex;flex-direction:column;gap:3px;}
.footer-meta{font-size:11px;letter-spacing:.06em;color:var(--navy-mid);}
.footer-meta span{color:var(--navy);font-weight:600;}
.footer-total{font-family:'Cormorant Garamond',serif;font-size:30px;color:var(--navy);line-height:1.1;font-weight:500;}
.footer-right{display:flex;align-items:center;gap:10px;flex-shrink:0;}
.btn-clear-cart{padding:11px 20px;border-radius:9px;font-size:11px;letter-spacing:.12em;text-transform:uppercase;font-family:'DM Sans',sans-serif;font-weight:500;border:1.5px solid rgba(74,82,64,.3);background:transparent;color:var(--olive);cursor:pointer;transition:all .25s;}
.btn-clear-cart:hover{background:var(--olive);color:#f5efe6;border-color:var(--olive);}
.btn-checkout{padding:11px 28px;border-radius:9px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-family:'DM Sans',sans-serif;font-weight:500;background:var(--navy);color:#f5efe6;border:none;cursor:pointer;transition:all .25s;}
.btn-checkout:hover:not(:disabled){background:#0d3459;box-shadow:0 8px 24px rgba(11,42,74,.28);transform:translateY(-1px);}
.btn-checkout:disabled{background:rgba(11,42,74,.18);color:rgba(11,42,74,.35);cursor:not-allowed;}
@media(max-width:640px){.cart-sticky-footer{padding:14px 16px;border-radius:14px 14px 0 0;}.footer-total{font-size:22px;}.btn-checkout,.btn-clear-cart{padding:10px 16px;font-size:10px;}}

.cart-count.pulse{animation:pulse .3s ease;}
@keyframes pulse{0%{transform:scale(1);}50%{transform:scale(1.3);}100%{transform:scale(1);}}
.vc-loading{display:inline-block;width:10px;height:10px;border:1.5px solid rgba(11,42,74,.15);border-top-color:var(--gold);border-radius:50%;animation:spin .5s linear infinite;vertical-align:middle;margin-left:3px;}
@keyframes spin{to{transform:rotate(360deg);}}
</style>

<div class="toast-wrap" id="toastWrap"></div>
<div class="confirm-wrap" id="confirmWrap">
    <div class="confirm-box">
        <p id="confirmText">Yakin?</p>
        <div class="confirm-actions">
            <button onclick="closeConfirm()" class="btn-cancel-sm">Batal</button>
            <button id="confirmOkBtn" class="btn-confirm-sm">Ya, lanjut</button>
        </div>
    </div>
</div>

<div class="cart-wrap">
    <p class="cart-header-label">Taku</p>
    <h1 class="cart-title">Keranjang Belanja</h1>

    @if(session('success'))<div class="flash-ok">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="flash-err">{{ session('error') }}</div>@endif

    @if(empty($grouped))
    <div class="cart-empty">
        <span class="cart-empty-icon">🛒</span>
        <p>Keranjang kamu masih kosong.</p>
        <a href="{{ route('products') }}" class="cart-empty-btn">Mulai Belanja</a>
    </div>
    @else

    <div class="cart-toolbar">
        <input type="checkbox" id="selectAllCb" class="toolbar-check" onchange="selectAll(this.checked)">
        <label for="selectAllCb" class="toolbar-label">Pilih Semua</label>
        <div class="toolbar-sep"></div>
        <button type="button" class="btn-mode" id="btnEdit" onclick="toggleEditMode()">Ubah</button>
        <button type="button" class="btn-del-sel" id="btnDelSelected" onclick="deleteSelected()">Hapus yang Dipilih</button>
    </div>

    <div id="cartItems">
    @foreach($grouped as $storeKey => $group)
    <div class="store-group">
        <div class="store-group-header">
            <input type="checkbox" class="store-group-check store-check" data-store="{{ $storeKey }}"
                onchange="toggleStore('{{ $storeKey }}', this.checked)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(11,42,74,.45)" stroke-width="1.5" style="flex-shrink:0;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            @if($group['store_slug'] ?? null)
                <a href="{{ route('store.show', $group['store_slug']) }}" class="store-group-name">{{ $group['store_name'] }}</a>
            @else
                <span class="store-group-name" style="cursor:default;">{{ $group['store_name'] }}</span>
            @endif
            <span class="store-group-badge">{{ count($group['items']) }} produk</span>
            <button type="button" class="store-toggle" onclick="toggleStoreCollapse(this)">▾</button>
        </div>

        {{-- ── DESKTOP ── --}}
        <table class="cart-table cart-desktop">
            <thead>
                <tr>
                    <th style="width:20px;"></th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Ukuran</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @foreach($group['items'] as $cartKey => $item)
            @php
                $finalPrice   = (int)$item['price'];
                $origPrice    = (int)($item['original_price'] ?? $finalPrice);
                $hasDiscount  = $origPrice > $finalPrice;
                $discPct      = $item['discount_percent'] ?? 0;
                $subtotal     = $finalPrice * $item['qty'];
                $stock        = (int)($item['stock'] ?? 999);
                $stockOut     = $stock === 0;
                $isSelected   = (bool)($item['is_selected'] ?? true);
                $variantId    = $item['variant_id'] ?? null;
                $allVariants  = $item['all_variants'] ?? [];
                $singleSize   = $item['single_size_label'] ?? null;
                $needsVariant = (bool)($item['needs_variant_selection'] ?? false);
                $productId    = $item['product_id'];
                $hasChips     = count($allVariants) > 0;
                $rowDisabled  = $needsVariant || $stockOut;
                $currentVariant = collect($allVariants)->firstWhere('id', $variantId);
                $pillLabel    = $needsVariant ? 'Pilih Ukuran' : ($currentVariant['label'] ?? ($singleSize ?? null));
                $pillDisc     = $currentVariant['discount_percent'] ?? 0;
            @endphp
            <tr class="cart-row" id="row-{{ $cartKey }}">
                <td>
                    <input type="checkbox"
                           class="item-check item-check-{{ $storeKey }}"
                           data-id="{{ $cartKey }}"
                           data-price="{{ $finalPrice }}"
                           data-qty="{{ $item['qty'] }}"
                           {{ $isSelected && !$rowDisabled ? 'checked' : '' }}
                           {{ $rowDisabled ? 'disabled' : '' }}
                           onchange="onItemCheck(this, '{{ $cartKey }}')">
                </td>
                <td>
                    <div class="cart-product">
                        <a href="{{ route('product.show', $productId) }}">
                            <img src="{{ asset($item['image'] ?? 'images/placeholder.jpg') }}"
                                 class="cart-product-img" alt="{{ $item['name'] }}"
                                 style="{{ $stockOut && !$needsVariant ? 'opacity:.4;' : '' }}"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        </a>
                        <div style="min-width:0;">
                            <a href="{{ route('product.show', $productId) }}" class="cart-product-name">{{ $item['name'] }}</a>
                            @if($stockOut && !$needsVariant)
                                <span class="stock-out-badge">Stok Habis</span>
                            @endif
                            @if($needsVariant)
                                <div>
                                    <span class="stale-badge" onclick="vpToggle('vp-{{ $cartKey }}', document.getElementById('vp-trigger-{{ $cartKey }}'))">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        Pilih ukuran dulu
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if($hasDiscount && !$needsVariant)
                        <p class="price-final discounted" id="price-{{ $cartKey }}">Rp {{ number_format($finalPrice,0,',','.') }}</p>
                        <p class="price-original" id="orig-{{ $cartKey }}">Rp {{ number_format($origPrice,0,',','.') }}</p>
                        @if($discPct > 0)<span class="price-disc-tag" id="disc-{{ $cartKey }}">-{{ $discPct }}%</span>@endif
                    @else
                        <p class="price-final" id="price-{{ $cartKey }}">{{ $needsVariant ? '—' : 'Rp '.number_format($finalPrice,0,',','.') }}</p>
                        <p class="price-original" id="orig-{{ $cartKey }}" style="display:none;"></p>
                    @endif
                </td>
                <td>
                    @if($hasChips)
                    {{-- Trigger button saja di sini, popover di-render di luar tabel --}}
                    <div class="vp-wrap" id="vp-wrap-{{ $cartKey }}">
                        <button type="button"
                                class="vp-trigger {{ $variantId && !$needsVariant ? 'has-variant' : '' }} {{ $needsVariant ? 'needs-pick' : '' }}"
                                id="vp-trigger-{{ $cartKey }}"
                                data-pop="vp-{{ $cartKey }}"
                                onclick="vpToggle('vp-{{ $cartKey }}', this)">
                            @if($pillDisc > 0)<span class="vp-disc-pill">-{{ $pillDisc }}%</span>@endif
                            <span class="vp-trigger-label">{{ $pillLabel ?? 'Pilih Ukuran' }}</span>
                            <span class="vp-trigger-arrow">▾</span>
                        </button>
                    </div>
                    @elseif($singleSize)
                    <span class="single-size-badge">{{ $singleSize }}</span>
                    @else
                    <span style="font-size:12px;color:rgba(11,42,74,.25);">—</span>
                    @endif
                </td>
                <td>
                    @if(!$needsVariant)
                    <div class="cart-qty-wrap">
                        <button type="button" class="cart-qty-btn"
                                onclick="ajaxQty('{{ $cartKey }}', -1, {{ $stock }})"
                                {{ $item['qty']<=1||$rowDisabled?'disabled':'' }}>−</button>
                        <span class="cart-qty-val" id="qty-{{ $cartKey }}">{{ $item['qty'] }}</span>
                        <button type="button" class="cart-qty-btn"
                                onclick="ajaxQty('{{ $cartKey }}', 1, {{ $stock }})"
                                {{ ($stock>0&&$item['qty']>=$stock)||$rowDisabled?'disabled':'' }}>+</button>
                    </div>
                    @endif
                    <form action="{{ route('cart.remove', $cartKey) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" onclick="removeItem('{{ $cartKey }}')" class="cart-remove-btn">Hapus</button>
                    </form>
                </td>
                <td class="cart-subtotal" id="sub-{{ $cartKey }}">
                    {{ $rowDisabled ? '—' : 'Rp '.number_format($subtotal,0,',','.') }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{-- ── MOBILE ── --}}
        <div class="cart-mobile">
            @foreach($group['items'] as $cartKey => $item)
            @php
                $finalPrice   = (int)$item['price'];
                $origPrice    = (int)($item['original_price'] ?? $finalPrice);
                $hasDiscount  = $origPrice > $finalPrice;
                $subtotal     = $finalPrice * $item['qty'];
                $stock        = (int)($item['stock'] ?? 999);
                $stockOut     = $stock === 0;
                $isSelected   = (bool)($item['is_selected'] ?? true);
                $variantId    = $item['variant_id'] ?? null;
                $allVariants  = $item['all_variants'] ?? [];
                $singleSize   = $item['single_size_label'] ?? null;
                $needsVariant = (bool)($item['needs_variant_selection'] ?? false);
                $productId    = $item['product_id'];
                $rowDisabled  = $needsVariant || $stockOut;
                $hasChips     = count($allVariants) > 0;
                $currentVariant = collect($allVariants)->firstWhere('id', $variantId);
                $pillLabel    = $needsVariant ? 'Pilih Ukuran' : ($currentVariant['label'] ?? ($singleSize ?? null));
                $pillDisc     = $currentVariant['discount_percent'] ?? 0;
            @endphp
            <div class="cart-card" id="row-m-{{ $cartKey }}">
                <div class="cart-card-top">
                    <input type="checkbox"
                           class="item-check item-check-{{ $storeKey }}"
                           data-id="{{ $cartKey }}"
                           data-price="{{ $finalPrice }}"
                           data-qty="{{ $item['qty'] }}"
                           {{ $isSelected&&!$rowDisabled?'checked':'' }}
                           {{ $rowDisabled?'disabled':'' }}
                           onchange="onItemCheck(this, '{{ $cartKey }}')">
                    <a href="{{ route('product.show', $productId) }}">
                        <img src="{{ asset($item['image'] ?? 'images/placeholder.jpg') }}"
                             class="cart-card-img" alt="{{ $item['name'] }}"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                    </a>
                    <div class="cart-card-info">
                        <a href="{{ route('product.show', $productId) }}" class="cart-product-name">{{ $item['name'] }}</a>
                        @if(!$needsVariant&&!$stockOut)
                            <p class="price-final {{ $hasDiscount?'discounted':'' }}" style="margin-top:2px;" id="price-m-{{ $cartKey }}">Rp {{ number_format($finalPrice,0,',','.') }}</p>
                            @if($hasDiscount)<p class="price-original" id="orig-m-{{ $cartKey }}">Rp {{ number_format($origPrice,0,',','.') }}</p>@endif
                        @endif
                        @if($stockOut&&!$needsVariant)<span class="stock-out-badge">Stok Habis</span>@endif
                        @if($needsVariant)
                            <span class="stale-badge" style="margin-top:4px;"
                                  onclick="vpToggle('vp-m-{{ $cartKey }}', document.getElementById('vp-trigger-m-{{ $cartKey }}'))">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Pilih ukuran dulu
                            </span>
                        @endif
                        @if($hasChips)
                        <div class="vp-wrap" style="margin-top:6px;" id="vp-wrap-m-{{ $cartKey }}">
                            <button type="button"
                                    class="vp-trigger {{ $variantId&&!$needsVariant?'has-variant':'' }} {{ $needsVariant?'needs-pick':'' }}"
                                    id="vp-trigger-m-{{ $cartKey }}"
                                    data-pop="vp-m-{{ $cartKey }}"
                                    onclick="vpToggle('vp-m-{{ $cartKey }}', this)">
                                @if($pillDisc>0)<span class="vp-disc-pill">-{{ $pillDisc }}%</span>@endif
                                <span class="vp-trigger-label">{{ $pillLabel ?? 'Pilih Ukuran' }}</span>
                                <span class="vp-trigger-arrow">▾</span>
                            </button>
                        </div>
                        @elseif($singleSize)
                        <span class="single-size-badge" style="margin-top:5px;">{{ $singleSize }}</span>
                        @endif
                    </div>
                </div>
                <div class="cart-card-bottom">
                    <form action="{{ route('cart.remove', $cartKey) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" onclick="removeItem('{{ $cartKey }}')" class="cart-remove-btn" style="margin-top:0;">Hapus</button>
                    </form>
                    @if(!$needsVariant)
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span class="cart-subtotal" id="sub-m-{{ $cartKey }}">{{ $stockOut?'—':'Rp '.number_format($subtotal,0,',','.') }}</span>
                        <div class="cart-qty-wrap">
                            <button type="button" class="cart-qty-btn" onclick="ajaxQty('{{ $cartKey }}', -1, {{ $stock }})" {{ $item['qty']<=1||$rowDisabled?'disabled':'' }}>−</button>
                            <span class="cart-qty-val" id="qty-m-{{ $cartKey }}">{{ $item['qty'] }}</span>
                            <button type="button" class="cart-qty-btn" onclick="ajaxQty('{{ $cartKey }}', 1, {{ $stock }})" {{ ($stock>0&&$item['qty']>=$stock)||$rowDisabled?'disabled':'' }}>+</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    @if(!empty($unavailable) && count($unavailable) > 0)
    <div style="margin-top:28px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;padding-bottom:10px;border-bottom:.5px solid rgba(11,42,74,.06);">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(11,42,74,.3)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
            <p style="font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:rgba(11,42,74,.35);">Produk Tidak Tersedia ({{ count($unavailable) }})</p>
        </div>
        <div style="background:white;border-radius:12px;border:.5px solid rgba(11,42,74,.08);overflow:hidden;">
            @foreach($unavailable as $itemId => $item)
            <div style="display:flex;align-items:center;gap:12px;padding:13px 16px;border-bottom:.5px solid rgba(11,42,74,.04);">
                <img src="{{ asset($item['image'] ?? 'images/placeholder.jpg') }}"
                     style="width:52px;height:52px;object-fit:cover;border-radius:8px;opacity:.4;border:.5px solid rgba(11,42,74,.08);flex-shrink:0;"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div style="flex:1;min-width:0;">
                    <span style="font-size:13px;font-weight:500;color:rgba(11,42,74,.4);display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['name'] }}</span>
                    <span style="display:inline-block;font-size:10px;padding:2px 9px;border-radius:100px;background:rgba(0,0,0,.05);color:rgba(11,42,74,.4);border:.5px solid rgba(11,42,74,.1);margin-top:4px;">Tidak Tersedia</span>
                </div>
                <form action="{{ route('cart.remove', $itemId) }}" method="POST">
                    @csrf
                    <button type="submit" style="font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#c0392b;background:none;border:.5px solid rgba(192,57,43,.2);border-radius:6px;padding:4px 10px;cursor:pointer;font-family:'DM Sans',sans-serif;">Hapus</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    </div>

    {{-- ══════════════════════════════════════════════════
         SEMUA POPOVER DI-RENDER DI SINI — di luar tabel
         Ini kunci fix: popover tidak lagi di dalam <td>
         sehingga position:fixed bisa bekerja dengan benar
    ══════════════════════════════════════════════════ --}}
    @foreach($grouped as $storeKey => $group)
    @foreach($group['items'] as $cartKey => $item)
    @php $allVariants = $item['all_variants'] ?? []; $variantId = $item['variant_id'] ?? null; $needsVariant = (bool)($item['needs_variant_selection'] ?? false); $productId = $item['product_id']; @endphp
    @if(count($allVariants) > 0)
    {{-- Desktop popover --}}
    <div class="vp-popover" id="vp-{{ $cartKey }}">
        <p class="vp-popover-title">Pilih Ukuran</p>
        <div class="vp-chips">
            @foreach($allVariants as $av)
            @php $avOut=$av['stock']==0;$avActive=$av['id']==$variantId&&!$needsVariant;$avHasDisc=$av['discount_percent']>0; @endphp
            <button type="button"
                    class="vc-chip {{ $avActive?'vc-chip-active':'' }} {{ $avOut?'vc-chip-out':'' }}"
                    data-variant-id="{{ $av['id'] }}"
                    data-price="{{ $av['final_price'] }}"
                    data-original="{{ $av['price'] }}"
                    data-discount="{{ $av['discount_percent'] }}"
                    data-stock="{{ $av['stock'] }}"
                    data-label="{{ $av['label'] }}"
                    {{ $avOut?'disabled':'' }}
                    onclick="selectChip('{{ $cartKey }}', {{ $productId }}, this)">
                @if($avHasDisc)<span class="vc-disc-badge">-{{ $av['discount_percent'] }}%</span>@endif
                <span class="vc-chip-label">{{ $av['label'] }}</span>
                <span class="vc-chip-price {{ $avHasDisc?'has-disc':'' }}">Rp {{ number_format($av['final_price'],0,',','.') }}</span>
                @if($avHasDisc)<span class="vc-chip-orig">Rp {{ number_format($av['price'],0,',','.') }}</span>@endif
                @if($avOut)<span class="vc-chip-out-lbl">Habis</span>
                @elseif($av['stock']<=5&&$av['stock']>0)<span class="vc-chip-stock">Sisa {{ $av['stock'] }}</span>@endif
            </button>
            @endforeach
        </div>
    </div>
    {{-- Mobile popover --}}
    <div class="vp-popover" id="vp-m-{{ $cartKey }}">
        <p class="vp-popover-title">Pilih Ukuran</p>
        <div class="vp-chips">
            @foreach($allVariants as $av)
            @php $avOut=$av['stock']==0;$avActive=$av['id']==$variantId&&!$needsVariant;$avHasDisc=$av['discount_percent']>0; @endphp
            <button type="button"
                    class="vc-chip {{ $avActive?'vc-chip-active':'' }} {{ $avOut?'vc-chip-out':'' }}"
                    data-variant-id="{{ $av['id'] }}"
                    data-price="{{ $av['final_price'] }}"
                    data-original="{{ $av['price'] }}"
                    data-discount="{{ $av['discount_percent'] }}"
                    data-stock="{{ $av['stock'] }}"
                    data-label="{{ $av['label'] }}"
                    {{ $avOut?'disabled':'' }}
                    onclick="selectChip('{{ $cartKey }}', {{ $productId }}, this)">
                @if($avHasDisc)<span class="vc-disc-badge">-{{ $av['discount_percent'] }}%</span>@endif
                <span class="vc-chip-label">{{ $av['label'] }}</span>
                <span class="vc-chip-price {{ $avHasDisc?'has-disc':'' }}">Rp {{ number_format($av['final_price'],0,',','.') }}</span>
                @if($avHasDisc)<span class="vc-chip-orig">Rp {{ number_format($av['price'],0,',','.') }}</span>@endif
                @if($avOut)<span class="vc-chip-out-lbl">Habis</span>
                @elseif($av['stock']<=5&&$av['stock']>0)<span class="vc-chip-stock">Sisa {{ $av['stock'] }}</span>@endif
            </button>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach
    @endforeach

    <form action="{{ route('checkout.select') }}" method="POST" id="checkoutForm">
        @csrf
        <div id="selectedInputs"></div>
        <div class="cart-sticky-footer">
            <div class="footer-summary">
                <p class="footer-meta"><span id="selectedCount">0</span> produk dipilih</p>
                <p class="footer-total" id="selectedTotal">Rp 0</p>
            </div>
            <div class="footer-right">
                <button type="button" class="btn-clear-cart" onclick="clearCart()">Kosongkan</button>
                <button type="submit" class="btn-checkout" id="checkoutBtn" disabled>Checkout</button>
            </div>
        </div>
    </form>

    @endif
</div>

<a href="https://wa.me/6281324683769" target="_blank"
   style="position:fixed;bottom:90px;right:24px;background:#25d366;color:white;width:48px;height:48px;border-radius:50%;text-decoration:none;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,.15);transition:transform .2s;z-index:50;"
   onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.11 1.523 5.836L.057 23.929l6.263-1.643A11.965 11.965 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.034-1.388l-.36-.214-3.724.977.994-3.63-.235-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
</a>

<script>
const CSRF = '{{ csrf_token() }}';
let editMode = false;

/* ── Toast ── */
function showToast(msg,type='info'){const wrap=document.getElementById('toastWrap');const t=document.createElement('div');t.className='toast '+type;t.textContent=msg;wrap.appendChild(t);requestAnimationFrame(()=>t.classList.add('show'));setTimeout(()=>{t.classList.remove('show');setTimeout(()=>t.remove(),300);},2600);}

/* ── Animated number ── */
function animateNumber(el,start,end){let s=null;const step=ts=>{if(!s)s=ts;const p=Math.min((ts-s)/280,1);el.textContent='Rp '+Math.floor(start+(end-start)*p).toLocaleString('id-ID');if(p<1)requestAnimationFrame(step);};requestAnimationFrame(step);}

/* ══════════════════════════════════════════════════════
   POPOVER — pakai position:fixed + JS positioning
   Saat trigger diklik, kita hitung posisi trigger
   dan set top/left popover secara manual
══════════════════════════════════════════════════════ */
function vpCloseAll() {
    document.querySelectorAll('.vp-popover.vp-open').forEach(p => {
        p.classList.remove('vp-open');
        p.style.top = '';
        p.style.left = '';
    });
    document.querySelectorAll('.vp-trigger').forEach(t => {
        t.classList.remove('vp-open');
        const arrow = t.querySelector('.vp-trigger-arrow');
        if (arrow) arrow.style.transform = '';
    });
}

function vpPositionPopover(pop, triggerEl) {
    // Hitung posisi trigger relatif ke viewport
    const rect = triggerEl.getBoundingClientRect();
    const popW = 280; // estimasi lebar popover
    const margin = 8;

    let top  = rect.bottom + margin;
    let left = rect.left;

    // Jangan sampai keluar kanan layar
    if (left + popW > window.innerWidth - margin) {
        left = window.innerWidth - popW - margin;
    }
    // Jangan sampai keluar kiri layar
    if (left < margin) left = margin;

    // Kalau bawah kurang ruang, muncul di atas trigger
    const popH = 200; // estimasi tinggi
    if (top + popH > window.innerHeight - margin) {
        top = rect.top - popH - margin;
    }

    pop.style.top  = top + 'px';
    pop.style.left = left + 'px';
}

function vpToggle(popId, triggerEl) {
    const pop = document.getElementById(popId);
    if (!pop) return;
    const isOpen = pop.classList.contains('vp-open');
    vpCloseAll();
    if (!isOpen) {
        pop.classList.add('vp-open');
        vpPositionPopover(pop, triggerEl);
        if (triggerEl) {
            triggerEl.classList.add('vp-open');
            const arrow = triggerEl.querySelector('.vp-trigger-arrow');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    }
}

// Reposisi semua popover yang terbuka saat scroll/resize
function vpRepositionOpen() {
    document.querySelectorAll('.vp-popover.vp-open').forEach(pop => {
        const popId   = pop.id;
        const trigger = document.querySelector(`[data-pop="${popId}"]`);
        if (trigger) vpPositionPopover(pop, trigger);
    });
}
window.addEventListener('scroll', vpRepositionOpen, { passive: true });
window.addEventListener('resize', vpRepositionOpen, { passive: true });

// Tutup saat klik di luar
document.addEventListener('click', e => {
    if (!e.target.closest('.vp-wrap') &&
        !e.target.closest('.vp-popover') &&
        !e.target.closest('.stale-badge')) {
        vpCloseAll();
    }
});

/* ── Select chip ── */
async function selectChip(oldCartId, productId, chipEl) {
    if (chipEl.disabled) return;
    const newVariantId = chipEl.dataset.variantId;
    const parts        = oldCartId.split('_');
    const currentVId   = parts[1] ?? null;

    vpCloseAll();
    if (String(currentVId) === String(newVariantId)) return;

    const triggerEl = document.getElementById('vp-trigger-'+oldCartId) || document.getElementById('vp-trigger-m-'+oldCartId);
    const origLabel = triggerEl?.querySelector('.vp-trigger-label')?.textContent ?? '';
    if (triggerEl) {
        const lbl = triggerEl.querySelector('.vp-trigger-label');
        if (lbl) lbl.innerHTML = 'Mengubah… <span class="vc-loading"></span>';
        triggerEl.disabled = true;
    }

    try {
        const res = await fetch('{{ route("cart.changeVariant") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
            body: JSON.stringify({ old_key: oldCartId, new_variant_id: newVariantId }),
        });
        const data = await res.json();

        if (!data.ok) {
            showToast(data.message || 'Gagal mengganti ukuran.', 'error');
            if (triggerEl) { triggerEl.querySelector('.vp-trigger-label').textContent = origLabel; triggerEl.disabled = false; }
            return;
        }

        const newCartId   = data.new_key;
        const newPrice    = data.price;
        const newOriginal = data.original_price;
        const newDiscount = data.discount;
        const newStock    = data.stock;
        const qty         = data.qty;
        const newLabel    = data.label;

        // Rename IDs
        const renames = {
            [`row-${oldCartId}`]:`row-${newCartId}`,
            [`row-m-${oldCartId}`]:`row-m-${newCartId}`,
            [`qty-${oldCartId}`]:`qty-${newCartId}`,
            [`qty-m-${oldCartId}`]:`qty-m-${newCartId}`,
            [`sub-${oldCartId}`]:`sub-${newCartId}`,
            [`sub-m-${oldCartId}`]:`sub-m-${newCartId}`,
            [`price-${oldCartId}`]:`price-${newCartId}`,
            [`price-m-${oldCartId}`]:`price-m-${newCartId}`,
            [`orig-${oldCartId}`]:`orig-${newCartId}`,
            [`orig-m-${oldCartId}`]:`orig-m-${newCartId}`,
            [`disc-${oldCartId}`]:`disc-${newCartId}`,
            [`vp-${oldCartId}`]:`vp-${newCartId}`,
            [`vp-m-${oldCartId}`]:`vp-m-${newCartId}`,
            [`vp-wrap-${oldCartId}`]:`vp-wrap-${newCartId}`,
            [`vp-wrap-m-${oldCartId}`]:`vp-wrap-m-${newCartId}`,
            [`vp-trigger-${oldCartId}`]:`vp-trigger-${newCartId}`,
            [`vp-trigger-m-${oldCartId}`]:`vp-trigger-m-${newCartId}`,
        };
        Object.entries(renames).forEach(([o,n])=>{const el=document.getElementById(o);if(el)el.id=n;});

        // Update data-pop attribute pada trigger
        ['vp-trigger-'+newCartId,'vp-trigger-m-'+newCartId].forEach(tId => {
            const t = document.getElementById(tId);
            if (t) {
                const suffix = tId.startsWith('vp-trigger-m-') ? 'vp-m-' : 'vp-';
                t.setAttribute('data-pop', suffix + newCartId);
                t.setAttribute('onclick', `vpToggle('${suffix}${newCartId}', this)`);
            }
        });

        // Update checkbox
        document.querySelectorAll(`[data-id="${oldCartId}"]`).forEach(el=>{el.dataset.id=newCartId;el.dataset.price=newPrice;el.dataset.qty=qty;el.disabled=false;});

        // Update qty display kalau ada penyesuaian (clamp stok)
        if (data.was_adjusted) {
            ["qty-"+newCartId,"qty-m-"+newCartId].forEach(qid=>{const el=document.getElementById(qid);if(el)el.textContent=qty;});
            const rowEl2=document.getElementById("row-"+newCartId);
            if(rowEl2){const btns=rowEl2.querySelectorAll(".cart-qty-btn");if(btns[0])btns[0].disabled=qty<=1;if(btns[1])btns[1].disabled=data.stock>0&&qty>=data.stock;}
        }

        // Update harga
        ['price-'+newCartId,'price-m-'+newCartId].forEach(id=>{const el=document.getElementById(id);if(!el)return;el.textContent='Rp '+newPrice.toLocaleString('id-ID');el.className='price-final'+(newDiscount>0?' discounted':'');});
        ['orig-'+newCartId,'orig-m-'+newCartId].forEach(id=>{const el=document.getElementById(id);if(!el)return;el.textContent=newOriginal?'Rp '+newOriginal.toLocaleString('id-ID'):'';el.style.display=newOriginal?'block':'none';});
        const discEl=document.getElementById('disc-'+newCartId);
        if(discEl){discEl.textContent=newDiscount>0?`-${newDiscount}%`:'';discEl.style.display=newDiscount>0?'inline-block':'none';}

        // Update subtotal
        const newSub='Rp '+(newPrice*qty).toLocaleString('id-ID');
        ['sub-'+newCartId,'sub-m-'+newCartId].forEach(id=>{const el=document.getElementById(id);if(el)el.textContent=newSub;});

        // Update pill label
        ['vp-trigger-'+newCartId,'vp-trigger-m-'+newCartId].forEach(id=>{
            const t=document.getElementById(id);if(!t)return;
            const lbl=t.querySelector('.vp-trigger-label');if(lbl)lbl.textContent=newLabel;
            t.classList.remove('needs-pick');t.classList.add('has-variant');t.disabled=false;
            let db=t.querySelector('.vp-disc-pill');
            if(newDiscount>0){if(!db){db=document.createElement('span');db.className='vp-disc-pill';t.insertBefore(db,t.firstChild);}db.textContent=`-${newDiscount}%`;}else{db?.remove();}
        });

        // Update active chip di kedua popover
        ['vp-'+newCartId,'vp-m-'+newCartId].forEach(popId=>{
            const pop=document.getElementById(popId);if(!pop)return;
            pop.querySelectorAll('.vc-chip').forEach(c=>{
                c.classList.toggle('vc-chip-active',c.dataset.variantId==newVariantId);
                c.setAttribute('onclick',`selectChip('${newCartId}', ${productId}, this)`);
            });
        });

        // Update qty buttons
        const rowEl=document.getElementById('row-'+newCartId);
        const qtyEl=document.getElementById('qty-'+newCartId);
        const curQty=qtyEl?parseInt(qtyEl.textContent)||1:1;
        if(rowEl){const btns=rowEl.querySelectorAll('.cart-qty-btn');if(btns[0])btns[0].disabled=curQty<=1;if(btns[1])btns[1].disabled=newStock>0&&curQty>=newStock;const cb=rowEl.querySelector('.item-check');if(cb)cb.disabled=false;}

        // Hapus stale badge
        [document.getElementById('row-'+newCartId),document.getElementById('row-m-'+newCartId)].forEach(r=>{if(r)r.querySelectorAll('.stale-badge').forEach(b=>{const p=b.closest('div');if(p&&p.children.length===1)p.remove();else b.remove();});});

        // Auto-centang
        document.querySelectorAll(`[data-id="${newCartId}"]`).forEach(el=>{if(el.type==='checkbox'){el.checked=true;saveSelect(newCartId,true);}});

        updateSummary();syncStoreChecks();

        // Tampilkan toast sesuai kondisi
        if (data.was_adjusted) {
            // Qty disesuaikan karena stok variant baru lebih kecil
            showToast(data.adjust_msg, 'warning');
        } else {
            showToast('Ukuran diubah ke ' + newLabel, 'success');
        }

    } catch(err) {
        console.error(err);showToast('Terjadi kesalahan.','error');
        if(triggerEl){triggerEl.querySelector('.vp-trigger-label').textContent=origLabel;triggerEl.disabled=false;}
    }
}

/* ── Summary ── */
function updateSummary(){const seen=new Set();let count=0,total=0;document.querySelectorAll('.item-check:checked').forEach(cb=>{const id=cb.dataset.id;if(seen.has(id))return;seen.add(id);count++;total+=(parseInt(cb.dataset.price)||0)*(parseInt(cb.dataset.qty)||1);});const cEl=document.getElementById('selectedCount');const tEl=document.getElementById('selectedTotal');if(cEl)cEl.textContent=count;if(tEl){const cur=parseInt(tEl.textContent.replace(/\D/g,''))||0;animateNumber(tEl,cur,total);}const btn=document.getElementById('checkoutBtn');if(btn)btn.disabled=count===0;const cont=document.getElementById('selectedInputs');if(cont){cont.innerHTML='';seen.forEach(id=>{const inp=document.createElement('input');inp.type='hidden';inp.name='selected[]';inp.value=id;cont.appendChild(inp);});}}
async function saveSelect(id,selected){try{await fetch(`/cart/select/${id}`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({selected})});}catch{}}
function onItemCheck(cb,id){const checked=cb.checked;document.querySelectorAll(`[data-id="${id}"]`).forEach(el=>el.checked=checked);saveSelect(id,checked);updateSummary();syncStoreChecks();}
function selectAll(checked){document.querySelectorAll('.item-check:not(:disabled)').forEach(cb=>{cb.checked=checked;document.querySelectorAll(`[data-id="${cb.dataset.id}"]`).forEach(el=>el.checked=checked);});fetch('/cart/select-all',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({selected:checked})});updateSummary();syncStoreChecks();}
function toggleStore(storeKey,checked){document.querySelectorAll(`.item-check-${storeKey}:not(:disabled)`).forEach(cb=>{cb.checked=checked;document.querySelectorAll(`[data-id="${cb.dataset.id}"]`).forEach(el=>el.checked=checked);saveSelect(cb.dataset.id,checked);});updateSummary();syncStoreChecks();}
function syncStoreChecks(){document.querySelectorAll('.store-check').forEach(sc=>{const key=sc.dataset.store;const all=document.querySelectorAll(`.item-check-${key}:not(:disabled)`);const chkd=document.querySelectorAll(`.item-check-${key}:checked`);sc.checked=all.length>0&&chkd.length===all.length;sc.indeterminate=chkd.length>0&&chkd.length<all.length;});const all=document.querySelectorAll('.item-check:not(:disabled)');const chkd=document.querySelectorAll('.item-check:checked');const cb=document.getElementById('selectAllCb');if(!cb)return;cb.checked=all.length>0&&chkd.length===all.length;cb.indeterminate=chkd.length>0&&chkd.length<all.length;}

/* ── Qty AJAX ── */
async function ajaxQty(cartId,delta,stock){const qtyEls=[document.getElementById('qty-'+cartId),document.getElementById('qty-m-'+cartId)].filter(Boolean);const subEls=[document.getElementById('sub-'+cartId),document.getElementById('sub-m-'+cartId)].filter(Boolean);if(!qtyEls.length)return;const current=parseInt(qtyEls[0].textContent)||1;const newQty=current+delta;if(newQty<1)return;if(stock>0&&newQty>stock){showToast(`Stok hanya tersisa ${stock} unit.`,'error');return;}qtyEls.forEach(el=>el.textContent=newQty);const row=document.getElementById('row-'+cartId);const btns=row?.querySelectorAll('.cart-qty-btn');if(btns){btns[0].disabled=newQty<=1;btns[1].disabled=stock>0&&newQty>=stock;}const cb=document.querySelector(`.item-check[data-id="${cartId}"]`);if(cb){cb.dataset.qty=newQty;document.querySelectorAll(`[data-id="${cartId}"]`).forEach(el=>el.dataset.qty=newQty);const price=parseInt(cb.dataset.price)||0;subEls.forEach(el=>el.textContent='Rp '+(price*newQty).toLocaleString('id-ID'));updateSummary();}try{const res=await fetch(`/cart/update/${cartId}`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({qty:newQty})});if(!res.ok)throw new Error();}catch{showToast('Gagal update.','error');qtyEls.forEach(el=>el.textContent=current);if(cb){cb.dataset.qty=current;document.querySelectorAll(`[data-id="${cartId}"]`).forEach(el=>el.dataset.qty=current);subEls.forEach(el=>el.textContent='Rp '+(parseInt(cb.dataset.price)*current).toLocaleString('id-ID'));updateSummary();}}}

/* ── Remove / Clear ── */
function collapseAndRemove(el){if(!el)return;el.style.height=el.offsetHeight+'px';el.style.overflow='hidden';el.style.transition='all .3s ease';requestAnimationFrame(()=>{el.style.opacity='0';el.style.height='0';el.style.marginBottom='0';el.style.paddingTop='0';el.style.paddingBottom='0';});setTimeout(()=>{el.remove();cleanupEmptyGroups();},320);}
function cleanupEmptyGroups(){document.querySelectorAll('.store-group').forEach(g=>{if(g.querySelectorAll('.cart-row,.cart-card').length===0)collapseAndRemove(g);});updateSummary();syncStoreChecks();}
function updateCartBadge(delta){document.querySelectorAll('.cart-count').forEach(el=>{el.textContent=Math.max(0,(parseInt(el.textContent)||0)+delta);el.classList.add('pulse');setTimeout(()=>el.classList.remove('pulse'),300);});}
async function removeItem(id){showConfirm('Hapus produk ini dari keranjang?',async()=>{try{await fetch(`/cart/remove/${id}`,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});collapseAndRemove(document.getElementById('row-'+id));collapseAndRemove(document.getElementById('row-m-'+id));// Hapus juga popover yang terkait
const popD=document.getElementById('vp-'+id);if(popD)popD.remove();const popM=document.getElementById('vp-m-'+id);if(popM)popM.remove();updateCartBadge(-1);showToast('Produk dihapus.','success');}catch{showToast('Gagal hapus.','error');}});}
function deleteSelected(){const ids=[...new Set([...document.querySelectorAll('.item-check:checked')].map(cb=>cb.dataset.id))];if(!ids.length){showToast('Pilih produk dulu.','warning');return;}showConfirm(`Hapus ${ids.length} produk terpilih?`,async()=>{try{await fetch('/cart/remove-selected',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({ids})});ids.forEach(id=>{collapseAndRemove(document.getElementById('row-'+id));collapseAndRemove(document.getElementById('row-m-'+id));const pd=document.getElementById('vp-'+id);if(pd)pd.remove();const pm=document.getElementById('vp-m-'+id);if(pm)pm.remove();});updateCartBadge(-ids.length);showToast('Produk terpilih dihapus.','success');}catch{showToast('Gagal hapus.','error');}});}
async function clearCart(){showConfirm('Kosongkan semua keranjang?',async()=>{try{await fetch('{{ route("cart.clear") }}',{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});document.querySelectorAll('.cart-row,.cart-card').forEach(el=>collapseAndRemove(el));document.querySelectorAll('.vp-popover').forEach(p=>p.remove());setTimeout(()=>{const w=document.querySelector('.cart-wrap');if(w)w.innerHTML=`<p class="cart-header-label">Taku</p><h1 class="cart-title">Keranjang Belanja</h1><div class="cart-empty"><span class="cart-empty-icon">🛒</span><p>Keranjang kamu masih kosong.</p><a href="{{ route('products') }}" class="cart-empty-btn">Mulai Belanja</a></div>`;},400);showToast('Keranjang dikosongkan.','success');}catch{showToast('Gagal.','error');}});}

function toggleStoreCollapse(btn){const body=btn.closest('.store-group').querySelector('.cart-table,.cart-mobile');if(!body)return;const collapsed=body.style.display==='none';body.style.display=collapsed?'':'none';btn.style.transform=collapsed?'rotate(0deg)':'rotate(-90deg)';}
function toggleEditMode(){editMode=!editMode;const btn=document.getElementById('btnEdit');const del=document.getElementById('btnDelSelected');if(btn){btn.textContent=editMode?'Selesai':'Ubah';btn.classList.toggle('active',editMode);}if(del)del.classList.toggle('show',editMode);}

let confirmCallback=null;
function showConfirm(msg,cb){document.getElementById('confirmText').textContent=msg;document.getElementById('confirmWrap').classList.add('show');confirmCallback=cb;}
function closeConfirm(){document.getElementById('confirmWrap').classList.remove('show');confirmCallback=null;}
document.getElementById('confirmOkBtn').onclick=()=>{if(confirmCallback)confirmCallback();closeConfirm();};

document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.item-check').forEach(cb=>{const id=cb.dataset.id;const qtyEl=document.getElementById('qty-'+id)||document.getElementById('qty-m-'+id);if(qtyEl)cb.dataset.qty=parseInt(qtyEl.textContent)||1;});
    syncStoreChecks();setTimeout(updateSummary,50);
    if(document.querySelector('.stock-out-badge'))setTimeout(()=>showToast('Beberapa produk stok habis.','warning'),700);
    if(document.querySelector('.stale-badge'))setTimeout(()=>showToast('Beberapa produk perlu pilih ukuran dulu.','warning'),900);
});
</script>
@endsection
