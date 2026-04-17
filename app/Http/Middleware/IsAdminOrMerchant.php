<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminOrMerchant
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isMerchant())) {
            abort(403);
        }

        return $next($request);
    }
}