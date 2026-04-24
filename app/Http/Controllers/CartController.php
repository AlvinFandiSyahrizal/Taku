<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $grouped     = $this->cart->getGrouped();
        $unavailable = $this->cart->getUnavailable();
        $total       = $this->cart->total();
        return view('pages.Cart', compact('grouped', 'unavailable', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::with('variants')->find($request->product_id);
        if (!$product) return redirect()->back()->with('error', 'Produk tidak ditemukan.');

        $variantId = $request->variant_id ? (int) $request->variant_id : null;
        $qty       = max(1, (int) $request->qty);

        if ($product->hasVariants() && !$variantId) {
            return redirect()->back()->with('error', 'Pilih ukuran produk terlebih dahulu.');
        }

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || $variant->product_id !== $product->id)
                return redirect()->back()->with('error', 'Variant tidak valid.');

            $cart   = $this->cart->get();
            $key    = "{$product->id}_{$variantId}";
            $inCart = $cart[$key]['qty'] ?? 0;
            if ($variant->stock > 0 && ($inCart + $qty) > $variant->stock)
                return redirect()->back()->with('error', "Stok tidak mencukupi. Tersisa {$variant->stock}.");
        } else {
            $cart   = $this->cart->get();
            $key    = (string) $product->id;
            $inCart = $cart[$key]['qty'] ?? 0;
            if ($product->stock > 0 && ($inCart + $qty) > $product->stock)
                return redirect()->back()->with('error', "Stok tidak mencukupi. Tersisa {$product->stock}.");
        }

        $this->cart->add($product->id, $qty, $variantId);

        if ($request->action === 'buy_now') return redirect()->route('cart.index');
        return redirect()->back()->with('success', __('app.added_to_cart'));
    }

    public function update(Request $request, $id)
    {
        $key = (string) $id;
        $qty = (int) $request->qty;

        $parts     = explode('_', $key, 2);
        $productId = (int) $parts[0];
        $variantId = isset($parts[1]) ? (int) $parts[1] : null;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->stock > 0 && $qty > $variant->stock) $qty = $variant->stock;
        } else {
            $product = Product::find($productId);
            if ($product && $product->stock > 0 && $qty > $product->stock) $qty = $product->stock;
        }

        $this->cart->updateByKey($key, $qty);

        if ($request->ajax() || $request->wantsJson())
            return response()->json(['ok' => true, 'qty' => $qty]);
        return redirect()->route('cart.index');
    }

    public function remove($id)
    {
        $this->cart->removeByKey((string) $id);
        return redirect()->route('cart.index');
    }

    public function clear()
    {
        $this->cart->clear();
        return redirect()->route('cart.index');
    }

    public function select(Request $request, $id)
    {
        $parts     = explode('_', (string) $id, 2);
        $productId = (int) $parts[0];
        $variantId = isset($parts[1]) ? (int) $parts[1] : null;

        $item = \App\Models\CartItem::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($item) $item->update(['is_selected' => $request->boolean('selected')]);
        return response()->json(['ok' => true]);
    }

    public function selectAll(Request $request)
    {
        \App\Models\CartItem::where('user_id', auth()->id())
            ->update(['is_selected' => $request->boolean('selected')]);
        return response()->json(['ok' => true]);
    }

    public function removeSelected(Request $request)
    {
        $keys = $request->input('ids', []);
        foreach ($keys as $key) {
            $parts     = explode('_', (string) $key, 2);
            $productId = (int) $parts[0];
            $variantId = isset($parts[1]) ? (int) $parts[1] : null;
            \App\Models\CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->delete();
        }
        return redirect()->route('cart.index')->with('success', 'Produk terpilih dihapus.');
    }

    /**
     * AJAX — Ganti variant item di cart.
     * Dipanggil dari dropdown variant di halaman cart.
     * Menghapus item lama (oldKey) dan membuat/merge item baru (newVariantId).
     */
    public function changeVariant(Request $request)
    {
        $request->validate([
            'old_key'       => 'required|string',
            'new_variant_id'=> 'required|integer',
        ]);

        $oldKey       = $request->old_key;
        $newVariantId = (int) $request->new_variant_id;

        // Parse old key
        $parts     = explode('_', $oldKey, 2);
        $productId = (int) $parts[0];

        // Cek variant baru valid
        $newVariant = ProductVariant::where('id', $newVariantId)
            ->where('product_id', $productId)
            ->first();

        if (!$newVariant) {
            return response()->json(['ok' => false, 'message' => 'Variant tidak valid.'], 422);
        }

        if ($newVariant->stock === 0) {
            return response()->json(['ok' => false, 'message' => 'Stok ukuran ini habis.'], 422);
        }

        // Ambil qty lama
        $cart   = $this->cart->get();
        $oldQty = $cart[$oldKey]['qty'] ?? 1;

        // Hapus item lama
        $this->cart->removeByKey($oldKey);

        // Tambah/merge ke variant baru
        $finalQty = $newVariant->stock > 0 ? min($oldQty, $newVariant->stock) : $oldQty;
        $this->cart->add($productId, $finalQty, $newVariantId);

        $newKey = "{$productId}_{$newVariantId}";

        return response()->json([
            'ok'          => true,
            'new_key'     => $newKey,
            'new_label'   => $newVariant->getLabel(),
            'final_price' => $newVariant->getFinalPrice(),
            'orig_price'  => $newVariant->price,
            'has_discount'=> $newVariant->hasDiscount(),
            'discount_pct'=> $newVariant->discount_percent,
            'stock'       => $newVariant->stock,
            'qty'         => $finalQty,
        ]);
    }
}
