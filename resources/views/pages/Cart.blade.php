@extends('layouts.app')
@section('content')
@php app()->setLocale(session('lang', 'id')); @endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=DM+Sans:wght@300;400;500&display=swap');
*{box-sizing:border-box}

:root{
    --navy:#0b2a4a;
    --navy-mid:rgba(11,42,74,.55);
    --navy-soft:rgba(11,42,74,.08);
    --gold:#c9a96e;
    --gold-soft:rgba(201,169,110,.12);
    --beige:#f5efe6;
    --beige-mid:#ede5d8;
    --olive:#4a5240;
    --olive-soft:rgba(74,82,64,.08);
    --sand:#d4c5a9;
    --terracotta:#c0654a;
    --terracotta-dark:#a6533c;
    --danger:#c0392b;
    --success:#1a7a3c;
}

.cart-wrap{max-width:980px;margin:52px auto 130px;padding:0 40px;font-family:'DM Sans',sans-serif;}
@media(max-width:640px){.cart-wrap{padding:0 16px;margin-top:28px;margin-bottom:120px;}}

.cart-header-label{font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:6px;}
.cart-title{font-family:'Cormorant Garamond',serif;font-weight:400;font-size:38px;color:var(--navy);margin-bottom:32px;}
@media(max-width:640px){.cart-title{font-size:28px;}}

.toast-wrap{position:fixed;top:80px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{background:var(--navy);color:#f0ebe0;padding:12px 18px;border-radius:10px;font-size:13px;font-family:'DM Sans',sans-serif;opacity:0;transform:translateY(-8px);transition:all .25s;max-width:300px;line-height:1.5;}
.toast.show{opacity:1;transform:translateY(0);}
.toast.error{background:var(--danger);}
.toast.success{background:var(--success);}
.toast.warning{background:var(--gold);color:var(--navy);}

.confirm-wrap{position:fixed;inset:0;background:rgba(0,0,0,.22);display:flex;align-items:center;justify-content:center;z-index:9999;opacity:0;pointer-events:none;transition:.25s;}
.confirm-wrap.show{opacity:1;pointer-events:auto;}
.confirm-box{background:white;padding:28px 32px;border-radius:16px;width:300px;text-align:center;box-shadow:0 24px 60px rgba(11,42,74,.15);transform:translateY(16px);transition:.25s;}
.confirm-wrap.show .confirm-box{transform:translateY(0);}
.confirm-box p{font-size:14px;color:var(--navy);margin-bottom:20px;line-height:1.6;}
.confirm-actions{display:flex;gap:10px;}
.btn-cancel{flex:1;padding:10px;border-radius:8px;border:1px solid var(--beige-mid);background:white;cursor:pointer;font-size:12px;color:var(--navy-mid);font-family:'DM Sans',sans-serif;transition:all .2s;}
.btn-cancel:hover{background:var(--beige);border-color:var(--sand);}
.btn-confirm{flex:1;padding:10px;border-radius:8px;border:none;background:var(--navy);color:#f0ebe0;cursor:pointer;font-size:12px;font-family:'DM Sans',sans-serif;transition:all .2s;}
.btn-confirm:hover{background:var(--olive);transform:translateY(-1px);box-shadow:0 6px 16px rgba(74,82,64,.25);}

.flash-ok{background:#f0f7f0;border:.5px solid #b2d9b2;border-radius:8px;padding:11px 16px;font-size:13px;color:#1a7a3c;margin-bottom:16px;}
.flash-err{background:#fdf0f0;border:.5px solid #f5c0c0;border-radius:8px;padding:11px 16px;font-size:13px;color:var(--danger);margin-bottom:16px;}

.cart-empty{text-align:center;padding:88px 0;color:rgba(11,42,74,.35);}
.cart-empty-icon{font-size:52px;margin-bottom:18px;display:block;}
.cart-empty p{font-size:15px;margin-bottom:28px;}
.cart-empty-btn{display:inline-block;padding:12px 30px;background:var(--navy);color:#f0ebe0;text-decoration:none;border-radius:8px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:500;transition:all .2s;}
.cart-empty-btn:hover{background:var(--olive);transform:translateY(-1px);box-shadow:0 6px 18px rgba(74,82,64,.25);}

.cart-toolbar{display:flex;align-items:center;gap:12px;padding:10px 0;margin-bottom:14px;border-bottom:.5px solid var(--navy-soft);}
.toolbar-check{width:15px;height:15px;accent-color:var(--navy);cursor:pointer;}
.toolbar-label{font-size:12px;color:var(--navy-mid);cursor:pointer;user-select:none;}
.toolbar-label:hover{color:var(--navy);}
.toolbar-sep{width:.5px;height:14px;background:rgba(11,42,74,.12);}
.btn-edit{background:none;border:none;cursor:pointer;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-family:'DM Sans',sans-serif;color:var(--navy-mid);transition:color .2s;padding:4px 0;}
.btn-edit:hover{color:var(--navy);}
.btn-edit.active{color:var(--gold);}
.btn-del-selected{background:none;border:none;cursor:pointer;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-family:'DM Sans',sans-serif;color:var(--danger);transition:opacity .2s;padding:4px 0;display:none;}
.btn-del-selected.show{display:inline;}

.store-group{margin-bottom:14px;border-radius:14px;overflow:hidden;border:.5px solid rgba(11,42,74,.1);box-shadow:0 2px 12px rgba(11,42,74,.04);}
.store-group-header{display:flex;align-items:center;gap:10px;padding:12px 16px;background:linear-gradient(to right,var(--beige),#f9f7f3);border-bottom:.5px solid rgba(11,42,74,.07);}
.store-group-check{width:15px;height:15px;accent-color:var(--navy);cursor:pointer;flex-shrink:0;}
.store-group-name{font-size:13px;font-weight:500;color:var(--navy);text-decoration:none;flex:1;transition:color .2s;}
.store-group-name:hover{color:var(--gold);}
.store-group-badge{font-size:9px;letter-spacing:.1em;text-transform:uppercase;background:rgba(11,42,74,.06);color:rgba(11,42,74,.45);padding:2px 9px;border-radius:100px;}
.store-toggle{border:none;background:none;cursor:pointer;font-size:14px;transition:.3s;color:var(--gold);}
.store-toggle.rotate{transform:rotate(-90deg);}

.cart-table{width:100%;border-collapse:collapse;background:white;}
.cart-table th{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:rgba(11,42,74,.35);font-weight:400;padding:10px 16px;border-bottom:.5px solid rgba(11,42,74,.06);text-align:left;}
.cart-table th:last-child,.cart-subtotal{text-align:right;}
.cart-row td{padding:14px 16px;border-bottom:.5px solid rgba(11,42,74,.04);vertical-align:middle;}
.cart-row:last-child td{border-bottom:none;}
.cart-row{transition:background .2s;}
.cart-row:hover{background:var(--gold-soft);}
.item-check{width:15px;height:15px;accent-color:var(--navy);cursor:pointer;}

.cart-product{display:flex;align-items:center;gap:13px;}
.cart-product-img{width:60px;height:60px;object-fit:cover;border-radius:9px;flex-shrink:0;border:.5px solid rgba(11,42,74,.08);transition:opacity .2s;}
.cart-product-img:hover{opacity:.85;}
.cart-product-name{font-size:13px;font-weight:500;color:var(--navy);text-decoration:none;display:block;line-height:1.4;transition:color .2s;}
.cart-product-name:hover{color:var(--gold);}
.cart-product-meta{font-size:11px;color:rgba(11,42,74,.4);margin-top:2px;}

.price-final{font-size:13px;color:var(--navy);font-weight:500;white-space:nowrap;line-height:1.4;}
.price-original{font-size:11px;color:rgba(11,42,74,.3);text-decoration:line-through;white-space:nowrap;}

.cart-qty-wrap{display:inline-flex;align-items:center;border:.5px solid rgba(11,42,74,.18);border-radius:8px;overflow:hidden;}
.cart-qty-btn{background:none;border:none;cursor:pointer;width:30px;height:30px;font-size:16px;color:var(--navy);display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;line-height:1;}
.cart-qty-btn:hover:not(:disabled){background:var(--navy);color:white;}
.cart-qty-btn:active{transform:scale(.9);}
.cart-qty-btn:disabled{opacity:.3;cursor:not-allowed;}
.cart-qty-val{width:34px;text-align:center;font-size:13px;font-weight:500;color:var(--navy);border:none;border-left:.5px solid rgba(11,42,74,.1);border-right:.5px solid rgba(11,42,74,.1);height:30px;font-family:'DM Sans',sans-serif;background:white;line-height:30px;}
.cart-remove-btn{background:none;border:none;cursor:pointer;color:rgba(11,42,74,.25);font-size:11px;letter-spacing:.08em;text-transform:uppercase;font-family:'DM Sans',sans-serif;transition:color .2s;padding:3px 0;display:block;margin-top:5px;}
.cart-remove-btn:hover{color:var(--danger);}
.cart-subtotal{font-size:13px;font-weight:500;color:var(--navy);white-space:nowrap;}

.stock-out-badge{display:inline-block;font-size:10px;color:var(--danger);background:rgba(192,57,43,.08);padding:2px 8px;border-radius:100px;border:.5px solid rgba(192,57,43,.2);margin-top:3px;}

.cart-card{padding:14px 16px 12px;border-bottom:.5px solid rgba(11,42,74,.04);background:white;transition:all .25s ease;}
.cart-card:last-child{border-bottom:none;}
.cart-card:hover{background:var(--gold-soft);}
.cart-card-top{display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;}
.cart-card-img{width:58px;height:58px;object-fit:cover;border-radius:9px;flex-shrink:0;border:.5px solid rgba(11,42,74,.08);}
.cart-card-info{flex:1;min-width:0;}
.cart-card-bottom{display:flex;align-items:center;justify-content:space-between;padding-left:25px;}

.cart-desktop{display:table;width:100%;}
.cart-mobile{display:none;}
@media(max-width:640px){
    .cart-desktop{display:none;}
    .cart-mobile{display:block;}
}

.cart-sticky-footer{
    position:sticky;
    bottom:0;
    background:rgba(245,239,230,.92);
    backdrop-filter:blur(12px);
    -webkit-backdrop-filter:blur(12px);
    border-top:1px solid var(--beige-mid);
    border-radius:16px 16px 0 0;
    padding:18px 28px;
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
    z-index:50;
    box-shadow:0 -8px 32px rgba(11,42,74,.07);
}

.footer-summary{
    display:flex;
    flex-direction:column;
    gap:3px;
    min-width:0;
}

.footer-meta{
    font-size:11px;
    letter-spacing:.06em;
    color:var(--navy-mid);
}
.footer-meta span{
    color:var(--navy);
    font-weight:600;
}

.footer-total{
    font-family:'Cormorant Garamond',serif;
    font-size:30px;
    color:var(--navy);
    line-height:1.1;
    font-weight:500;
}

.footer-right{
    display:flex;
    align-items:center;
    gap:10px;
    flex-shrink:0;
}

.btn-clear{
    padding:11px 20px;
    border-radius:9px;
    font-size:11px;
    letter-spacing:.12em;
    text-transform:uppercase;
    font-family:'DM Sans',sans-serif;
    font-weight:500;
    border:1.5px solid rgba(74,82,64,.3);
    background:transparent;
    color:var(--olive);
    cursor:pointer;
    transition:all .25s ease;
}
.btn-clear:hover{
    background:var(--olive);
    color:#f5efe6;
    border-color:var(--olive);
    box-shadow:0 6px 20px rgba(74,82,64,.22);
    transform:translateY(-1px);
}

.btn-checkout{
    position:relative;
    padding:11px 28px;
    border-radius:9px;
    font-size:11px;
    letter-spacing:.14em;
    text-transform:uppercase;
    font-family:'DM Sans',sans-serif;
    font-weight:500;
    background:var(--navy);
    color:#f5efe6;
    border:none;
    cursor:pointer;
    transition:all .25s ease;
    overflow:hidden;
}
.btn-checkout::after{
    content:'';
    position:absolute;
    bottom:0;left:0;right:0;
    height:2px;
    background:linear-gradient(90deg,transparent,var(--gold),transparent);
    opacity:0;
    transition:opacity .3s;
}
.btn-checkout:hover{
    background:#0d3459;
    box-shadow:0 8px 24px rgba(11,42,74,.28);
    transform:translateY(-1px);
}
.btn-checkout:hover::after{
    opacity:1;
}
.btn-checkout:disabled{
    background:rgba(11,42,74,.18);
    color:rgba(11,42,74,.35);
    cursor:not-allowed;
    box-shadow:none;
    transform:none;
}
.btn-checkout:disabled::after{opacity:0;}

@media(max-width:640px){
    .cart-sticky-footer{padding:14px 16px;border-radius:14px 14px 0 0;}
    .footer-total{font-size:22px;}
    .btn-checkout{padding:10px 20px;font-size:10px;}
    .btn-clear{padding:10px 14px;font-size:10px;}
}

.cart-count.pulse{animation:pulse .3s ease;}
@keyframes pulse{0%{transform:scale(1);}50%{transform:scale(1.3);}100%{transform:scale(1);}}
</style>

<div class="toast-wrap" id="toastWrap"></div>
<div class="confirm-wrap" id="confirmWrap">
    <div class="confirm-box">
        <p id="confirmText">Yakin?</p>
        <div class="confirm-actions">
            <button onclick="closeConfirm()" class="btn-cancel">Batal</button>
            <button id="confirmOkBtn" class="btn-confirm">Ya, lanjut</button>
        </div>
    </div>
</div>

<div class="cart-wrap">
    <p class="cart-header-label">Taku</p>
    <h1 class="cart-title">Keranjang Belanja</h1>

    @if(session('success'))
    <div class="flash-ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="flash-err">{{ session('error') }}</div>
    @endif

    @if(empty($grouped))
    <div class="cart-empty">
        <span class="cart-empty-icon">🛒</span>
        <p>Keranjang kamu masih kosong.</p>
        <a href="{{ route('products') }}" class="cart-empty-btn">Mulai Belanja</a>
    </div>
    @else

    {{-- Toolbar --}}
    <div class="cart-toolbar">
        <input type="checkbox" id="selectAllCb" class="toolbar-check" onchange="selectAll(this.checked)" title="Pilih semua">
        <label for="selectAllCb" class="toolbar-label">Pilih Semua</label>
        <div class="toolbar-sep"></div>
        <button type="button" class="btn-edit" id="btnEdit" onclick="toggleEditMode()">Ubah</button>
        <button type="button" class="btn-del-selected" id="btnDelSelected" onclick="deleteSelected()">
            Hapus yang Dipilih
        </button>
    </div>

    {{-- Cart items --}}
    <div id="cartItems">
        @foreach($grouped as $storeKey => $group)
        <div class="store-group">
            <div class="store-group-header">
                <input type="checkbox" class="store-group-check store-check"
                    data-store="{{ $storeKey }}"
                    onchange="toggleStore('{{ $storeKey }}', this.checked)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(11,42,74,.45)" stroke-width="1.5" style="flex-shrink:0;">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                @if($group['store_slug'] ?? null)
                    <a href="{{ route('store.show', $group['store_slug']) }}" class="store-group-name">{{ $group['store_name'] }}</a>
                @else
                    <span class="store-group-name" style="cursor:default;">{{ $group['store_name'] }}</span>
                @endif
                <span class="store-group-badge">{{ count($group['items']) }} produk</span>
                <button type="button" class="store-toggle" onclick="toggleStoreCollapse(this)">▾</button>
            </div>

            {{-- Desktop --}}
            <table class="cart-table cart-desktop">
                <thead>
                    <tr>
                        <th style="width:20px;"></th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($group['items'] as $id => $item)
                @php
                    $finalPrice    = (int)$item['price'];
                    $originalPrice = (int)($item['original_price'] ?? $finalPrice);
                    $hasDiscount   = $originalPrice > $finalPrice;
                    $subtotal      = $finalPrice * $item['qty'];
                    $stock         = (int)($item['stock'] ?? 999);
                    $stockOut      = $stock === 0;
                    $isSelected    = (bool)($item['is_selected'] ?? true);
                @endphp
                <tr class="cart-row{{ $stockOut ? ' stock-out-row' : '' }}" id="row-{{ $id }}">
                    <td>
                        <input type="checkbox"
                               class="item-check item-check-{{ $storeKey }}"
                               data-id="{{ $id }}"
                               data-price="{{ $finalPrice }}"
                               data-qty="{{ $item['qty'] }}"
                               {{ $isSelected && !$stockOut ? 'checked' : '' }}
                               {{ $stockOut ? 'disabled' : '' }}
                               onchange="onItemCheck(this, {{ $id }})">
                    </td>
                    <td>
                        <div class="cart-product">
                            <a href="{{ route('product.show', $id) }}">
                                <img src="{{ asset($item['image'] ?? 'images/placeholder.jpg') }}"
                                     class="cart-product-img" alt="{{ $item['name'] }}"
                                     style="{{ $stockOut ? 'opacity:.4;' : '' }}"
                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                            </a>
                            <div>
                                <a href="{{ route('product.show', $id) }}" class="cart-product-name"
                                   style="{{ $stockOut ? 'color:rgba(11,42,74,.4);' : '' }}">
                                    {{ $item['name'] }}
                                </a>
                                @if($stockOut)
                                    <span class="stock-out-badge">Stok habis</span>
                                @elseif($hasDiscount)
                                    <p class="cart-product-meta">Hemat Rp {{ number_format($originalPrice - $finalPrice,0,',','.') }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($hasDiscount)
                            <p class="price-final">Rp {{ number_format($finalPrice,0,',','.') }}</p>
                            <p class="price-original">Rp {{ number_format($originalPrice,0,',','.') }}</p>
                        @else
                            <p class="price-final">Rp {{ number_format($finalPrice,0,',','.') }}</p>
                        @endif
                    </td>
                    <td>
                        <div class="cart-qty-wrap">
                            <button type="button" class="cart-qty-btn"
                                    onclick="ajaxQty({{ $id }}, -1, {{ $stock }})"
                                    {{ $item['qty'] <= 1 || $stockOut ? 'disabled' : '' }}>−</button>
                            <span class="cart-qty-val" id="qty-{{ $id }}">{{ $item['qty'] }}</span>
                            <button type="button" class="cart-qty-btn"
                                    onclick="ajaxQty({{ $id }}, 1, {{ $stock }})"
                                    {{ ($stock > 0 && $item['qty'] >= $stock) || $stockOut ? 'disabled' : '' }}>+</button>
                        </div>
                        <form action="{{ route('cart.remove', $id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="button" onclick="removeItem({{ $id }})" class="cart-remove-btn">Hapus</button>
                        </form>
                    </td>
                    <td class="cart-subtotal" id="sub-{{ $id }}">
                        {{ $stockOut ? '—' : 'Rp '.number_format($subtotal,0,',','.') }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="cart-mobile">
                @foreach($group['items'] as $id => $item)
                @php
                    $finalPrice    = (int)$item['price'];
                    $originalPrice = (int)($item['original_price'] ?? $finalPrice);
                    $hasDiscount   = $originalPrice > $finalPrice;
                    $subtotal      = $finalPrice * $item['qty'];
                    $stock         = (int)($item['stock'] ?? 999);
                    $stockOut      = $stock === 0;
                    $isSelected    = (bool)($item['is_selected'] ?? true);
                @endphp
                <div class="cart-card" id="row-m-{{ $id }}">
                    <div class="cart-card-top">
                        <input type="checkbox"
                               class="item-check item-check-{{ $storeKey }}"
                               data-id="{{ $id }}"
                               data-price="{{ $finalPrice }}"
                               data-qty="{{ $item['qty'] }}"
                               {{ $isSelected && !$stockOut ? 'checked' : '' }}
                               {{ $stockOut ? 'disabled' : '' }}
                               onchange="onItemCheck(this, {{ $id }})">
                        <a href="{{ route('product.show', $id) }}">
                            <img src="{{ asset($item['image'] ?? 'images/placeholder.jpg') }}"
                                 class="cart-card-img" alt="{{ $item['name'] }}"
                                 style="{{ $stockOut ? 'opacity:.4;' : '' }}"
                                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        </a>
                        <div class="cart-card-info">
                            <a href="{{ route('product.show', $id) }}" class="cart-product-name">{{ $item['name'] }}</a>
                            @if($stockOut)
                                <span class="stock-out-badge">Stok habis</span>
                            @else
                                <p class="price-final" style="margin-top:3px;">Rp {{ number_format($finalPrice,0,',','.') }}</p>
                                @if($hasDiscount)
                                <p class="price-original">Rp {{ number_format($originalPrice,0,',','.') }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="cart-card-bottom">
                        <form action="{{ route('cart.remove', $id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="cart-remove-btn" style="margin-top:0;">Hapus</button>
                        </form>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span class="cart-subtotal" id="sub-m-{{ $id }}">
                                {{ $stockOut ? '—' : 'Rp '.number_format($subtotal,0,',','.') }}
                            </span>
                            <div class="cart-qty-wrap">
                                <button type="button" class="cart-qty-btn"
                                        onclick="ajaxQty({{ $id }}, -1, {{ $stock }})"
                                        {{ $item['qty'] <= 1 || $stockOut ? 'disabled' : '' }}>−</button>
                                <span class="cart-qty-val" id="qty-m-{{ $id }}">{{ $item['qty'] }}</span>
                                <button type="button" class="cart-qty-btn"
                                        onclick="ajaxQty({{ $id }}, 1, {{ $stock }})"
                                        {{ ($stock > 0 && $item['qty'] >= $stock) || $stockOut ? 'disabled' : '' }}>+</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Form checkout --}}
    <form action="{{ route('checkout.select') }}" method="POST" id="checkoutForm">
        @csrf
        <div id="selectedInputs"></div>
        <div class="cart-sticky-footer">
            <div class="footer-summary">
                <p class="footer-meta"><span id="selectedCount">0</span> produk dipilih</p>
                <p class="footer-total" id="selectedTotal">Rp 0</p>
            </div>
            <div class="footer-right">
                <button type="button" class="btn-clear" onclick="clearCart()">
                    Kosongkan
                </button>
                <button type="submit" class="btn-checkout" id="checkoutBtn" disabled>
                    Checkout
                </button>
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

function showToast(msg, type='info') {
    const wrap = document.getElementById('toastWrap');
    if (!wrap) return;
    const t = document.createElement('div');
    t.className = 'toast ' + type;
    t.textContent = msg;
    wrap.appendChild(t);
    requestAnimationFrame(() => t.classList.add('show'));
    setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 2500);
}

function animateNumber(el, start, end) {
    let startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        const p = Math.min((ts - startTime) / 300, 1);
        el.textContent = 'Rp ' + Math.floor(start + (end - start) * p).toLocaleString('id-ID');
        if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

function updateSummary() {
    const seen = new Set();
    let count = 0, total = 0;
    document.querySelectorAll('.item-check:checked').forEach(cb => {
        const id = cb.dataset.id;
        if (seen.has(id)) return;
        seen.add(id);
        count++;
        total += (parseInt(cb.dataset.price) || 0) * (parseInt(cb.dataset.qty) || 1);
    });
    const countEl = document.getElementById('selectedCount');
    const totalEl = document.getElementById('selectedTotal');
    if (countEl) countEl.textContent = count;
    if (totalEl) {
        const cur = parseInt(totalEl.textContent.replace(/\D/g,'')) || 0;
        animateNumber(totalEl, cur, total);
    }
    const btn = document.getElementById('checkoutBtn');
    if (btn) btn.disabled = count === 0;
    const container = document.getElementById('selectedInputs');
    if (container) {
        container.innerHTML = '';
        seen.forEach(id => {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'selected[]'; inp.value = id;
            container.appendChild(inp);
        });
    }
}

async function saveSelect(id, selected) {
    try {
        await fetch(`/cart/select/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ selected })
        });
    } catch {}
}

function onItemCheck(cb, id) {
    const checked = cb.checked;
    document.querySelectorAll(`[data-id="${id}"]`).forEach(el => el.checked = checked);
    saveSelect(id, checked);
    updateSummary();
    syncStoreChecks();
}

function selectAll(checked) {
    document.querySelectorAll('.item-check:not(:disabled)').forEach(cb => {
        cb.checked = checked;
        document.querySelectorAll(`[data-id="${cb.dataset.id}"]`).forEach(el => el.checked = checked);
    });
    fetch('/cart/select-all', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ selected: checked })
    });
    updateSummary();
    syncStoreChecks();
}

function toggleStore(storeKey, checked) {
    document.querySelectorAll(`.item-check-${storeKey}:not(:disabled)`).forEach(cb => {
        cb.checked = checked;
        document.querySelectorAll(`[data-id="${cb.dataset.id}"]`).forEach(el => el.checked = checked);
        saveSelect(cb.dataset.id, checked);
    });
    updateSummary();
    syncStoreChecks();
}

function syncStoreChecks() {
    document.querySelectorAll('.store-check').forEach(sc => {
        const key  = sc.dataset.store;
        const all  = document.querySelectorAll(`.item-check-${key}:not(:disabled)`);
        const chkd = document.querySelectorAll(`.item-check-${key}:checked`);
        sc.checked = all.length > 0 && chkd.length === all.length;
        sc.indeterminate = chkd.length > 0 && chkd.length < all.length;
    });
    const all  = document.querySelectorAll('.item-check:not(:disabled)');
    const chkd = document.querySelectorAll('.item-check:checked');
    const cb   = document.getElementById('selectAllCb');
    if (!cb) return;
    cb.checked = all.length > 0 && chkd.length === all.length;
    cb.indeterminate = chkd.length > 0 && chkd.length < all.length;
}

function updateCartBadge(delta) {
    document.querySelectorAll('.cart-count').forEach(el => {
        const v = Math.max(0, (parseInt(el.textContent) || 0) + delta);
        el.textContent = v;
        el.classList.add('pulse');
        setTimeout(() => el.classList.remove('pulse'), 300);
    });
}

function collapseAndRemove(el) {
    if (!el) return;
    el.style.height = el.offsetHeight + 'px';
    el.style.overflow = 'hidden';
    el.style.transition = 'all .3s ease';
    requestAnimationFrame(() => {
        el.style.opacity = '0';
        el.style.height = '0';
        el.style.marginBottom = '0';
        el.style.paddingTop = '0';
        el.style.paddingBottom = '0';
    });
    setTimeout(() => { el.remove(); cleanupEmptyGroups(); }, 320);
}

function cleanupEmptyGroups() {
    document.querySelectorAll('.store-group').forEach(g => {
        const rows = g.querySelectorAll('.cart-row, .cart-card');
        if (rows.length === 0) collapseAndRemove(g);
    });
    updateSummary();
    syncStoreChecks();
}

async function ajaxQty(productId, delta, stock) {
    const qtyEls = [document.getElementById('qty-'+productId), document.getElementById('qty-m-'+productId)].filter(Boolean);
    const subEls = [document.getElementById('sub-'+productId), document.getElementById('sub-m-'+productId)].filter(Boolean);
    if (!qtyEls.length) return;
    const current = parseInt(qtyEls[0].textContent) || 1;
    const newQty  = current + delta;
    if (newQty < 1) return;
    if (stock > 0 && newQty > stock) { showToast(`Stok hanya tersisa ${stock} unit.`, 'error'); return; }
    qtyEls.forEach(el => el.textContent = newQty);
    const row  = document.getElementById('row-'+productId);
    const btns = row?.querySelectorAll('.cart-qty-btn');
    if (btns) { btns[0].disabled = newQty <= 1; btns[1].disabled = stock > 0 && newQty >= stock; }
    const cb = document.querySelector(`.item-check[data-id="${productId}"]`);
    if (cb) {
        cb.dataset.qty = newQty;
        document.querySelectorAll(`[data-id="${productId}"]`).forEach(el => el.dataset.qty = newQty);
        const price  = parseInt(cb.dataset.price) || 0;
        const newSub = 'Rp ' + (price * newQty).toLocaleString('id-ID');
        subEls.forEach(el => el.textContent = newSub);
        updateSummary();
    }
    try {
        const res = await fetch(`/cart/update/${productId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ qty: newQty })
        });
        if (!res.ok) throw new Error();
    } catch {
        showToast('Gagal update, coba lagi.', 'error');
        qtyEls.forEach(el => el.textContent = current);
        if (cb) {
            cb.dataset.qty = current;
            document.querySelectorAll(`[data-id="${productId}"]`).forEach(el => el.dataset.qty = current);
            subEls.forEach(el => el.textContent = 'Rp ' + (parseInt(cb.dataset.price) * current).toLocaleString('id-ID'));
            updateSummary();
        }
    }
}

async function removeItem(id) {
    showConfirm('Hapus produk ini dari keranjang?', async () => {
        try {
            await fetch(`/cart/remove/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
            });
            collapseAndRemove(document.getElementById('row-'+id));
            collapseAndRemove(document.getElementById('row-m-'+id));
            updateCartBadge(-1);
            showToast('Produk dihapus.', 'success');
        } catch { showToast('Gagal hapus.', 'error'); }
    });
}

function deleteSelected() {
    const ids = [...new Set([...document.querySelectorAll('.item-check:checked')].map(cb => cb.dataset.id))];
    if (!ids.length) { showToast('Pilih produk dulu.', 'warning'); return; }
    showConfirm(`Hapus ${ids.length} produk terpilih?`, async () => {
        try {
            await fetch('/cart/remove-selected', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ ids })
            });
            ids.forEach(id => {
                collapseAndRemove(document.getElementById('row-'+id));
                collapseAndRemove(document.getElementById('row-m-'+id));
            });
            updateCartBadge(-ids.length);
            showToast('Produk terpilih dihapus.', 'success');
        } catch { showToast('Gagal hapus.', 'error'); }
    });
}

async function clearCart() {
    showConfirm('Kosongkan semua keranjang?', async () => {
        try {
            await fetch('{{ route("cart.clear") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
            });
            document.querySelectorAll('.cart-row, .cart-card').forEach(el => collapseAndRemove(el));
            setTimeout(() => {
                const wrap = document.querySelector('.cart-wrap');
                if (wrap) wrap.innerHTML = `<p class="cart-header-label">Taku</p><h1 class="cart-title">Keranjang Belanja</h1><div class="cart-empty"><span class="cart-empty-icon">🛒</span><p>Keranjang kamu masih kosong.</p><a href="{{ route('products') }}" class="cart-empty-btn">Mulai Belanja</a></div>`;
            }, 400);
            showToast('Keranjang dikosongkan.', 'success');
        } catch { showToast('Gagal.', 'error'); }
    });
}

function toggleStoreCollapse(btn) {
    const group = btn.closest('.store-group');
    const body  = group.querySelector('.cart-table, .cart-mobile');
    if (!body) return;
    const isCollapsed = body.style.display === 'none';
    body.style.display = isCollapsed ? '' : 'none';
    btn.style.transform = isCollapsed ? 'rotate(0deg)' : 'rotate(-90deg)';
}

function toggleEditMode() {
    editMode = !editMode;
    const btn = document.getElementById('btnEdit');
    const del = document.getElementById('btnDelSelected');
    if (btn) { btn.textContent = editMode ? 'Selesai' : 'Ubah'; btn.classList.toggle('active', editMode); }
    if (del) del.classList.toggle('show', editMode);
}

let confirmCallback = null;
function showConfirm(msg, cb) {
    document.getElementById('confirmText').textContent = msg;
    document.getElementById('confirmWrap').classList.add('show');
    confirmCallback = cb;
}
function closeConfirm() {
    document.getElementById('confirmWrap').classList.remove('show');
    confirmCallback = null;
}
document.getElementById('confirmOkBtn').onclick = () => { if (confirmCallback) confirmCallback(); closeConfirm(); };

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.item-check').forEach(cb => {
        const id   = cb.dataset.id;
        const qtyEl = document.getElementById('qty-'+id) || document.getElementById('qty-m-'+id);
        if (qtyEl) cb.dataset.qty = parseInt(qtyEl.textContent) || 1;
    });
    syncStoreChecks();
    setTimeout(() => updateSummary(), 50);
    const hasStockOut = document.querySelector('.stock-out-badge');
    if (hasStockOut) setTimeout(() => showToast('Beberapa produk di keranjang habis stok.', 'warning'), 600);
});
</script>

@endsection
