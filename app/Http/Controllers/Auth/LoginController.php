<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

        public function login(Request $request)
        {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

                $request->session()->regenerate();

                app(CartService::class)->mergeSessionToDb();

                if (Auth::user()->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }
                if (Auth::user()->isMerchant()) {
                    return redirect()->route('merchant.dashboard');
                }

                return redirect()->intended(route('home'));
            }

            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
