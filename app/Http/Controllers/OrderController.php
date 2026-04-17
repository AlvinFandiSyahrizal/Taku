<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Order::where('user_id', Auth::id())->with('items')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->get();

        $counts = [
            'all'       => Order::where('user_id', Auth::id())->count(),
            'pending'   => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Order::where('user_id', Auth::id())->where('status', 'confirmed')->count(),
            'shipped'   => Order::where('user_id', Auth::id())->where('status', 'shipped')->count(),
            'completed' => Order::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'cancelled' => Order::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('pages.orders', compact('orders', 'status', 'counts'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');
        return view('pages.order-detail', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses.');
        }

        // Kembalikan stok
        $order->load('items');
        foreach ($order->items as $item) {
            if ($item->product_id) {
                \App\Models\Product::where('id', $item->product_id)
                    ->increment('stock', $item->qty);
            }
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
