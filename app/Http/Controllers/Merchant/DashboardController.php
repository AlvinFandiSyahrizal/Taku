<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        $storeId = $store->id;

        $stats = [
            'total_products'  => Product::where('store_id', $storeId)->count(),
            'active_products' => Product::where('store_id', $storeId)->where('is_active', true)->count(),
            'total_orders'    => Order::where('store_id', $storeId)->count(),
            'pending_orders'  => Order::where('store_id', $storeId)->where('status', 'pending')->count(),
            'total_revenue'   => Order::where('store_id', $storeId)->where('status', 'completed')->sum('total'),
            'unique_buyers'   => Order::where('store_id', $storeId)->distinct('user_id')->count('user_id'),
        ];

        $revenueMonthly = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, SUM(total) as total')
            ->groupByRaw('DATE_FORMAT(created_at, "%b %Y"), YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->pluck('total', 'month');

        $revenueDaily = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(29))
            ->selectRaw('DATE_FORMAT(created_at, "%d %b") as day, SUM(total) as total')
            ->groupByRaw('DATE_FORMAT(created_at, "%d %b"), DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->pluck('total', 'day');

        $statusColors = [
            'pending'   => '#e67e22',
            'confirmed' => '#2980b9',
            'shipped'   => '#8e44ad',
            'completed' => '#27ae60',
            'cancelled' => '#c0392b',
        ];
        $statusLabels = [
            'pending'   => 'Pending',
            'confirmed' => 'Dikonfirmasi',
            'shipped'   => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        $orderStatusData = Order::where('store_id', $storeId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(fn($o) => [
                'label' => $statusLabels[$o->status] ?? $o->status,
                'count' => $o->count,
                'color' => $statusColors[$o->status] ?? '#888',
            ]);

        $recentOrders = Order::where('store_id', $storeId)
            ->latest()->take(5)->get();

        $topProducts = Product::where('store_id', $storeId)
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)->get();

        return view('merchant.dashboard', compact(
            'store', 'stats',
            'revenueMonthly', 'revenueDaily',
            'orderStatusData', 'recentOrders', 'topProducts'
        ));
    }
}