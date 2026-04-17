<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TrafficController extends Controller
{
    private array $ignoredPaths = [
        '/.well-known',
        '/favicon',
        '/_debugbar',
        '/sanctum',
        '/livewire',
        '/telescope',
        '/horizon',
    ];

    public function index(Request $request)
    {
        $from = $request->get('from', now()->subDays(29)->toDateString());
        $to   = $request->get('to', today()->toDateString());

        if ($from > $to) $from = $to;

        $query = PageView::whereBetween('viewed_date', [$from, $to]);

        foreach ($this->ignoredPaths as $path) {
            $query->where('path', 'not like', $path.'%');
        }

        $dailyTraffic = $query->clone()
            ->selectRaw('viewed_date, COUNT(*) as views, COUNT(DISTINCT ip) as unique_visitors')
            ->groupBy('viewed_date')
            ->orderBy('viewed_date')
            ->get()
            ->keyBy('viewed_date');

        $labels  = [];
        $views   = [];
        $unique  = [];
        $current = \Carbon\Carbon::parse($from);
        $end     = \Carbon\Carbon::parse($to);
        while ($current <= $end) {
            $d = $current->toDateString();
            $labels[] = $current->format('d M');
            $row = $dailyTraffic->get($d);
            $views[]  = $row?->views ?? 0;
            $unique[] = $row?->unique_visitors ?? 0;
            $current->addDay();
        }

        $topPages = PageView::whereBetween('viewed_date', [$from, $to]);
        foreach ($this->ignoredPaths as $path) {
            $topPages->where('path', 'not like', $path.'%');
        }
        $topPages = $topPages
            ->selectRaw('path, COUNT(*) as views, COUNT(DISTINCT ip) as unique_visitors')
            ->groupBy('path')
            ->orderByDesc('views')
            ->paginate(20);

        $totalViews   = array_sum($views);
        $totalUnique  = PageView::whereBetween('viewed_date', [$from, $to])
            ->where(fn($q) => $this->applyFilter($q))
            ->distinct('ip')->count('ip');
        $avgPerDay    = count($views) > 0 ? round($totalViews / count($views)) : 0;

        $peakDay = $dailyTraffic->sortByDesc('views')->first();

        return view('admin.traffic.index', compact(
            'labels','views','unique',
            'topPages','from','to',
            'totalViews','totalUnique','avgPerDay','peakDay'
        ));
    }

    public function export(Request $request)
    {
        $from = $request->get('from', now()->subDays(29)->toDateString());
        $to   = $request->get('to', today()->toDateString());

        $data = PageView::whereBetween('viewed_date', [$from, $to])
            ->selectRaw('path, viewed_date, COUNT(*) as views, COUNT(DISTINCT ip) as unique_visitors')
            ->groupBy('path', 'viewed_date')
            ->orderBy('viewed_date')
            ->get();

        $response = new StreamedResponse(function () use ($data) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Halaman','Tanggal','Views','Unik']);
            foreach ($data as $row) {
                fputcsv($h, [$row->path, $row->viewed_date, $row->views, $row->unique_visitors]);
            }
            fclose($h);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=traffic-'.$from.'-'.$to.'.csv');
        return $response;
    }

    private function applyFilter($q)
    {
        foreach ($this->ignoredPaths as $path) {
            $q->where('path', 'not like', $path.'%');
        }
        return $q;
    }
}