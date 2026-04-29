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

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $variantId = $request->variant_id ? (int) $request->variant_id : null;
        $qty       = max(1, (int) $request->qty);

        if ($product->hasVariants() && !$variantId) {
            return redirect()->back()->with('error', 'Pilih ukuran produk terlebih dahulu.');
        }

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || $variant->product_id !== $product->id) {
                return redirect()->back()->with('error', 'Variant tidak valid.');
            }
            $cart   = $this->cart->get();
            $key    = "{$product->id}_{$variantId}";
            $inCart = $cart[$key]['qty'] ?? 0;
            if ($variant->stock > 0 && ($inCart + $qty) > $variant->stock) {
                return redirect()->back()->with('error', "Stok tidak mencukupi. Tersisa {$variant->stock}.");
            }
        } else {
            $cart   = $this->cart->get();
            $key    = (string) $product->id;
            $inCart = $cart[$key]['qty'] ?? 0;
            if ($product->stock > 0 && ($inCart + $qty) > $product->stock) {
                return redirect()->back()->with('error', "Stok tidak mencukupi. Tersisa {$product->stock}.");
            }
        }

        $this->cart->add($product->id, $qty, $variantId);

        if ($request->action === 'buy_now') {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', __('app.added_to_cart'));
    }

    public function update(Request $request, $id)
    {
        $key       = (string) $id;
        $qty       = (int) $request->qty;
        $parts     = explode('_', $key, 2);
        $productId = (int) $parts[0];
        $variantId = isset($parts[1]) ? (int) $parts[1] : null;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->stock > 0 && $qty > $variant->stock) {
                $qty = $variant->stock;
            }
        } else {
            $product = Product::find($productId);
            if ($product && $product->stock > 0 && $qty > $product->stock) {
                $qty = $product->stock;
            }
        }

        $this->cart->updateByKey($key, $qty);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'qty' => $qty]);
        }

        return redirect()->route('cart.index');
    }

    /**
     * Ganti variant item di cart (AJAX).
     *
     * Behaviour (seperti Tokopedia/Shopee):
     * - Kalau qty lama <= stok variant baru  → pakai qty lama (tidak ada perubahan)
     * - Kalau qty lama >  stok variant baru  → clamp ke stok baru, beri tahu user
     * - Kalau variant baru stok = 0          → tolak, tidak bisa ganti
     *
     * Request body: { old_key: "15_3", new_variant_id: 5 }
     */
    public function changeVariant(Request $request)
    {
        $request->validate([
            'old_key'        => 'required|string',
            'new_variant_id' => 'required|integer|exists:product_variants,id',
        ]);

        $oldKey       = (string) $request->old_key;
        $newVariantId = (int) $request->new_variant_id;

        // Ambil item lama dari cart
        $cart    = $this->cart->get();
        $oldItem = $cart[$oldKey] ?? null;

        if (!$oldItem) {
            return response()->json(['ok' => false, 'message' => 'Item tidak ditemukan di cart.'], 404);
        }

        $oldQty    = (int) ($oldItem['qty'] ?? 1);
        $productId = (int) $oldItem['product_id'];

        // Validasi variant baru milik produk yang sama
        $newVariant = ProductVariant::find($newVariantId);
        if (!$newVariant || $newVariant->product_id !== $productId) {
            return response()->json(['ok' => false, 'message' => 'Variant tidak valid.'], 422);
        }

        // Tolak kalau variant baru stok habis total
        if ($newVariant->stock === 0) {
            return response()->json([
                'ok'      => false,
                'message' => "{$newVariant->getLabel()} sedang habis stok.",
            ], 422);
        }

        // ── CLAMP: kalau qty lama melebihi stok variant baru, sesuaikan ──
        $newKey   = "{$productId}_{$newVariantId}";
        $inCart   = $cart[$newKey]['qty'] ?? 0; // sudah ada di cart sebelumnya?
        $totalQty = $inCart + $oldQty;

        $finalQty    = $oldQty;   // qty yang akan disimpan
        $wasAdjusted = false;     // apakah ada penyesuaian qty?
        $adjustMsg   = null;

        if ($newVariant->stock > 0 && $totalQty > $newVariant->stock) {
            // Hitung berapa yang masih bisa ditambah ke slot variant baru
            $available = max(0, $newVariant->stock - $inCart);

            if ($available === 0) {
                // Variant baru sudah penuh di cart (dari item lain)
                return response()->json([
                    'ok'      => false,
                    'message' => "{$newVariant->getLabel()} sudah ada di keranjang dan mencapai batas stok ({$newVariant->stock}).",
                ], 422);
            }

            // Clamp ke available stock
            $finalQty    = $available;
            $wasAdjusted = true;
            $adjustMsg   = "Jumlah disesuaikan menjadi {$finalQty} karena stok {$newVariant->getLabel()} hanya tersisa {$newVariant->stock}.";
        }

        // Hapus item lama, tambah/merge item baru dengan qty yang sudah di-clamp
        $this->cart->removeByKey($oldKey);
        $this->cart->add($productId, $finalQty, $newVariantId);

        return response()->json([
            'ok'           => true,
            'new_key'      => $newKey,
            'label'        => $newVariant->getLabel(),
            'price'        => $newVariant->getFinalPrice(),
            'original_price' => $newVariant->hasDiscount() ? $newVariant->price : null,
            'discount'     => $newVariant->hasDiscount() ? $newVariant->discount_percent : 0,
            'stock'        => $newVariant->stock,
            'qty'          => $finalQty,       // qty aktual setelah clamp
            'was_adjusted' => $wasAdjusted,    // flag untuk frontend
            'adjust_msg'   => $adjustMsg,      // pesan jika ada penyesuaian
        ]);
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

        if ($item) {
            $item->update(['is_selected' => $request->boolean('selected')]);
        }

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
}