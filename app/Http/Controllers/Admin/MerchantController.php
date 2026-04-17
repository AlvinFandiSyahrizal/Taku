<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class MerchantController extends Controller
{
    public function index()
    {
        $stores = Store::with('user')->withCount('products')->get();

        $activeProducts   = Product::whereNotNull('store_id')->where('is_active', true)->count();
        $inactiveProducts = Product::whereNotNull('store_id')->where('is_active', false)->count();

        $topStores = Store::withCount('products')
            ->where('status', 'active')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        $storeGrowth = Store::selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('DATE_FORMAT(created_at, "%b %Y"), YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->pluck('total', 'month');

        $stats = [
            'total_stores'    => $stores->count(),
            'active_stores'   => $stores->where('status', 'active')->count(),
            'pending_stores'  => $stores->where('status', 'pending')->count(),
            'banned_stores'   => $stores->where('status', 'banned')->count(),
            'total_merchant_products' => Product::whereNotNull('store_id')->count(),
            'active_products'   => $activeProducts,
            'inactive_products' => $inactiveProducts,
        ];

        return view('admin.merchants.index', compact('stats', 'topStores', 'storeGrowth'));
    }

    public function show(Store $store)
    {
        $request = request();
        $month   = $request->get('month');
        $year    = $request->get('year');
        $search  = $request->get('search', '');

        $baseQuery = \App\Models\OrderItem::whereHas('product', fn($q) => $q->where('store_id', $store->id))
            ->whereHas('order', fn($q) => $q->where('status', 'completed'));

        if ($year)  $baseQuery->whereYear('created_at', $year);
        if ($month) $baseQuery->whereMonth('created_at', $month);

        $totalProducts  = $store->products()->count();
        $activeProducts = $store->products()->where('is_active', true)->count();
        $totalOrders    = (clone $baseQuery)->count();
        $totalRevenue   = (clone $baseQuery)->sum('subtotal');

        $topProducts = (clone $baseQuery)
            ->selectRaw('product_id, SUM(qty) as sold')
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->with('product')
            ->take(10)
            ->get();

        $chartType = ($month && $year) ? 'daily' : 'monthly';

        if ($chartType === 'daily') {
            $currentDate  = \Carbon\Carbon::create($year, $month, 1);
            $previousDate = $currentDate->copy()->subMonth();
            $current  = (clone $baseQuery)->whereMonth('created_at', $currentDate->month)->whereYear('created_at', $currentDate->year)
                ->selectRaw('DAY(created_at) as label, SUM(subtotal) as total')->groupBy('label')->orderBy('label')->pluck('total','label');
            $previous = (clone $baseQuery)->whereMonth('created_at', $previousDate->month)->whereYear('created_at', $previousDate->year)
                ->selectRaw('DAY(created_at) as label, SUM(subtotal) as total')->groupBy('label')->orderBy('label')->pluck('total','label');
        } else {
            $current  = (clone $baseQuery)->whereYear('created_at', now()->year)
                ->selectRaw('MONTH(created_at) as label, SUM(subtotal) as total')->groupBy('label')->orderBy('label')->pluck('total','label');
            $previous = (clone $baseQuery)->whereYear('created_at', now()->year - 1)
                ->selectRaw('MONTH(created_at) as label, SUM(subtotal) as total')->groupBy('label')->orderBy('label')->pluck('total','label');
        }

        $labels       = collect(range(1, $chartType === 'daily' ? 31 : 12));
        $currentData  = $labels->map(fn($l) => $current[$l] ?? 0);
        $previousData = $labels->map(fn($l) => $previous[$l] ?? 0);

        $previousRevenue = (clone $baseQuery)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('subtotal');
        $growth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        $productsQuery = $store->products()->latest();
        if ($search) {
            $productsQuery->where('name', 'like', "%{$search}%");
        }
        $products = $productsQuery->paginate(25)->withQueryString();

        $productStats = [
            'active'   => $store->products()->where('is_active', true)->count(),
            'inactive' => $store->products()->where('is_active', false)->count(),
        ];

        return view('admin.merchants.show', compact(
            'store','totalProducts','activeProducts','totalOrders','totalRevenue',
            'topProducts','products','productStats','chartType','growth',
            'labels','currentData','previousData','search'
        ));
    }

    public function export(Store $store)
{
    $month = request('month');
    $year  = request('year');

    $query = \App\Models\OrderItem::whereHas('product', function ($q) use ($store) {
        $q->where('store_id', $store->id);
    })->whereHas('order', function ($q) {
        $q->where('status', 'completed');
    });

    if ($year) {
        $query->whereYear('created_at', $year);
    }

    if ($month) {
        $query->whereMonth('created_at', $month);
    }

    $data = $query->get();

    $filename = 'merchant_'.$store->id.'_orders.csv';

    return response()->streamDownload(function () use ($data) {

        $handle = fopen('php://output', 'w');

        fputcsv($handle, ['Tanggal', 'Produk', 'Qty', 'Harga', 'Subtotal']);

        foreach ($data as $row) {
            fputcsv($handle, [
                $row->created_at,
                $row->product_name,
                $row->qty,
                $row->price,
                $row->subtotal
            ]);
        }

        fclose($handle);

    }, $filename);
}
    
}