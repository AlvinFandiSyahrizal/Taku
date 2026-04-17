<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {

        if (!Auth::user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice')
            ->with('error', 'Verifikasi email kamu dulu sebelum checkout.');
    }

        $selected = session('checkout_selected', []);
        $cart     = $this->cart->get();

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $checkoutCart = empty($selected)
            ? $cart
            : array_filter($cart, fn($item, $id) => in_array($id, $selected), ARRAY_FILTER_USE_BOTH);

        if (empty($checkoutCart)) {
            return redirect()->route('cart.index')->with('error', 'Pilih produk yang ingin di-checkout.');
        }

        $grouped = [];
        foreach ($checkoutCart as $id => $item) {
            $storeKey = $item['store_id'] ?? 'official';
            if (!isset($grouped[$storeKey])) {
                $grouped[$storeKey] = [
                    'store_id'   => $item['store_id'] ?? null,
                    'store_name' => $item['store_name'] ?? 'Taku Official',
                    'items'      => [],
                    'subtotal'   => 0,
                ];
            }
            $grouped[$storeKey]['items'][$id] = $item;
            $grouped[$storeKey]['subtotal'] += $item['price'] * $item['qty'];
        }

        $total = array_sum(array_column($grouped, 'subtotal'));

        return view('pages.Checkout', compact('grouped', 'total', 'checkoutCart'));
    }

    public function select(Request $request)
    {
        $selected = $request->input('selected', []);
        session(['checkout_selected' => array_map('strval', $selected)]);
        return redirect()->route('checkout.index');
    }

    public function process(Request $request)
    {

        if (!Auth::user()->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'note'    => 'nullable|string|max:300',
        ]);

        $selected = session('checkout_selected', []);
        $cart     = $this->cart->get();

        $checkoutCart = empty($selected)
            ? $cart
            : array_filter($cart, fn($item, $id) => in_array($id, $selected), ARRAY_FILTER_USE_BOTH);

        if (empty($checkoutCart)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang dipilih.');
        }

        $grouped = [];
        foreach ($checkoutCart as $id => $item) {
            $storeKey = $item['store_id'] ?? 'official';
            $grouped[$storeKey]['store_id']   = $item['store_id'] ?? null;
            $grouped[$storeKey]['store_name'] = $item['store_name'] ?? 'Taku Official';
            $grouped[$storeKey]['items'][$id] = $item;
        }

        $orders  = [];
        $waUrls  = [];
        $waNumber = config('app.wa_number', '6281324683769');

        foreach ($grouped as $group) {
            $groupTotal = array_sum(array_map(
                fn($i) => $i['price'] * $i['qty'],
                $group['items']
            ));

            // Buat order
            $order = Order::create([
                'user_id'    => Auth::id(),
                'store_id'   => $group['store_id'],
                'order_code' => Order::generateCode(),
                'name'       => $request->name,
                'phone'      => $request->phone,
                'address'    => $request->address,
                'note'       => $request->note,
                'total'      => $groupTotal,
                'status'     => 'pending',
            ]);

            foreach ($group['items'] as $productId => $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => is_numeric($productId) ? (int)$productId : null,
                    'product_name'  => $item['name'],
                    'product_image' => $item['image'] ?? null,
                    'price'         => (int) $item['price'],
                    'qty'           => (int) $item['qty'],
                    'subtotal'      => (int) $item['price'] * (int) $item['qty'],
                ]);

                if (is_numeric($productId)) {
                    Product::where('id', (int) $productId)
                        ->where('stock', '>', 0)
                        ->decrement('stock', (int) $item['qty']);
                }
            }

           OrderPlaced::dispatch($order);

            $orders[] = $order;

            $itemLines = '';
            foreach ($group['items'] as $item) {
                $itemLines .= "- {$item['name']} (x{$item['qty']}) — Rp "
                    . number_format($item['price'], 0, ',', '.') . "\n";
            }

            $message =
                "Halo {$group['store_name']}, saya ingin memesan:\n\n"
                . "Kode Pesanan: {$order->order_code}\n"
                . "Nama: {$request->name}\n"
                . "No. WA: {$request->phone}\n"
                . "Alamat: {$request->address}"
                . ($request->note ? "\nCatatan: {$request->note}" : '')
                . "\n\nDetail Pesanan:\n{$itemLines}\n"
                . "Total: Rp " . number_format($groupTotal, 0, ',', '.');

            $storePhone = $group['store_id']
                ? (\App\Models\Store::find($group['store_id'])?->phone ?? $waNumber)
                : $waNumber;

            $cleanPhone = preg_replace('/[^0-9]/', '', $storePhone);
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }

            $waUrls[] = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);
        }

        $this->cart->removeItems(array_keys($checkoutCart));
        session()->forget('checkout_selected');
        session(['pending_wa_urls' => $waUrls]);
        session(['last_orders' => array_map(fn($o) => $o->order_code, $orders)]);

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        $waUrls     = session('pending_wa_urls', []);
        $orderCodes = session('last_orders', []);
        session()->forget(['pending_wa_urls', 'last_orders']);
        return view('pages.checkout-success', compact('waUrls', 'orderCodes'));
    }
}