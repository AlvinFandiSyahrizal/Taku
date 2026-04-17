<?php
namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function create()
    {
        $store = Auth::user()->store;

        if ($store) {
            if ($store->isActive()) return redirect()->route('merchant.dashboard');
            return redirect()->route('store.pending');
        }

        return view('store.register');
    }

    public function store(Request $request)
    {
        $store = Auth::user()->store;

        if ($store) {
            if ($store->isActive()) return redirect()->route('merchant.dashboard');
            return redirect()->route('store.pending');
        }

        $request->validate([
            'name'        => 'required|string|max:100|unique:stores,name',
            'phone'       => 'required|string|max:20',
            'city'        => 'required|string|max:100',
            'description' => 'required|string|min:30|max:500',
            'agreed_terms'=> 'accepted',
        ], [
            'agreed_terms.accepted' => 'Kamu harus menyetujui syarat dan ketentuan.',
            'description.min'       => 'Deskripsi minimal 30 karakter.',
            'phone.required'        => 'Nomor WhatsApp wajib diisi.',
            'city.required'         => 'Kota wajib diisi.',
        ]);

        Store::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'description' => $request->description,
            'phone'       => $request->phone,
            'city'        => $request->city,
            'agreed_terms'=> true,
            'status'      => 'pending',
        ]);

                \App\Models\Notification::create([
            'store_id' => null, 
            'type'     => 'store_registered',
            'title'    => 'Pendaftaran Toko Baru',
            'body'     => "Toko \"{$request->name}\" mendaftar dan menunggu approval.",
            'data'     => ['store_name' => $request->name],
        ]);

        return redirect()->route('store.pending');
    }

    public function pending()
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('store.register');
        if ($store->isActive()) return redirect()->route('merchant.dashboard');
        return view('store.pending', compact('store'));
    }
    public function edit()
    {
        $store = Auth::user()->store;
        if (!$store || !$store->canResubmit()) abort(403);
        return view('store.edit', compact('store'));
    }

    public function resubmit(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store || !$store->canResubmit()) abort(403);

        $request->validate([
            'phone'       => 'required|string|max:20',
            'city'        => 'required|string|max:100',
            'description' => 'required|string|min:30|max:500',
        ]);

        $store->update([
            'phone'           => $request->phone,
            'city'            => $request->city,
            'description'     => $request->description,
            'reject_reason'   => null,
            'resubmitted_at'  => now(),
        ]);

        return redirect()->route('store.pending')
            ->with('success', 'Pengajuan toko kamu sudah diperbarui dan menunggu review ulang.');
    }
    public function cancel()
    {
        $store = Auth::user()->store;
        if (!$store || $store->isActive()) abort(403);
        $store->delete();
        return redirect()->route('home')
            ->with('success', 'Pengajuan toko dibatalkan.');
    }

    public function show($slug)
    {
        $store = Store::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $products = \App\Models\Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->with('images', 'category')
            ->latest()->get();

        $categories = $products->pluck('category')->filter()->unique('id')->values();

        try {
            $banners = \App\Models\StoreBanner::where('store_id', $store->id)
                ->where('is_active', true)->orderBy('sort')->get();
        } catch (\Exception $e) {
            $banners = collect();
        }

        try {
            $sections = \App\Models\StoreSection::where('store_id', $store->id)
                ->where('is_active', true)
                ->with(['products' => fn($q) => $q->where('is_active', true)->with('images')])
                ->orderBy('sort')->get();
        } catch (\Exception $e) {
            $sections = collect();
        }

        return view('pages.store', compact('store', 'products', 'categories', 'banners', 'sections'));
    }

    public function showOfficial(Request $request)
    {
        $officialPhone = \App\Models\Setting::get('official_store_phone', '');
        $officialName  = \App\Models\Setting::get('official_store_name', 'Taku Official');
        $officialDesc  = \App\Models\Setting::get('official_store_desc', 'Produk kurasi langsung dari tim Taku.');

        $store = (object)[
            'id'          => null,
            'name'        => $officialName,
            'description' => $officialDesc,
            'phone'       => $officialPhone,
            'logo'        => null,
            'approved_at' => null,
            'slug'        => 'taku-official',
        ];

        $products   = \App\Models\Product::whereNull('store_id')->where('is_active', true)
            ->with('images','category')->latest()->get();
        $categories = $products->pluck('category')->filter()->unique('id')->values();

        try {
            $banners = \App\Models\StoreBanner::whereNull('store_id')
                ->where('is_active', true)->orderBy('sort')->get();
        } catch (\Exception $e) { $banners = collect(); }

        try {
            $sections = \App\Models\StoreSection::whereNull('store_id')
                ->where('is_active', true)
                ->with(['products' => fn($q) => $q->where('is_active', true)->with('images','category')])
                ->orderBy('sort')->get();
        } catch (\Exception $e) { $sections = collect(); }

        return view('pages.store-official', compact('store','products','categories','banners','sections','officialPhone'));
    }

}
