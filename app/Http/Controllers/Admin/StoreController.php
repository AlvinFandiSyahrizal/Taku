<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('user')->latest()->get();

        $stats = [
            'pending' => $stores->where('status', 'pending')->count(),
            'active'  => $stores->where('status', 'active')->count(),
            'banned'  => $stores->where('status', 'banned')->count(),
        ];

        return view('admin.stores.index', compact('stores', 'stats'));
    }

    public function approve(Store $store)
    {
        $store->update([
            'status'      => 'active',
            'approved_at' => now(),
            'reject_reason' => null,
        ]);

        $store->user->update(['role' => 'merchant']);

        return back()->with('success', "Toko \"{$store->name}\" berhasil disetujui.");
    }

    public function reject(Request $request, Store $store)
    {
        $request->validate(['reason' => 'required|string|max:300']);

        $newCount = $store->rejection_count + 1;

        $store->update([
            'status'          => 'pending',
            'reject_reason'   => $request->reason,
            'rejection_count' => $newCount,
            'rejected_at'     => now(),
            'resubmitted_at'  => null,
        ]);

        return back()->with('success', "Pengajuan toko \"{$store->name}\" ditolak.");
    }

    public function ban(Store $store)
    {
        $store->update(['status' => 'banned']);

        $store->user->update(['role' => 'user']);

        return back()->with('success', "Toko \"{$store->name}\" telah dibanned.");
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
