<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreBanner;
use App\Models\StoreSection;
use App\Models\Product;
use App\Models\Category;
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
            'description' => 'required|string|min:30|max:500',
            'province'    => 'required|string',
            'regency'     => 'required|string',
            'district'    => 'required|string',
            'village'     => 'nullable|string',
            'street'      => 'required|string|max:200',
            'building_no' => 'nullable|string|max:50',
            'rt_rw'       => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'agreed_terms'=> 'accepted',
        ], [
            'agreed_terms.accepted' => 'Kamu harus menyetujui syarat dan ketentuan.',
            'description.min'       => 'Deskripsi minimal 30 karakter.',
            'province.required'     => 'Provinsi wajib dipilih.',
            'regency.required'      => 'Kabupaten/Kota wajib dipilih.',
            'district.required'     => 'Kecamatan wajib dipilih.',
            'street.required'       => 'Nama jalan wajib diisi.',
        ]);

        $parts = array_filter([
            $request->street,
            $request->building_no ? 'No. ' . $request->building_no : null,
            $request->rt_rw       ? 'RT/RW ' . $request->rt_rw     : null,
        ]);

        Store::create([
            'user_id'      => Auth::id(),
            'name'         => $request->name,
            'description'  => $request->description,
            'phone'        => $request->phone,
            'city'         => $request->regency_name ?: $request->city,
            'address'      => implode(', ', $parts),
            'province'     => $request->province_name,
            'district'     => $request->district_name,
            'village'      => $request->village_name,
            'postal_code'  => $request->postal_code,
            'agreed_terms' => true,
            'status'       => 'pending',
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
            'phone'          => $request->phone,
            'city'           => $request->city,
            'description'    => $request->description,
            'reject_reason'  => null,
            'resubmitted_at' => now(),
        ]);

        return redirect()->route('store.pending')
            ->with('success', 'Pengajuan toko kamu sudah diperbarui dan menunggu review ulang.');
    }

    public function cancel()
    {
        $store = Auth::user()->store;
        if (!$store || $store->isActive()) abort(403);
        $store->delete();
        return redirect()->route('home')->with('success', 'Pengajuan toko dibatalkan.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STORE SHOW (Merchant Store)
    // ─────────────────────────────────────────────────────────────────────────
    public function show(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $perPage     = 20;
        $categorySlug = $request->get('category');
        $subSlug      = $request->get('sub');
        $sort         = $request->get('sort', 'latest');

        // ── Kategori sidebar: parent categories yang punya produk di toko ini
        $allProducts = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->pluck('category_id')
            ->filter()
            ->unique()
            ->values();

        // Ambil kategori parent yang punya produk (langsung atau via anak)
        $sidebarCategories = Category::with(['children' => function ($q) use ($allProducts) {
                $q->whereIn('id', $allProducts)->active()->orderBy('sort');
            }])
            ->whereNull('parent_id')
            ->active()
            ->where(function ($q) use ($allProducts) {
                // parent langsung punya produk ATAU punya anak yang punya produk
                $q->whereIn('id', $allProducts)
                  ->orWhereHas('children', fn($c) => $c->whereIn('id', $allProducts));
            })
            ->orderBy('sort')
            ->get();

        // ── Query produk utama (untuk katalog & pagination)
        $productQuery = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->with('images', 'category');

        // Filter kategori
        if ($subSlug) {
            $productQuery->whereHas('category', fn($q) => $q->where('slug', $subSlug));
        } elseif ($categorySlug) {
            $productQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->orWhereHas('parent', fn($p) => $p->where('slug', $categorySlug));
            });
        }

        // Sort
        match ($sort) {
            'oldest'    => $productQuery->oldest(),
            'price_asc' => $productQuery->orderBy('price'),
            'price_desc'=> $productQuery->orderByDesc('price'),
            'name'      => $productQuery->orderBy('name'),
            default     => $productQuery->latest(),
        };

        $products    = $productQuery->paginate($perPage)->withQueryString();
        $totalProducts = Product::where('store_id', $store->id)->where('is_active', true)->count();

        // ── Banners per position
        $bannersTop           = StoreBanner::where('store_id', $store->id)->active()->where('position', 'top')->orderBy('sort')->get();
        $bannersAfterSections = StoreBanner::where('store_id', $store->id)->active()->where('position', 'after_sections')->orderBy('sort')->get();
        $bannersBottom        = StoreBanner::where('store_id', $store->id)->active()->where('position', 'bottom')->orderBy('sort')->get();

        // ── Sections
        $sections = StoreSection::where('store_id', $store->id)
            ->where('is_active', true)
            ->with(['products' => fn($q) => $q->where('is_active', true)->with('images', 'category')])
            ->orderBy('sort')
            ->get();

        // Active category objects for breadcrumb
        $activeCategory    = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;
        $activeSubCategory = $subSlug      ? Category::where('slug', $subSlug)->first()      : null;

        return view('pages.store', compact(
            'store', 'products', 'totalProducts',
            'sidebarCategories', 'activeCategory', 'activeSubCategory',
            'bannersTop', 'bannersAfterSections', 'bannersBottom',
            'sections', 'sort', 'categorySlug', 'subSlug'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OFFICIAL STORE
    // ─────────────────────────────────────────────────────────────────────────
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

        $perPage      = 20;
        $categorySlug = $request->get('category');
        $subSlug      = $request->get('sub');
        $sort         = $request->get('sort', 'latest');

        // Kategori sidebar
        $allProducts = Product::whereNull('store_id')->where('is_active', true)
            ->pluck('category_id')->filter()->unique()->values();

        $sidebarCategories = Category::with(['children' => function ($q) use ($allProducts) {
                $q->whereIn('id', $allProducts)->active()->orderBy('sort');
            }])
            ->whereNull('parent_id')
            ->active()
            ->where(function ($q) use ($allProducts) {
                $q->whereIn('id', $allProducts)
                  ->orWhereHas('children', fn($c) => $c->whereIn('id', $allProducts));
            })
            ->orderBy('sort')
            ->get();

        // Query produk
        $productQuery = Product::whereNull('store_id')
            ->where('is_active', true)
            ->with('images', 'category');

        if ($subSlug) {
            $productQuery->whereHas('category', fn($q) => $q->where('slug', $subSlug));
        } elseif ($categorySlug) {
            $productQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->orWhereHas('parent', fn($p) => $p->where('slug', $categorySlug));
            });
        }

        match ($sort) {
            'oldest'    => $productQuery->oldest(),
            'price_asc' => $productQuery->orderBy('price'),
            'price_desc'=> $productQuery->orderByDesc('price'),
            'name'      => $productQuery->orderBy('name'),
            default     => $productQuery->latest(),
        };

        $products      = $productQuery->paginate($perPage)->withQueryString();
        $totalProducts = Product::whereNull('store_id')->where('is_active', true)->count();

        $bannersTop           = StoreBanner::whereNull('store_id')->active()->where('position', 'top')->orderBy('sort')->get();
        $bannersAfterSections = StoreBanner::whereNull('store_id')->active()->where('position', 'after_sections')->orderBy('sort')->get();
        $bannersBottom        = StoreBanner::whereNull('store_id')->active()->where('position', 'bottom')->orderBy('sort')->get();

        $sections = StoreSection::whereNull('store_id')
            ->where('is_active', true)
            ->with(['products' => fn($q) => $q->where('is_active', true)->with('images', 'category')])
            ->orderBy('sort')
            ->get();

        $activeCategory    = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;
        $activeSubCategory = $subSlug      ? Category::where('slug', $subSlug)->first()      : null;

        return view('pages.store-official', compact(
            'store', 'products', 'totalProducts',
            'sidebarCategories', 'activeCategory', 'activeSubCategory',
            'bannersTop', 'bannersAfterSections', 'bannersBottom',
            'sections', 'sort', 'categorySlug', 'subSlug',
            'officialPhone'
        ));
    }
}
