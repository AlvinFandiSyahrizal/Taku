<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

public function register(Request $request)
{
    $siteKey = config('services.turnstile.site_key', '');
    if (!empty($siteKey)) {
        $turnstileToken = $request->input('cf-turnstile-response');
        if (!$this->verifyTurnstile($turnstileToken, $request->ip())) {
            return back()
                ->withErrors(['cf-turnstile-response' => 'Verifikasi manusia gagal. Coba lagi.'])
                ->withInput();
        }
    }

    $request->validate([
        'name'     => 'required|string|max:100',
        'email'    => 'required|email|unique:users,email',
        'password' => [
            'required', 'min:8', 'confirmed',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ],
    ], [
        'password.min'       => 'Password minimal 8 karakter.',
        'password.regex'     => 'Password harus mengandung huruf besar, angka, dan simbol (@$!%*#?&).',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'user',
    ]);

    $user->sendEmailVerificationNotification();
    Auth::login($user);
    app(\App\Services\CartService::class)->mergeSessionToDb();

    return redirect()->route('verification.notice');
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
