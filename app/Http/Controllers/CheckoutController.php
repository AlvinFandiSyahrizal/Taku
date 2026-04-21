<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\UserAddress;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return redirect()
                ->route('verification.notice')
                ->with('error', 'Verifikasi email kamu dulu sebelum checkout.');
        }

        $selected = session('checkout_selected', []);
        $cart     = $this->cart->get();

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $checkoutCart = empty($selected)
            ? $cart
            : array_filter(
                $cart,
                fn ($item, $id) => in_array((string) $id, $selected),
                ARRAY_FILTER_USE_BOTH
            );

        if (empty($checkoutCart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Pilih produk yang ingin di-checkout.');
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
            $grouped[$storeKey]['subtotal'] += ((int) $item['price'] * (int) $item['qty']);
        }

        $total = array_sum(array_column($grouped, 'subtotal'));

        return view('pages.Checkout', compact('grouped', 'total', 'checkoutCart'));
    }

    public function select(Request $request)
    {
        $selected = array_map('strval', $request->input('selected', []));
        session(['checkout_selected' => $selected]);

        return redirect()->route('checkout.index');
    }

    public function process(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $request->validate([
            'name'        => 'required|string|max:100',
            'phone'       => 'required|string|max:30',
            'address'     => 'required|string|max:500',
            'note'        => 'nullable|string|max:300',
            'use_address' => 'nullable',
        ]);

        $selected = session('checkout_selected', []);
        $cart     = $this->cart->get();

        $checkoutCart = empty($selected)
            ? $cart
            : array_filter(
                $cart,
                fn ($item, $id) => in_array((string) $id, $selected),
                ARRAY_FILTER_USE_BOTH
            );

        if (empty($checkoutCart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Tidak ada produk yang dipilih.');
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | VALIDASI PRODUK
            |--------------------------------------------------------------------------
            */
            foreach ($checkoutCart as $productId => $item) {
                if (!is_numeric($productId)) {
                    continue;
                }

                $product = Product::with('store')->find((int) $productId);

                if (!$product) {
                    throw new \Exception("Produk {$item['name']} tidak ditemukan.");
                }

                if (!$product->is_active) {
                    throw new \Exception("Produk {$product->name} sedang nonaktif.");
                }

                if ($product->store && $product->store->status !== 'active') {
                    throw new \Exception("Toko {$product->store->name} sedang nonaktif.");
                }

                if ($product->stock < (int) $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }
            }

            /*
            |--------------------------------------------------------------------------
            | GROUP PER TOKO
            |--------------------------------------------------------------------------
            */
            $grouped = [];

            foreach ($checkoutCart as $id => $item) {
                $storeKey = $item['store_id'] ?? 'official';

                $grouped[$storeKey]['store_id']   = $item['store_id'] ?? null;
                $grouped[$storeKey]['store_name'] = $item['store_name'] ?? 'Taku Official';
                $grouped[$storeKey]['items'][$id] = $item;
            }

            $orders   = [];
            $waUrls   = [];
            $waNumber = config('app.wa_number', '6281324683769');

            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER PER TOKO
            |--------------------------------------------------------------------------
            */
            foreach ($grouped as $group) {
                $groupTotal = array_sum(array_map(
                    fn ($i) => ((int) $i['price'] * (int) $i['qty']),
                    $group['items']
                ));

                $order = Order::create([
                    'user_id'    => $user->id,
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
                        'product_id'    => is_numeric($productId) ? (int) $productId : null,
                        'product_name'  => $item['name'],
                        'product_image' => $item['image'] ?? null,
                        'price'         => (int) $item['price'],
                        'qty'           => (int) $item['qty'],
                        'subtotal'      => ((int) $item['price'] * (int) $item['qty']),
                    ]);

                    if (is_numeric($productId)) {
                        Product::where('id', (int) $productId)
                            ->decrement('stock', (int) $item['qty']);
                    }
                }

                OrderPlaced::dispatch($order);

                $orders[] = $order;

                /*
                |--------------------------------------------------------------------------
                | WHATSAPP MESSAGE
                |--------------------------------------------------------------------------
                */
                $itemLines = '';

                foreach ($group['items'] as $item) {
                    $itemLines .= "- {$item['name']} (x{$item['qty']}) — Rp "
                        . number_format($item['price'], 0, ',', '.')
                        . "\n";
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
                    ? (Store::find($group['store_id'])?->phone ?? $waNumber)
                    : $waNumber;

                $cleanPhone = preg_replace('/[^0-9]/', '', $storePhone);

                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }

                $waUrls[] = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE NEW ADDRESS
            |--------------------------------------------------------------------------
            */
            if (
                $request->boolean('save_new_address') &&
                $request->get('use_address') === 'new'
            ) {
                $exists = UserAddress::where('user_id', $user->id)
                    ->where('address', $request->address)
                    ->first();

                if (!$exists) {
                    UserAddress::create([
                        'user_id'     => $user->id,
                        'label'       => 'Rumah',
                        'recipient'   => $request->name,
                        'phone'       => $request->phone,
                        'address'     => $request->address,
                        'city'        => $request->get('regency_name', ''),
                        'province'    => $request->get('province_name', ''),
                        'district'    => $request->get('district_name', ''),
                        'village'     => $request->get('village_name', ''),
                        'postal_code' => $request->get('postal_code', ''),
                        'is_default'  => $user->addresses()->count() === 0,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CLEAR CART
            |--------------------------------------------------------------------------
            */
            $this->cart->removeItems(array_keys($checkoutCart));
            session()->forget('checkout_selected');

            session([
                'pending_wa_urls' => $waUrls,
                'last_orders'     => array_map(fn ($o) => $o->order_code, $orders),
            ]);

            DB::commit();

            return redirect()->route('checkout.success');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function success()
    {
        $waUrls     = session('pending_wa_urls', []);
        $orderCodes = session('last_orders', []);

        session()->forget([
            'pending_wa_urls',
            'last_orders',
        ]);

        return view('pages.checkout-success', compact('waUrls', 'orderCodes'));
    }
}