<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from   = $request->get('from', now()->subDays(29)->toDateString());
        $to     = $request->get('to', today()->toDateString());
        $search = $request->get('search', '');
        if ($from > $to) $from = $to;

        $ordersQuery = Order::whereNull('store_id')
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);

        $totalRevenue = $ordersQuery->clone()->where('status','completed')->sum('total');
        $totalOrders  = $ordersQuery->clone()->count();
        $byStatus     = $ordersQuery->clone()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count','status');

        $dailyRevenue = Order::whereNull('store_id')
            ->where('status','completed')
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')->orderBy('date')->get()->keyBy('date');

        $revLabels = []; $revData = [];
        $cur = \Carbon\Carbon::parse($from);
        $end = \Carbon\Carbon::parse($to);
        while ($cur <= $end) {
            $d = $cur->toDateString();
            $revLabels[] = $cur->format('d M');
            $revData[]   = $dailyRevenue->get($d)?->total ?? 0;
            $cur->addDay();
        }

        $topProducts = OrderItem::whereHas('order', fn($q) =>
                $q->whereNull('store_id')
                ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            )
            ->selectRaw('product_name, SUM(qty) as total_sold, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')->orderByDesc('total_sold')->take(10)->get();

        $newUsers = User::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->count();

        $detailQuery = Order::whereNull('store_id')
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->with('items');

        if ($search) {
            $detailQuery->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhereHas('items', fn($qi) => $qi->where('product_name','like',"%{$search}%"));
            });
        }

        $orders = $detailQuery->latest()->paginate(10)->withQueryString();

        return view('admin.reports.index', compact(
            'from','to','search','orders','totalRevenue','totalOrders',
            'byStatus','revLabels','revData','topProducts','newUsers'
        ));
    }


    public function export(Request $request)
    {
        $from = $request->get('from', now()->subDays(29)->toDateString());
        $to   = $request->get('to', today()->toDateString());

        $orders = Order::whereNull('store_id')
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->with('items')
            ->latest()
            ->get();

        $response = new StreamedResponse(function () use ($orders) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Kode','Nama','HP','Alamat','Total','Status','Produk','Tanggal']);
            foreach ($orders as $o) {
                $items = $o->items->map(fn($i) => $i->product_name.'(x'.$i->qty.')')->implode(', ');
                fputcsv($h, [
                    $o->order_code, $o->name, $o->phone, $o->address,
                    $o->total, $o->status, $items, $o->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($h);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=laporan-'.$from.'-'.$to.'.csv');
        return $response;
    }
}
