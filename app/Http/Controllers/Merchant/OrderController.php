<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function myStore()
    {
        return Auth::user()->store;
    }

public function index(Request $request)
{
    $store  = $this->myStore();
    $status = $request->get('status', 'pending');
    $search = $request->get('q', '');

    $query = Order::where('store_id', $store->id)
        ->where('status', $status)
        ->with('items')
        ->latest();

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('order_code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    $orders = $query->paginate(10)->withQueryString();

    $counts = [
        'pending'   => Order::where('store_id', $store->id)->where('status', 'pending')->count(),
        'confirmed' => Order::where('store_id', $store->id)->where('status', 'confirmed')->count(),
        'shipped'   => Order::where('store_id', $store->id)->where('status', 'shipped')->count(),
        'completed' => Order::where('store_id', $store->id)->where('status', 'completed')->count(),
        'cancelled' => Order::where('store_id', $store->id)->where('status', 'cancelled')->count(),
    ];

    return view('merchant.orders.index', compact('orders', 'status', 'counts', 'store', 'search'));
}

    public function show(Order $order)
    {
        if ($order->store_id !== $this->myStore()->id) {
            abort(403);
        }

        $order->load('items');
        return view('merchant.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->store_id !== $this->myStore()->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,shipped,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $order->load('items');
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->qty);
                }
            }
        }

       
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            $order->load('items');
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)
                        ->where('stock', '>', 0)
                        ->decrement('stock', $item->qty);
                }
            }
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', 'Status pesanan berhasil diupdate.');
    }

    public function export()
    {
        $store  = Auth::user()->store;
        $orders = Order::where('store_id', $store->id)->latest()->get();

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Order', 'Nama Pembeli', 'Total', 'Status', 'Tanggal']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_code,
                    $order->name,
                    $order->total,
                    $order->status,
                    $order->created_at->format('d M Y H:i'),
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition',
            'attachment; filename=pesanan-' . $store->slug . '-' . now()->format('Y-m-d') . '.csv');

        return $response;
    }
}
