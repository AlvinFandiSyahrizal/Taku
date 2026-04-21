<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StoreBanner;
use App\Models\StoreSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreAppearanceController extends Controller
{
    private function store() { return Auth::user()->store; }

    public function index()
    {
        $store    = $this->store();
        $banners  = StoreBanner::where('store_id', $store->id)
            ->orderBy('position')->orderBy('sort')->get();
        $sections = StoreSection::where('store_id', $store->id)
            ->with(['products' => fn($q) => $q->with('category')])
            ->orderBy('sort')->get();
        $products = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->with('category')
            ->get();

        return view('merchant.store-appearance', compact('store', 'banners', 'sections', 'products'));
    }


    public function storeBanner(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:100',
            'subtitle'    => 'nullable|string|max:200',
            'image'       => 'required|image|max:2048',
            'link'        => 'nullable|string|max:200',
            'button_text' => 'nullable|string|max:50',
            'position'    => 'nullable|in:top,after_sections,bottom',
        ]);

        $imagePath = 'storage/' . $request->file('image')->store('store-banners', 'public');

        StoreBanner::create([
            'store_id'    => $this->store()->id,
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image'       => $imagePath,
            'link'        => $request->link,
            'button_text' => $request->button_text,
            'auto_slide'  => $request->has('auto_slide'), 
            'sort'        => StoreBanner::where('store_id', $this->store()->id)->count(),
            'position'    => $request->get('position', 'top'),
            'is_active'   => true,
        ]);

        return back()->with('success', 'Banner ditambahkan.');
    }

    public function updateBanner(Request $request, StoreBanner $banner)
    {
        abort_if($banner->store_id !== $this->store()->id, 403);

        $request->validate([
            'title'       => 'nullable|string|max:100',
            'subtitle'    => 'nullable|string|max:200',
            'image'       => 'nullable|image|max:2048',
            'link'        => 'nullable|string|max:200',
            'button_text' => 'nullable|string|max:50',
            'position'    => 'nullable|in:top,after_sections,bottom',
        ]);

        $data = [
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'link'        => $request->link,
            'button_text' => $request->button_text,
            'auto_slide'  => $request->has('auto_slide'), 
            'position'    => $request->get('position', $banner->position ?? 'top'),
        ];

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
            }
            $data['image'] = 'storage/' . $request->file('image')->store('store-banners', 'public');
        }

        $banner->update($data);
        return back()->with('success', 'Banner diupdate.');
    }

    public function toggleBanner(StoreBanner $banner)
    {
        abort_if($banner->store_id !== $this->store()->id, 403);
        $banner->update(['is_active' => !$banner->is_active]);
        return back();
    }

    public function destroyBanner(StoreBanner $banner)
    {
        abort_if($banner->store_id !== $this->store()->id, 403);
        if ($banner->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
        }
        $banner->delete();
        return back()->with('success', 'Banner dihapus.');
    }

    public function reorderBanner(Request $request): \Illuminate\Http\JsonResponse
    {
        $store    = $this->store();
        $ids      = $request->input('ids', []);
        $position = $request->input('position', 'top');

        foreach ($ids as $sort => $id) {
            StoreBanner::where('id', $id)
                ->where('store_id', $store->id)
                ->update(['sort' => $sort, 'position' => $position]);
        }

        return response()->json(['ok' => true]);
    }


    public function storeSection(Request $request)
    {
        $request->validate(['title' => 'required|string|max:100']);

        StoreSection::create([
            'store_id'   => $this->store()->id,
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'rows'       => $request->get('rows', 1),
            'auto_slide' => $request->has('auto_slide'), 
            'sort'       => StoreSection::where('store_id', $this->store()->id)->count(),
        ]);

        return back()->with('success', 'Section dibuat.');
    }

    public function updateSection(Request $request, StoreSection $section)
    {
        abort_if($section->store_id !== $this->store()->id, 403);
        $request->validate(['title' => 'required|string|max:100']);

        $section->update([
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'rows'       => $request->get('rows', 1),
            'auto_slide' => $request->has('auto_slide'), 
        ]);

        return back()->with('success', 'Section diupdate.');
    }

    public function toggleSection(StoreSection $section)
    {
        abort_if($section->store_id !== $this->store()->id, 403);
        $section->update(['is_active' => !$section->is_active]);
        return back();
    }

    public function destroySection(StoreSection $section)
    {
        abort_if($section->store_id !== $this->store()->id, 403);
        $section->delete();
        return back()->with('success', 'Section dihapus.');
    }

    public function addProduct(Request $request, StoreSection $section)
    {
        abort_if($section->store_id !== $this->store()->id, 403);
        $request->validate(['product_id' => 'required|exists:products,id']);

        if (!$section->products->contains($request->product_id)) {
            $section->products()->attach($request->product_id, [
                'sort' => $section->products()->count()
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke section.');
    }

    public function removeProduct(StoreSection $section, Product $product)
    {
        abort_if($section->store_id !== $this->store()->id, 403);
        $section->products()->detach($product->id);
        return back()->with('success', 'Produk dihapus dari section.');
    }
}