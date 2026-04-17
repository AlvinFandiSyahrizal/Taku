<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsMerchant
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Verifikasi email kamu terlebih dahulu untuk mengakses dashboard toko.');
        }

        if (!$user->isMerchant()) {
            abort(403);
        }

        return $next($request);
    }
}
