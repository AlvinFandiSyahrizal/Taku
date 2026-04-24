<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartService
{
    private function makeKey(int $productId, ?int $variantId): string
    {
        return $variantId ? "{$productId}_{$variantId}" : (string) $productId;
    }

    public function get(): array
    {
        return Auth::check() ? $this->getFromDb() : $this->getFromSession();
    }

    private function getFromDb(): array
    {
        $items = CartItem::where('user_id', Auth::id())
            ->with(['product', 'product.store', 'product.variants', 'variant'])
            ->get();

        $cart = [];
        foreach ($items as $item) {
            if (!$item->product) continue;

            $storeIsActive = $item->product->store_id === null
                ? true
                : $item->product->store?->status === 'active';

            if ($item->variant) {
                $finalPrice    = $item->variant->getFinalPrice();
                $originalPrice = $item->variant->price;
                $stock         = $item->variant->stock;
                $discPct       = $item->variant->discount_percent;
                $variantLabel  = $item->variant->getLabel();
            } else {
                $finalPrice    = $item->product->getFinalPrice();
                $originalPrice = $item->product->price;
                $stock         = $item->product->stock ?? 0;
                $discPct       = $item->product->discount_percent ?? 0;
                $variantLabel  = null;
            }

            // Semua variant produk ini untuk dropdown di cart
            $allVariants = $item->product->variants->map(fn($v) => [
                'id'               => $v->id,
                'label'            => $v->getLabel(),
                'price'            => $v->price,
                'final_price'      => $v->getFinalPrice(),
                'discount_percent' => $v->discount_percent,
                'stock'            => $v->stock,
            ])->toArray();

            $key = $this->makeKey($item->product_id, $item->variant_id);

            $cart[$key] = [
                'product_id'      => $item->product_id,
                'variant_id'      => $item->variant_id,
                'variant_label'   => $variantLabel,
                'all_variants'    => $allVariants,   // ← BARU
                'discount_pct'    => $discPct,
                'discount_percent'=> $discPct,
                'store_id'        => $item->product->store_id,
                'store_name'      => $item->product->store?->name ?? 'Taku Official',
                'store_slug'      => $item->product->store?->slug ?? null,
                'name'            => $item->product->name,
                'price'           => $finalPrice,
                'original_price'  => $originalPrice,
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

    public function getGrouped(): array
    {
        $cart    = $this->get();
        $grouped = [];

        foreach ($cart as $key => $item) {
            if (!($item['is_active'] ?? true) || !($item['store_is_active'] ?? true)) continue;

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
            if (!($item['is_active'] ?? true) || !($item['store_is_active'] ?? true)) {
                $unavailable[$key] = $item;
            }
        }

        return $unavailable;
    }

    public function add(int $productId, int $qty = 1, ?int $variantId = null): void
    {
        $product = Product::with('store')->find($productId);
        if (!$product) return;

        $variant      = $variantId ? ProductVariant::find($variantId) : null;
        $storeId      = $product->store_id;
        $storeName    = $product->store?->name ?? 'Taku Official';
        $storeSlug    = $product->store?->slug ?? null;
        $finalPrice   = $variant ? $variant->getFinalPrice() : $product->getFinalPrice();
        $origPrice    = $variant ? $variant->price : $product->price;
        $stock        = $variant ? $variant->stock : $product->stock;
        $discPct      = $variant ? $variant->discount_percent : ($product->discount_percent ?? 0);
        $variantLabel = $variant?->getLabel();

        if (Auth::check()) {
            $item = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->first();

            if ($item) {
                $item->increment('qty', $qty);
            } else {
                CartItem::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'store_id'   => $storeId,
                    'qty'        => $qty,
                    'is_selected'=> true,
                ]);
            }
        } else {
            $cart = session('cart', []);
            $key  = $this->makeKey($productId, $variantId);

            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $qty;
            } else {
                $cart[$key] = [
                    'product_id'       => $productId,
                    'variant_id'       => $variantId,
                    'variant_label'    => $variantLabel,
                    'all_variants'     => [],   // session cart tidak simpan all_variants (diload saat login)
                    'discount_pct'     => $discPct,
                    'discount_percent' => $discPct,
                    'store_id'         => $storeId,
                    'store_name'       => $storeName,
                    'store_slug'       => $storeSlug,
                    'name'             => $product->name,
                    'price'            => $finalPrice,
                    'original_price'   => $origPrice,
                    'image'            => $product->image,
                    'qty'              => $qty,
                    'stock'            => $stock,
                    'is_active'        => $product->is_active,
                    'store_is_active'  => $product->store?->status === 'active',
                ];
            }

            session(['cart' => $cart]);
        }
    }

    public function updateByKey(string $key, int $qty): void
    {
        [$productId, $variantId] = $this->parseKey($key);

        if (Auth::check()) {
            $q = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId);
            $qty <= 0 ? $q->delete() : $q->update(['qty' => $qty]);
        } else {
            $cart = session('cart', []);
            if ($qty <= 0) unset($cart[$key]);
            elseif (isset($cart[$key])) $cart[$key]['qty'] = $qty;
            session(['cart' => $cart]);
        }
    }

    public function update(int $productId, int $qty): void
    {
        $this->updateByKey((string) $productId, $qty);
    }

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

    public function remove(int $productId): void
    {
        $this->removeByKey((string) $productId);
    }

    public function removeItems(array $keys): void
    {
        foreach ($keys as $key) $this->removeByKey((string) $key);
    }

    public function clear(): void
    {
        if (Auth::check()) CartItem::where('user_id', Auth::id())->delete();
        session()->forget('cart');
    }

    public function total(): int
    {
        return array_sum(array_map(fn($i) => ($i['price'] ?? 0) * $i['qty'], $this->get()));
    }

    public function count(): int
    {
        if (Auth::check()) return CartItem::where('user_id', Auth::id())->sum('qty');
        return array_sum(array_column(session('cart', []), 'qty'));
    }

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
                    'is_selected'=> true,
                ]);
            }
        }

        session()->forget('cart');
    }

    private function parseKey(string $key): array
    {
        $parts = explode('_', $key, 2);
        return [(int) $parts[0], isset($parts[1]) ? (int) $parts[1] : null];
    }
}
