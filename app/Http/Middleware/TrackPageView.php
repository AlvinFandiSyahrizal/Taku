<?php
namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $path = $request->path();
        $ignoredPrefixes = [
            'admin', 'merchant', '_debugbar', 'sanctum',
            'livewire', 'telescope', 'horizon', '.well-known',
            'favicon',
        ];

        $shouldTrack = $request->isMethod('GET')
            && !$request->ajax()
            && !$request->expectsJson()
            && $response->getStatusCode() < 400; 

        foreach ($ignoredPrefixes as $prefix) {
            if (str_starts_with($path, $prefix) || str_starts_with($path, '.'.$prefix)) {
                $shouldTrack = false;
                break;
            }
        }

        if (str_contains($request->getRequestUri(), '//') || str_contains($path, '.json')) {
            $shouldTrack = false;
        }

        if ($shouldTrack) {
            try {
                PageView::create([
                    'path'        => '/' . $path,
                    'ip'          => $request->ip(),
                    'user_id'     => Auth::id(),
                    'viewed_date' => today(),
                ]);
            } catch (\Exception $e) {}
        }

        return $response;
    }
    
}
