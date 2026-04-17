<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PageView;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $month = request('month');
        $year  = request('year');

        $storeGrowth = Store::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as total')
            ->groupByRaw('DATE_FORMAT(created_at, "%b %Y"), YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->pluck('total', 'month');

        $revenueQuery = Order::whereNull('store_id')->where('status', 'completed');
        if ($month) $revenueQuery->whereMonth('created_at', $month);
        if ($year)  $revenueQuery->whereYear('created_at', $year);

        $revenueChart = $revenueQuery
            ->selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, SUM(total) as total')
            ->groupByRaw('DATE_FORMAT(created_at, "%b %Y"), YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->pluck('total', 'month');

        $lastMonth = Store::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)->count();
        $thisMonth = Store::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $growth = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;

        $trafficData = PageView::selectRaw('viewed_date, COUNT(*) as views, COUNT(DISTINCT ip) as unique_visitors')
            ->where('viewed_date', '>=', now()->subDays(29)->toDateString())
            ->groupBy('viewed_date')
            ->orderBy('viewed_date')
            ->get()
            ->keyBy('viewed_date');

        $trafficLabels  = [];
        $trafficViews   = [];
        $trafficUnique  = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $trafficLabels[] = now()->subDays($i)->format('d M');
            $row = $trafficData->get($date);
            $trafficViews[]  = $row?->views ?? 0;
            $trafficUnique[] = $row?->unique_visitors ?? 0;
        }

        $onlineCount = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->whereNotNull('user_id')
            ->count();

        $topPages = PageView::selectRaw('path, COUNT(*) as views')
            ->where('viewed_date', '>=', now()->subDays(7)->toDateString())
            ->groupBy('path')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        $todayViews   = PageView::whereDate('viewed_date', today())->count();
        $todayUnique  = PageView::whereDate('viewed_date', today())->distinct('ip')->count('ip');

        $stats = [
            'total_products'    => Product::whereNull('store_id')->count(),
            'total_orders'      => Order::whereNull('store_id')->count(),
            'total_users'       => User::where('role', 'user')->count(),
            'total_revenue'     => Order::whereNull('store_id')->where('status', 'completed')->sum('total'),
            'recent_orders'     => Order::whereNull('store_id')->with('items')->latest()->take(5)->get(),
            'pending_orders'    => Order::whereNull('store_id')->where('status', 'pending')->count(),

            'merchant_count'    => Store::where('status', 'active')->count(),
            'pending_stores'    => Store::where('status', 'pending')->count(),
            'merchant_products' => Product::whereNotNull('store_id')->count(),

            'store_growth'           => $storeGrowth,
            'revenue_chart'          => $revenueChart,
            'store_growth_percent'   => $growth,

            'online_count'  => $onlineCount,
            'today_views'   => $todayViews,
            'today_unique'  => $todayUnique,
            'top_pages'     => $topPages,
        ];

        return view('admin.dashboard', compact(
            'stats', 'month', 'year',
            'trafficLabels', 'trafficViews', 'trafficUnique'
        ));
    }

    public function export()
    {
        $month = request('month');
        $year  = request('year');

        $query = Order::whereNull('store_id');
        if ($month) $query->whereMonth('created_at', $month);
        if ($year)  $query->whereYear('created_at', $year);

        $orders = $query->latest()->get();

        $response = new StreamedResponse(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Order','Nama','Total','Status','Tanggal']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_code, $order->name,
                    $order->total, $order->status, $order->created_at,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=orders-admin-'.now()->format('Y-m-d').'.csv');

        return $response;
    }

    public function notificationsCount()
    {
        $unreadNotifs = \App\Models\Notification::forAdmin()->unread()->count();

        return response()->json([
            'pending_orders' => \App\Models\Order::whereNull('store_id')->where('status','pending')->count(),
            'pending_stores' => \App\Models\Store::where('status','pending')->count(),
            'unread_notifs'  => $unreadNotifs,
        ]);
    }
    
}