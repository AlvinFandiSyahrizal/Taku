<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user          = Auth::user();
        $officialPhone = Setting::get('official_store_phone', '');
        $officialName  = Setting::get('official_store_name', 'Taku Official');
        $officialDesc  = Setting::get('official_store_desc', '');
        return view('admin.settings', compact('user', 'officialPhone', 'officialName', 'officialDesc'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->input('tab', 'profile');

        if ($tab === 'profile') {
            $request->validate([
                'name'         => 'required|string|max:100',
                'profile_phone'=> 'nullable|string|max:25',
            ]);

            $user->update([
                'name'  => $request->name,
                'phone' => $request->profile_phone ?: null,   
            ]);

            return back()->with('success', 'Profil berhasil diupdate.');
        }


        if ($tab === 'store') {
            $request->validate([
                'official_phone' => 'nullable|string|max:25',
                'official_name'  => 'nullable|string|max:100',
                'official_desc'  => 'nullable|string|max:300',
            ]);

            Setting::set('official_store_phone', $request->official_phone ?? '');
            Setting::set('official_store_name',  $request->official_name  ?? 'Taku Official');
            Setting::set('official_store_desc',  $request->official_desc  ?? '');

            return back()->with('success', 'Info toko official diupdate.');
        }

        // ── Tab Password ─────────────────────────────────────────────────
        if ($tab === 'password') {
            $request->validate([
                'current_password' => 'required',
                'password'         => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }

            $user->update(['password' => Hash::make($request->password)]);
            return back()->with('success', 'Password berhasil diubah.');
        }
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:1024']);
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete(str_replace('storage/', '', $user->avatar));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => 'storage/' . $path]);

        return back()->with('success', 'Foto profil diupdate.');
    }
}
