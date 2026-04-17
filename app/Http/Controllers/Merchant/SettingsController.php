<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $store = $user->store;
        return view('merchant.settings', compact('user', 'store'));
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $store = $user->store;
        $tab   = $request->input('tab', 'profile');

        if ($tab === 'profile') {
            $request->validate([
                'name'  => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
            ]);
            $user->update($request->only('name', 'email', 'phone'));
            return back()->with('success', 'Profil berhasil diupdate.')->with('active_tab', 'profile');
        }

        if ($tab === 'store') {
            $request->validate([
                'store_name'        => 'required|string|max:100',
                'store_description' => 'nullable|string|max:500',
                'store_phone'       => 'nullable|string|max:20',
            ]);
            $store->update([
                'name'        => $request->store_name,
                'description' => $request->store_description,
                'phone'       => $request->store_phone, 
            ]);
            return back()->with('success', 'Info toko berhasil diupdate.')->with('active_tab', 'store');
        }

        if ($tab === 'password') {
            $request->validate([
                'current_password' => 'required',
                'password'         => 'required|min:6|confirmed',
            ]);
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password saat ini salah.'])
                    ->with('active_tab', 'password');
            }
            $user->update(['password' => Hash::make($request->password)]);
            return back()->with('success', 'Password berhasil diubah.')->with('active_tab', 'password');
        }
    }

    public function updateLogo(Request $request)
    {
        $request->validate(['logo' => 'required|image|max:1024']);
        $store = Auth::user()->store;

        if ($store->logo) {
            Storage::disk('public')->delete(str_replace('storage/', '', $store->logo));
        }

        $path = $request->file('logo')->store('stores', 'public');
        $store->update(['logo' => 'storage/' . $path]);

        return back()->with('success', 'Logo toko diupdate.')->with('active_tab', 'store');
    }
}