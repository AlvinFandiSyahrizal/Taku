<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $orders = Order::whereNull('store_id')
            ->where('status', $status)
            ->with('items')
            ->latest()
            ->get();

        $counts = [
            'pending'   => Order::whereNull('store_id')->where('status', 'pending')->count(),
            'confirmed' => Order::whereNull('store_id')->where('status', 'confirmed')->count(),
            'shipped'   => Order::whereNull('store_id')->where('status', 'shipped')->count(),
            'completed' => Order::whereNull('store_id')->where('status', 'completed')->count(),
            'cancelled' => Order::whereNull('store_id')->where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders','status','counts'));
    }

    public function show(Order $order)
    {
        if ($order->store_id !== null) {
            abort(403); 
        }

        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->store_id !== null) abort(403);

        $request->validate([
            'status' => 'required|in:confirmed,shipped,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $order->load('items');
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    \App\Models\Product::where('id', $item->product_id)
                        ->increment('stock', $item->qty);
                }
            }
        }

        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            $order->load('items');
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    \App\Models\Product::where('id', $item->product_id)
                        ->where('stock', '>', 0)
                        ->decrement('stock', $item->qty);
                }
            }
        }

        $order->update(['status' => $newStatus]);
        return back()->with('success', 'Status berhasil diupdate.');
    }

}
