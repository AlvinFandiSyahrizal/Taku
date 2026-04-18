<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) return redirect()->route('home');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $siteKey = config('services.turnstile.site_key', '');
        if (!empty($siteKey)) {
            if (!$this->verifyTurnstile($request->input('cf-turnstile-response'), $request->ip())) {
                return back()
                    ->withErrors(['cf-turnstile-response' => 'Verifikasi manusia gagal. Coba lagi.'])
                    ->withInput($request->except('password'));
            }
        }

        $throttleKey = 'login:' . Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
                ])
                ->withInput($request->except('password'));
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey); 

            app(CartService::class)->mergeSessionToDb();

            if (Auth::user()->isAdmin())    return redirect()->route('admin.dashboard');
            if (Auth::user()->isMerchant()) return redirect()->route('merchant.dashboard');

            return redirect()->intended(route('home'));
        }

        RateLimiter::hit($throttleKey, 60); // 60 detik cooldown

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function verifyTurnstile(?string $token, string $ip): bool
    {
        if (empty($token)) return false;
        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->post(
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                [
                    'secret'   => config('services.turnstile.secret_key', ''),
                    'response' => $token,
                    'remoteip' => $ip,
                ]
            );
            return $response->json('success', false);
        } catch (\Exception $e) {
            return false;
        }
    }
}