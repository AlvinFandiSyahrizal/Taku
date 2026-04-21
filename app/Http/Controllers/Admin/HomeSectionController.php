<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::with('products')->orderBy('sort')->get();
        $products = Product::where('is_active', true)->with('category')->orderBy('name')->get();
        return view('admin.home-sections.index', compact('sections', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'rows'     => 'required|integer|min:1|max:3',
        ]);

        HomeSection::create([
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'rows'       => $request->rows,
            'auto_slide' => $request->has('auto_slide'), 
            'is_active'  => true,
            'sort'       => HomeSection::max('sort') + 1,
        ]);

        return back()->with('success', 'Section dibuat.');
    }

    public function update(Request $request, HomeSection $section)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'rows'     => 'required|integer|min:1|max:3',
        ]);

        $section->update([
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'rows'       => $request->rows,
            'auto_slide' => $request->has('auto_slide'), 
        ]);

        return back()->with('success', 'Section diupdate.');
    }

    public function destroy(HomeSection $section)
    {
        $section->delete();
        return back()->with('success', 'Section dihapus.');
    }

    public function toggle(HomeSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);
        return back();
    }

    public function addProduct(Request $request, HomeSection $section)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        if (!$section->products()->where('product_id', $request->product_id)->exists()) {
            $section->products()->attach($request->product_id, [
                'sort' => $section->products()->count()
            ]);
        }
        return back()->with('success', 'Produk ditambahkan ke section.');
    }

    public function removeProduct(HomeSection $section, Product $product)
    {
        $section->products()->detach($product->id);
        return back()->with('success', 'Produk dihapus dari section.');
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $sort => $id) {
            HomeSection::where('id', $id)->update(['sort' => $sort]);
        }
        return response()->json(['ok' => true]);
    }
}