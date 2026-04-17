<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
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
            ->with(['product', 'product.store'])
            ->get();

        $cart = [];
        foreach ($items as $item) {
            if (!$item->product) continue;
            $cart[(string) $item->product_id] = [
                'product_id'     => $item->product_id,
                'store_id'       => $item->store_id,
                'store_name'     => $item->product->store?->name ?? 'Taku Official',
                'store_slug'     => $item->product->store?->slug ?? null,
                'name'           => $item->product->name,
                'price'          => $item->product->getFinalPrice(),
                'original_price' => $item->product->price,
                'image'          => $item->product->image,
                'qty'            => $item->qty,
                'is_selected'    => (bool) $item->is_selected,
                'stock'          => $item->product->stock ?? 0,
            ];
        }
        return $cart;
    }

    public function getGrouped(): array
    {
        $cart = $this->get();
        $grouped = [];

        foreach ($cart as $productId => $item) {
            $storeKey = $item['store_id'] ?? 'official';

            if (!isset($grouped[$storeKey])) {
                $grouped[$storeKey] = [
                    'store_id'   => $item['store_id'] ?? null,
                    'store_name' => $item['store_name'] ?? 'Taku Official',
                    'store_slug' => $item['store_slug'] ?? null,
                    'items'      => [],
                ];
            }

            $grouped[$storeKey]['items'][(string)$productId] = $item;
        }

        return $grouped;
    }

    private function getFromSession(): array
    {
        return session('cart', []);
    }

    public function add(int $productId, int $qty = 1): void
    {
        $product = Product::with('store')->find($productId);
        if (!$product) return;

        $storeId      = $product->store_id;
        $storeName    = $product->store?->name ?? 'Taku Official';
        $storeSlug    = $product->store?->slug ?? null;
        $finalPrice   = $product->getFinalPrice();
        $originalPrice = $product->price;

        if (Auth::check()) {
            $item = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)->first();
            if ($item) {
                $item->increment('qty', $qty);
            } else {
                CartItem::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $productId,
                    'store_id'   => $storeId,
                    'qty'        => $qty,
                    'is_selected' => true,
                ]);
            }
        } else {
            $cart = session('cart', []);
            $key  = (string) $productId;
            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $qty;
            } else {
                $cart[$key] = [
                    'product_id'     => $productId,
                    'store_id'       => $storeId,
                    'store_name'     => $storeName,
                    'store_slug'     => $storeSlug,
                    'name'           => $product->name,
                    'price'          => $finalPrice,
                    'original_price' => $originalPrice,
                    'image'          => $product->image,
                    'qty'            => $qty,
                ];
            }
            session(['cart' => $cart]);
        }
    }

    public function update(int $productId, int $qty): void
    {
        if (Auth::check()) {
            if ($qty <= 0) {
                CartItem::where('user_id', Auth::id())
                    ->where('product_id', $productId)->delete();
            } else {
                CartItem::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->update(['qty' => $qty]);
            }
        } else {
            $cart = session('cart', []);
            $key  = (string) $productId;
            if ($qty <= 0) {
                unset($cart[$key]);
            } elseif (isset($cart[$key])) {
                $cart[$key]['qty'] = $qty;
            }
            session(['cart' => $cart]);
        }
    }

    public function remove(int $productId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[(string) $productId]);
            session(['cart' => $cart]);
        }
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }
        session()->forget('cart');
    }

    public function removeItems(array $productIds): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->whereIn('product_id', $productIds)
                ->delete();
        } else {
            $cart = session('cart', []);
            foreach ($productIds as $id) {
                unset($cart[(string) $id]);
            }
            session(['cart' => $cart]);
        }
    }

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

    public function mergeSessionToDb(): void
    {
        $sessionCart = session('cart', []);
        if (empty($sessionCart)) return;

        foreach ($sessionCart as $productId => $item) {
            $existing = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)->first();

            if ($existing) {
                $existing->increment('qty', $item['qty']);
            } else {
                CartItem::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $productId,
                    'store_id'   => $item['store_id'] ?? null,
                    'qty'        => $item['qty'],
                ]);
            }
        }

        session()->forget('cart');
    }

}
