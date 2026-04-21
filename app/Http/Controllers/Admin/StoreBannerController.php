<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreBanner;
use App\Models\StoreSection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreBannerController extends Controller
{
    public function index()
    {
        $banners  = StoreBanner::whereNull('store_id')->orderBy('position')->orderBy('sort')->get();
        $sections = StoreSection::whereNull('store_id')
            ->with(['products' => fn($q) => $q->with('category')])
            ->orderBy('sort')->get();
        $products = Product::whereNull('store_id')->where('is_active', true)
            ->with('category')->orderBy('name')->get();

        return view('admin.store-content.index', compact('banners', 'sections', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:100',
            'subtitle'    => 'nullable|string|max:200',
            'image'       => 'required|image|max:4096',
            'link'        => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'position'    => 'nullable|in:top,after_sections,bottom',
          
        ]);

        $path = $request->file('image')->store('store-banners', 'public');

        StoreBanner::create([
            'store_id'    => null,
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image'       => 'storage/' . $path,
            'link'        => $request->link,
            'button_text' => $request->button_text,
            'auto_slide'  => $request->has('auto_slide'), 
            'sort'        => StoreBanner::whereNull('store_id')->max('sort') + 1,
            'position'    => $request->get('position', 'top'),
            'is_active'   => true,
        ]);

        return back()->with('success', 'Banner ditambahkan.');
    }

    public function update(Request $request, StoreBanner $banner)
    {
        $request->validate([
            'title'       => 'nullable|string|max:100',
            'subtitle'    => 'nullable|string|max:200',
            'image'       => 'nullable|image|max:4096',
            'link'        => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'sort'        => 'nullable|integer',
            'position'    => 'nullable|in:top,after_sections,bottom',
        ]);

        $data = [
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'link'        => $request->link,
            'button_text' => $request->button_text,
            'sort'        => $request->sort ?? $banner->sort,
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

    public function destroy(StoreBanner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
        }
        $banner->delete();
        return back()->with('success', 'Banner dihapus.');
    }

    public function toggle(StoreBanner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back();
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $ids      = $request->input('ids', []);
        $position = $request->input('position', 'top');
        foreach ($ids as $sort => $id) {
            StoreBanner::where('id', $id)
                ->whereNull('store_id')
                ->update(['sort' => $sort, 'position' => $position]);
        }
        return response()->json(['ok' => true]);
    }
}