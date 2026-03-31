<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = array_sum(array_map(function ($item) {
            $price = (int) preg_replace('/[^0-9]/', '', $item['price']);
            return $price * $item['qty'];
        }, $cart));

        return view('pages.Checkout', [
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'note'    => 'nullable|string|max:300',
        ]);

        $cart  = session('cart', []);
        $total = array_sum(array_map(function ($item) {
            $price = (int) preg_replace('/[^0-9]/', '', $item['price']);
            return $price * $item['qty'];
        }, $cart));

        // Susun pesan WA
        $items = '';
        foreach ($cart as $item) {
            $items .= "- {$item['name']} (x{$item['qty']}) — {$item['price']}\n";
        }

        $totalFormatted = 'Rp ' . number_format($total, 0, ',', '.');
        $note = $request->note ? "\nCatatan: {$request->note}" : '';

        $message =
            "Halo Taku, saya ingin memesan:\n\n" .
            "Nama: {$request->name}\n" .
            "No. WA: {$request->phone}\n" .
            "Alamat: {$request->address}" .
            $note . "\n\n" .
            "Detail Pesanan:\n" .
            $items . "\n" .
            "Total: {$totalFormatted}";

        // Kosongkan cart setelah checkout
        session()->forget('cart');

        $waNumber = config('app.wa_number', '6281324683769');
        $waUrl    = 'https://wa.me/' . $waNumber . '?text=' . urlencode($message);

        return redirect($waUrl);
    }
}
