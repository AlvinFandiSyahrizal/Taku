<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = session('lang', 'id');

        if (in_array($lang, ['id', 'en'])) {
            App::setLocale($lang);
        }

        return $next($request);
    }
}
