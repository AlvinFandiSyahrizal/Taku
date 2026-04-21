<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $grouped     = $this->cart->getGrouped();
        $unavailable = $this->cart->getUnavailable();
        $total   = $this->cart->total();

        return view('pages.Cart', compact('grouped','unavailable', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $cart    = $this->cart->get();
        $id      = (string) $product->id;
        $qty     = max(1, (int) $request->qty);
        $inCart  = $cart[$id]['qty'] ?? 0;

        if ($product->stock > 0 && ($inCart + $qty) > $product->stock) {
            return redirect()->back()->with('error', "Stok tidak mencukupi. Tersisa {$product->stock}.");
        }

        $this->cart->add((int) $product->id, $qty);

        if ($request->action === 'buy_now') {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', __('app.added_to_cart'));
    }

    public function update(Request $request, $id)
    {
        $qty     = (int) $request->qty;
        $product = Product::find($id);

        if ($product && $product->stock > 0 && $qty > $product->stock) {
            $qty = $product->stock;
        }

        $this->cart->update((int) $id, $qty);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'qty' => $qty]);
        }

        return redirect()->route('cart.index');
    }

    public function remove($id)
    {
        $this->cart->remove((int) $id);
        return redirect()->route('cart.index');
    }


    public function clear()
    {
        $this->cart->clear();
        return redirect()->route('cart.index');
    }

    public function select(Request $request, $id)
    {
        $item = \App\Models\CartItem::where('user_id', auth()->id())
            ->where('product_id', $id)->first();

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
        $ids = $request->input('ids', []);
        \App\Models\CartItem::where('user_id', auth()->id())
            ->whereIn('product_id', $ids)->delete();

        return redirect()->route('cart.index')->with('success', 'Produk terpilih dihapus.');
    }



}
