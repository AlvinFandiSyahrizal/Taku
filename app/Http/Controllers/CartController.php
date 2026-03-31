<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = array_sum(array_map(function ($item) {
            $price = (int) preg_replace('/[^0-9]/', '', $item['price']);
            return $price * $item['qty'];
        }, $cart));

        return view('pages.Cart', [
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $cart = session('cart', []);
        $id   = $request->product_id;
        $qty  = (int) $request->qty ?? 1;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'name'  => $request->name,
                'price' => $request->price,
                'image' => $request->image,
                'qty'   => $qty,
            ];
        }

        session(['cart' => $cart]);

        if ($request->action === 'buy_now') {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', __('app.added_to_cart'));
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function update(Request $request, $id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $qty = (int) $request->qty;
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['qty'] = $qty;
            }
        }

        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
