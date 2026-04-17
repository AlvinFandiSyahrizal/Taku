<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private function store() { return Auth::user()->store; }

    public function index(Request $request)
    {
        $store   = $this->store();
        $storeId = $store->id;

        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $orders = Order::where('store_id', $storeId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->with('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalOrders  = Order::where('store_id', $storeId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->count();

        $totalRevenue = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('total');

        $byStatus = Order::where('store_id', $storeId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $dailyRevenue = Order::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revLabels = $dailyRevenue->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $revData   = $dailyRevenue->pluck('total')->toArray();

        $topProducts = \App\Models\OrderItem::whereHas('order', fn($q) =>
                $q->where('store_id', $storeId)
                  ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            )
            ->selectRaw('product_id, product_name, SUM(qty) as total_sold, SUM(subtotal) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $lowStock = Product::where('store_id', $storeId)
            ->where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->get();

        return view('merchant.reports.index', compact(
            'store', 'orders', 'from', 'to',
            'totalOrders', 'totalRevenue', 'byStatus',
            'revLabels', 'revData', 'topProducts', 'lowStock'
        ));
    }

    public function export(Request $request)
    {
        $store  = $this->store();
        $from   = $request->get('from', now()->startOfMonth()->toDateString());
        $to     = $request->get('to', now()->toDateString());

        $orders = Order::where('store_id', $store->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->latest()->get();

        $response = new StreamedResponse(function() use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Order','Nama Pembeli','No HP','Total','Status','Tanggal']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_code, $order->name, $order->phone,
                    $order->total, $order->status,
                    $order->created_at->format('d M Y H:i'),
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition',
            'attachment; filename=laporan-'.$store->slug.'-'.$from.'-sd-'.$to.'.csv');
        return $response;
    }
}
