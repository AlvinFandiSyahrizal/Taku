<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function myStore()
    {
        return Auth::user()->store;
    }

    private function authorizeProduct(Product $product)
    {
        if ($product->store_id !== $this->myStore()->id) {
            abort(403);
        }
    }

    public function index()
    {
        $store    = $this->myStore();
        $products = Product::where('store_id', $store->id)->with('images')->latest()->get();
        return view('merchant.products.index', compact('products', 'store'));
    }

    public function create()
    {
        return view('merchant.products.create');
    }

public function store(Request $request)
{
    $request->validate([
        'name'             => 'required|string|max:200',
        'price'            => 'required|integer|min:0',
        'discount_percent' => 'nullable|integer|min:0|max:100',
        'stock'            => 'nullable|integer|min:0',
        'desc_id'          => 'nullable|string',
        'desc_en'          => 'nullable|string',
        'detail_id'        => 'nullable|string',
        'detail_en'        => 'nullable|string',
        'image'            => 'nullable|image|max:2048',
        'images.*'         => 'nullable|image|max:2048',
        'is_active'        => 'nullable',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    $product = Product::create([
        'store_id'         => $this->myStore()->id,
        'name'             => $request->name,
        'slug'             => Str::slug($request->name) . '-' . time(),
        'price'            => $request->price,
        'discount_percent' => (int) $request->get('discount_percent', 0),
        'stock'            => (int) $request->get('stock', 0),
        'desc_id'          => $request->desc_id,
        'desc_en'          => $request->desc_en,
        'detail_id'        => $request->detail_id,
        'detail_en'        => $request->detail_en,
        'image'            => $imagePath ? 'storage/' . $imagePath : null,
        'is_active'        => $request->boolean('is_active', true),
        'is_featured'      => $request->boolean('is_featured', false),
    ]);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $i => $img) {
            $path = $img->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image'      => 'storage/' . $path,
                'sort'       => $i,
            ]);
        }
    }

    return redirect()->route('merchant.products.index')
        ->with('success', 'Produk berhasil ditambahkan.');
}

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $product->load('images');
        return view('merchant.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name'      => 'required|string|max:200',
            'price'     => 'required|integer|min:0',
            'desc_id'   => 'nullable|string',
            'desc_en'   => 'nullable|string',
            'detail_id' => 'nullable|string',
            'detail_en' => 'nullable|string',
            'image'     => 'nullable|image|max:2048',
            'images.*'  => 'nullable|image|max:2048',
            'is_active' => 'nullable',
            'discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $currentImage = $product->image;

        if ($request->hasFile('image')) {
            if ($currentImage) {
                Storage::disk('public')->delete(str_replace('storage/', '', $currentImage));
            }
            $newPath      = $request->file('image')->store('products', 'public');
            $currentImage = 'storage/' . $newPath;
        }

        $product->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name) . '-' . $product->id,
            'price'     => $request->price,
            'desc_id'   => $request->desc_id,
            'desc_en'   => $request->desc_en,
            'detail_id' => $request->detail_id,
            'detail_en' => $request->detail_en,
            'image'     => $currentImage,
            'is_active' => $request->boolean('is_active', true),
            'stock'       => (int) $request->get('stock', 0),
            'is_featured' => $request->boolean('is_featured', false),
            'discount_percent' => (int) $request->get('discount_percent', 0),
        ]);

        if ($request->hasFile('images')) {
            $lastSort = $product->images()->max('sort') ?? 0;
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => 'storage/' . $path,
                    'sort'       => $lastSort + $i + 1,
                ]);
            }
        }

        return redirect()->route('merchant.products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        foreach ($product->images as $img) {
            Storage::disk('public')->delete(str_replace('storage/', '', $img->image));
        }
        if ($product->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
        }

        $product->delete();
        return redirect()->route('merchant.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyImage(ProductImage $image)
    {
        if ($image->product->store_id !== $this->myStore()->id) {
            abort(403);
        }

        Storage::disk('public')->delete(str_replace('storage/', '', $image->image));
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function toggleActive(Product $product)
    {
        $this->authorizeProduct($product);
        $product->update(['is_active' => !$product->is_active]);
        return back();
    }
}
