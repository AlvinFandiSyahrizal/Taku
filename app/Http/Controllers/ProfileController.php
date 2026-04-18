<?php
namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $addresses = $user->addresses()->latest()->get();
        return view('pages.profile', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20|regex:/^\+[0-9]{7,15}$/',
            'bio'   => 'nullable|string|max:300',
        ], [
            'phone.regex' => 'Format nomor tidak valid. Gunakan format internasional (+62xxx).',
        ]);

        $user->update($request->only('name', 'phone', 'bio'));
        return back()->with('success', 'Profil berhasil diupdate.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        Auth::user()->update(['password' => $request->password]);
        return back()->with('success', 'Password berhasil diubah.');
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

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label'          => 'required|string|max:50',
            'recipient'      => 'required|string|max:100',
            'phone'          => 'required|string|max:25',
            'province_code'  => 'required|string',
            'province_name'  => 'required|string',
            'regency_code'   => 'required|string',
            'regency_name'   => 'required|string',
            'district_code'  => 'required|string',
            'district_name'  => 'required|string',
            'village_name'   => 'nullable|string',
            'street'         => 'required|string|max:300',
            'postal_code'    => 'nullable|string|max:10',
        ]);

        $user    = Auth::user();
        $isFirst = $user->addresses()->count() === 0;

        if ($request->boolean('is_default') || $isFirst) {
            $user->addresses()->update(['is_default' => false]);
        }

        // Susun alamat lengkap
        $addressParts = array_filter([
            $request->street,
            $request->village_name,
            $request->district_name,
            $request->regency_name,
            $request->province_name,
            $request->postal_code,
        ]);

        $user->addresses()->create([
            'label'         => $request->label,
            'recipient'     => $request->recipient,
            'phone'         => $request->phone,
            'address'       => implode(', ', $addressParts),
            'city'          => $request->regency_name,
            'province'      => $request->province_name,
            'district'      => $request->district_name,
            'village'       => $request->village_name,
            'postal_code'   => $request->postal_code,
            'province_code' => $request->province_code,
            'regency_code'  => $request->regency_code,
            'district_code' => $request->district_code,
            'is_default'    => $request->boolean('is_default') || $isFirst,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan.')->with('active_tab', 'alamat');
    }

    public function setDefault(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama diperbarui.');
    }

    public function destroyAddress(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'Alamat dihapus.');
    }
}
