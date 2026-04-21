<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSection;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreSectionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'rows'     => 'required|integer|min:1|max:3',
        ]);

        StoreSection::create([
            'store_id'   => null,
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'rows'       => $request->rows,
            'auto_slide' => $request->has('auto_slide'), 
            'is_active'  => true,
            'sort'       => StoreSection::whereNull('store_id')->max('sort') + 1,
        ]);

        return back()->with('success', 'Section dibuat.');
    }

    public function update(Request $request, StoreSection $section)
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

    public function destroy(StoreSection $section)
    {
        $section->delete();
        return back()->with('success', 'Section dihapus.');
    }

    public function toggle(StoreSection $section)
    {
        $section->update(['is_active' => !$section->is_active]);
        return back();
    }

    public function addProduct(Request $request, StoreSection $section)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        if (!$section->products()->where('product_id', $request->product_id)->exists()) {
            $section->products()->attach($request->product_id, [
                'sort' => $section->products()->count()
            ]);
        }
        return back()->with('success', 'Produk ditambahkan ke section.');
    }

    public function removeProduct(StoreSection $section, Product $product)
    {
        $section->products()->detach($product->id);
        return back()->with('success', 'Produk dihapus dari section.');
    }
}