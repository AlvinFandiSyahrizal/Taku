<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Cart key format:
     *   - Produk dengan variant : "{productId}_{variantId}"
     *   - Produk tanpa variant  : "{productId}"
     *
     * Ini supaya 1 produk bisa masuk cart 2x dengan variant berbeda.
     */
    private function makeKey(int $productId, ?int $variantId): string
    {
        return $variantId ? "{$productId}_{$variantId}" : (string) $productId;
    }

    // ── GET 

    public function get(): array
    {
        if (Auth::check()) {
            return $this->getFromDb();
        }
        return $this->getFromSession();
    }

    private function getFromDb(): array
    {
        $items = CartItem::where('user_id', Auth::id())
            ->with(['product', 'product.store', 'variant'])
            ->get();

        $cart = [];
        foreach ($items as $item) {
            if (!$item->product) continue;

            $storeIsActive = $item->product->store_id === null
                ? true
                : $item->product->store?->status === 'active';

            // Kalau ada variant, pakai harga & stok dari variant
            $price = $item->variant
                ? $item->variant->price
                : $item->product->getFinalPrice();

            $stock = $item->variant
                ? $item->variant->stock
                : ($item->product->stock ?? 0);

            $variantLabel = $item->variant?->getLabel();

            $key = $this->makeKey($item->product_id, $item->variant_id);

            $cart[$key] = [
                'product_id'      => $item->product_id,
                'variant_id'      => $item->variant_id,
                'variant_label'   => $variantLabel,
                'store_id'        => $item->store_id,
                'store_name'      => $item->product->store?->name ?? 'Taku Official',
                'store_slug'      => $item->product->store?->slug ?? null,
                'name'            => $item->product->name,
                'price'           => $price,
                'original_price'  => $item->product->price,
                'image'           => $item->product->image,
                'qty'             => $item->qty,
                'is_selected'     => (bool) $item->is_selected,
                'stock'           => $stock,
                'is_active'       => (bool) $item->product->is_active,
                'store_is_active' => $storeIsActive,
            ];
        }
        return $cart;
    }

    private function getFromSession(): array
    {
        return session('cart', []);
    }

    // ── GROUPED (untuk Cart & Checkout view)

    public function getGrouped(): array
    {
        $cart    = $this->get();
        $grouped = [];

        foreach ($cart as $key => $item) {
            $isActive    = $item['is_active'] ?? true;
            $storeActive = $item['store_is_active'] ?? true;

            if (!$isActive || !$storeActive) continue;

            $storeKey = $item['store_id'] ?? 'official';

            if (!isset($grouped[$storeKey])) {
                $grouped[$storeKey] = [
                    'store_id'   => $item['store_id'] ?? null,
                    'store_name' => $item['store_name'] ?? 'Taku Official',
                    'store_slug' => $item['store_slug'] ?? null,
                    'items'      => [],
                ];
            }

            $grouped[$storeKey]['items'][$key] = $item;
        }

        return $grouped;
    }

    public function getUnavailable(): array
    {
        $cart        = $this->get();
        $unavailable = [];

        foreach ($cart as $key => $item) {
            $isActive    = $item['is_active'] ?? true;
            $storeActive = $item['store_is_active'] ?? true;

            if (!$isActive || !$storeActive) {
                $unavailable[$key] = $item;
            }
        }

        return $unavailable;
    }

    // ── ADD

    public function add(int $productId, int $qty = 1, ?int $variantId = null): void
    {
        $product = Product::with('store')->find($productId);
        if (!$product) return;

        $variant = $variantId ? ProductVariant::find($variantId) : null;

        $storeId    = $product->store_id;
        $storeName  = $product->store?->name ?? 'Taku Official';
        $storeSlug  = $product->store?->slug ?? null;
        $price      = $variant ? $variant->price : $product->getFinalPrice();
        $stock      = $variant ? $variant->stock : $product->stock;

        if (Auth::check()) {
            $item = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)  // null-safe: WHERE variant_id IS NULL atau = X
                ->first();

            if ($item) {
                $item->increment('qty', $qty);
            } else {
                CartItem::create([
                    'user_id'     => Auth::id(),
                    'product_id'  => $productId,
                    'variant_id'  => $variantId,
                    'store_id'    => $storeId,
                    'qty'         => $qty,
                    'is_selected' => true,
                ]);
            }
        } else {
            $cart        = session('cart', []);
            $key         = $this->makeKey($productId, $variantId);
            $variantLabel = $variant?->getLabel();

            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $qty;
            } else {
                $cart[$key] = [
                    'product_id'      => $productId,
                    'variant_id'      => $variantId,
                    'variant_label'   => $variantLabel,
                    'store_id'        => $storeId,
                    'store_name'      => $storeName,
                    'store_slug'      => $storeSlug,
                    'name'            => $product->name,
                    'price'           => $price,
                    'original_price'  => $product->price,
                    'image'           => $product->image,
                    'qty'             => $qty,
                    'is_active'       => $product->is_active,
                    'store_is_active' => $product->store?->status === 'active',
                    'stock'           => $stock,
                ];
            }
            session(['cart' => $cart]);
        }
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────

    /**
     * Update qty berdasarkan cart key (format: "productId" atau "productId_variantId")
     */
    public function updateByKey(string $key, int $qty): void
    {
        [$productId, $variantId] = $this->parseKey($key);

        if (Auth::check()) {
            $query = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId);

            if ($qty <= 0) {
                $query->delete();
            } else {
                $query->update(['qty' => $qty]);
            }
        } else {
            $cart = session('cart', []);
            if ($qty <= 0) {
                unset($cart[$key]);
            } elseif (isset($cart[$key])) {
                $cart[$key]['qty'] = $qty;
            }
            session(['cart' => $cart]);
        }
    }

    /** Backward-compat: update by product_id saja (untuk produk tanpa variant) */
    public function update(int $productId, int $qty): void
    {
        $this->updateByKey((string) $productId, $qty);
    }

    // ── REMOVE

    public function removeByKey(string $key): void
    {
        [$productId, $variantId] = $this->parseKey($key);

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$key]);
            session(['cart' => $cart]);
        }
    }

    /** Backward-compat */
    public function remove(int $productId): void
    {
        $this->removeByKey((string) $productId);
    }

    public function removeItems(array $keys): void
    {
        foreach ($keys as $key) {
            $this->removeByKey((string) $key);
        }
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }
        session()->forget('cart');
    }

    // ── TOTAL & COUNT
    public function total(): int
    {
        return array_sum(array_map(
            fn($i) => ($i['price'] ?? 0) * $i['qty'],
            $this->get()
        ));
    }

    public function count(): int
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->sum('qty');
        }
        return array_sum(array_column(session('cart', []), 'qty'));
    }

    // ── MERGE session → DB setelah login ─────────────────────────────────────

    public function mergeSessionToDb(): void
    {
        $sessionCart = session('cart', []);
        if (empty($sessionCart)) return;

        foreach ($sessionCart as $key => $item) {
            $productId = $item['product_id'];
            $variantId = $item['variant_id'] ?? null;

            $existing = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->first();

            if ($existing) {
                $existing->increment('qty', $item['qty']);
            } else {
                CartItem::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'store_id'   => $item['store_id'] ?? null,
                    'qty'        => $item['qty'],
                    'is_selected' => true,
                ]);
            }
        }

        session()->forget('cart');
    }

    // ── PRIVATE HELPERS ───────────────────────────────────────────────────────

    /**
     * Parse cart key jadi [productId, variantId|null]
     * Key format: "15" atau "15_3"
     */
    private function parseKey(string $key): array
    {
        $parts     = explode('_', $key, 2);
        $productId = (int) $parts[0];
        $variantId = isset($parts[1]) ? (int) $parts[1] : null;
        return [$productId, $variantId];
    }
}
