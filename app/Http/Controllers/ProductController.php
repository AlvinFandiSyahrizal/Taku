<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->get('q', '');

        if ($searchQuery) {
            $searchProducts = Product::active()
                ->with('images', 'store')
                ->where('name', 'like', "%{$searchQuery}%")
                ->take(12)->get();

            $searchStores = Store::where('status', 'active')
                ->where('name', 'like', "%{$searchQuery}%")
                ->take(6)->get();

            return view('pages.search-results', compact(
                'searchQuery', 'searchProducts', 'searchStores'
            ));
        }

        // ── Banners ────────────────────────────────────────────────────────
        $banners = \App\Models\Banner::active()->orderBy('sort')->get();

        // ── Kategori (hanya yang punya produk aktif) ───────────────────────
        $categories = Category::active()
            ->whereNull('store_id')        // hanya kategori global
            ->whereNull('parent_id')       // hanya induk
            ->withCount(['products' => fn($q) => $q->active()])
            ->having('products_count', '>', 0)
            ->orderBy('sort')
            ->take(12)
            ->get();

        // ── Produk terlaris (by order count) ──────────────────────────────
        $bestSellers = Product::active()
            ->with('images', 'store', 'variants')
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(10)->get();

        // ── Produk terbaru ─────────────────────────────────────────────────
        $newArrivals = Product::active()
            ->with('images', 'store', 'variants')
            ->latest()
            ->take(10)->get();

        // ── Produk sedang diskon ───────────────────────────────────────────
        $onSaleProducts = Product::active()
            ->with('images', 'store', 'variants')
            ->where('discount_percent', '>', 0)
            ->orderByDesc('discount_percent')
            ->take(10)->get();

        // ── Toko terbaik (produk aktif terbanyak) ─────────────────────────
        $topStores = Store::where('status', 'active')
            ->withCount(['products' => fn($q) => $q->active()])
            ->having('products_count', '>', 0)
            ->orderByDesc('products_count')
            ->take(6)->get();

        // Prepend Taku Official jika ada produk official
        $officialCount = Product::whereNull('store_id')->where('is_active', true)->count();
        if ($officialCount > 0) {
            $officialStore = (object) [
                'id'             => null,
                'name'           => 'Taku Official',
                'description'    => 'Koleksi tanaman kurasi langsung dari tim Taku.',
                'logo'           => null,
                'slug'           => 'taku-official',
                'products_count' => $officialCount,
                'is_official'    => true,
            ];
            $topStores = $topStores->prepend($officialStore);
        }

        // ── Home sections manual dari admin ───────────────────────────────
        $homeSections = \App\Models\HomeSection::active()
            ->with(['products' => fn($q) => $q->active()->with('images', 'store', 'variants')])
            ->orderBy('sort')
            ->get();

        return view('pages.home', compact(
            'banners',
            'categories',
            'bestSellers',
            'newArrivals',
            'onSaleProducts',
            'topStores',
            'homeSections'
        ));
    }

    public function shop(Request $request)
    {
        $query = Product::active()->with('images', 'store', 'category', 'variants');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('store')) {
            $query->whereHas('store', fn($q) => $q->where('slug', $request->store));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        $sort = $request->get('sort', 'default');
        match ($sort) {
            'name_az'  => $query->orderBy('name'),
            'name_za'  => $query->orderBy('name', 'desc'),
            'price_lo' => $query->orderBy('price'),
            'price_hi' => $query->orderBy('price', 'desc'),
            default    => $query->latest(),
        };

        $products    = $query->get();
        $allProducts = Product::active()->get();
        $categories  = Category::active()
            ->withCount(['products' => fn($q) => $q->active()])
            ->orderBy('sort')->get();
        $stores = Store::where('status', 'active')
            ->withCount(['products' => fn($q) => $q->active()])
            ->having('products_count', '>', 0)
            ->orderBy('name')->get();

        return view('pages.Product', [
            'products'         => $products,
            'total'            => $products->count(),
            'sort'             => $sort,
            'categories'       => $categories,
            'stores'           => $stores,
            'selectedCategory' => $request->get('category', ''),
            'selectedStore'    => $request->get('store', ''),
            'minPossible'      => $allProducts->min('price') ?? 0,
            'maxPossible'      => $allProducts->max('price') ?? 999999999,
            'minPrice'         => $request->get('min_price', $allProducts->min('price') ?? 0),
            'maxPrice'         => $request->get('max_price', $allProducts->max('price') ?? 999999999),
            'q'                => $request->get('q', ''),
        ]);
    }

    public function show($id)
    {
        $product = Product::active()->with('images', 'store', 'category', 'variants')->findOrFail($id);

        $storeProducts = collect();
        if ($product->store_id) {
            $storeProducts = Product::active()->with('images')
                ->where('id', '!=', $id)
                ->where('store_id', $product->store_id)
                ->latest()->take(12)->get();
        } else {
            $storeProducts = Product::active()->with('images')
                ->where('id', '!=', $id)
                ->whereNull('store_id')
                ->latest()->take(12)->get();
        }

        $storeProductIds = $storeProducts->pluck('id')->push($id);

        $others = Product::active()->with('images', 'store')
            ->whereNotIn('id', $storeProductIds)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->latest()->take(12)->get();

        if ($others->count() < 3) {
            $others = Product::active()->with('images', 'store')
                ->whereNotIn('id', $storeProductIds)
                ->latest()->take(12)->get();
        }

        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $id)->exists();
        }

        return view('pages.product-detail', [
            'product'       => $product,
            'storeProducts' => $storeProducts,
            'products'      => $others,
            'isWishlisted'  => $isWishlisted,
        ]);
    }

    public function toggle(Product $product)
    {
        if (!auth()->check()) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $user   = auth()->user();
        $exists = $user->wishlist()->where('product_id', $product->id)->exists();

        if ($exists) {
            $user->wishlist()->detach($product->id);
            $status = 'removed';
        } else {
            $user->wishlist()->attach($product->id);
            $status = 'added';
        }

        if (request()->expectsJson()) {
            return response()->json([
                'status' => $status,
                'count'  => $user->wishlist()->count(),
            ]);
        }

        return back();
    }
}

