<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
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

    /** Ambil kategori milik store ini beserta children, untuk dropdown form produk */
    private function getCategories()
    {
        $store = $this->myStore();

        return Category::where('store_id', $store->id)
            ->whereNull('parent_id')
            ->with(['children' => fn($q) => $q->active()->orderBy('sort')])
            ->active()
            ->orderBy('sort')
            ->get();
    }

    public function index()
    {
        $store    = $this->myStore();
        $products = Product::where('store_id', $store->id)->with('images')->latest()->get();
        return view('merchant.products.index', compact('products', 'store'));
    }

    public function create()
    {
        $categories = $this->getCategories();
        return view('merchant.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:200',
            'price'            => 'required|integer|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock'            => 'nullable|integer|min:0',
            'category_id'      => 'nullable|exists:categories,id',
            'desc_id'          => 'nullable|string',
            'desc_en'          => 'nullable|string',
            'detail_id'        => 'nullable|string',
            'detail_en'        => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'images.*'         => 'nullable|image|max:2048',
            'is_active'        => 'nullable',
            'height'           => 'nullable|numeric|min:0',
            'height_unit'      => 'nullable|in:cm,meter',
            'diameter'         => 'nullable|numeric|min:0',
            'diameter_unit'    => 'nullable|in:cm,meter',
            'variants'                 => 'nullable|array',
            'variants.*.height'        => 'nullable|numeric|min:0',
            'variants.*.height_unit'   => 'nullable|in:cm,meter',
            'variants.*.diameter'      => 'nullable|numeric|min:0',
            'variants.*.diameter_unit' => 'nullable|in:cm,meter',
            'variants.*.price'         => 'required_with:variants|integer|min:0',
            'variants.*.stock'         => 'nullable|integer|min:0',
        ]);

        // Pastikan category_id yang dipilih memang milik store ini
        if ($request->category_id) {
            $cat = Category::find($request->category_id);
            if (!$cat || $cat->store_id !== $this->myStore()->id) {
                return back()->withErrors(['category_id' => 'Kategori tidak valid.'])->withInput();
            }
        }

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
            'category_id'      => $request->category_id ?: null,
            'desc_id'          => $request->desc_id,
            'desc_en'          => $request->desc_en,
            'detail_id'        => $request->detail_id,
            'detail_en'        => $request->detail_en,
            'image'            => $imagePath ? 'storage/' . $imagePath : null,
            'is_active'        => $request->boolean('is_active', true),
            'is_featured'      => $request->boolean('is_featured', false),
            'height'           => $request->height ?: null,
            'height_unit'      => $request->height ? ($request->height_unit ?? 'cm') : 'cm',
            'diameter'         => $request->diameter ?: null,
            'diameter_unit'    => $request->diameter ? ($request->diameter_unit ?? 'cm') : 'cm',
        ]);

        $this->syncVariants($product, $request->input('variants', []));

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
        $product->load('images', 'variants');
        $categories = $this->getCategories();
        return view('merchant.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name'             => 'required|string|max:200',
            'price'            => 'required|integer|min:0',
            'category_id'      => 'nullable|exists:categories,id',
            'desc_id'          => 'nullable|string',
            'desc_en'          => 'nullable|string',
            'detail_id'        => 'nullable|string',
            'detail_en'        => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
            'images.*'         => 'nullable|image|max:2048',
            'is_active'        => 'nullable',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock'            => 'nullable|integer|min:0',
            'height'           => 'nullable|numeric|min:0',
            'height_unit'      => 'nullable|in:cm,meter',
            'diameter'         => 'nullable|numeric|min:0',
            'diameter_unit'    => 'nullable|in:cm,meter',
            'variants'                 => 'nullable|array',
            'variants.*.height'        => 'nullable|numeric|min:0',
            'variants.*.height_unit'   => 'nullable|in:cm,meter',
            'variants.*.diameter'      => 'nullable|numeric|min:0',
            'variants.*.diameter_unit' => 'nullable|in:cm,meter',
            'variants.*.price'         => 'required_with:variants|integer|min:0',
            'variants.*.stock'         => 'nullable|integer|min:0',
        ]);

        // Pastikan category_id milik store ini
        if ($request->category_id) {
            $cat = Category::find($request->category_id);
            if (!$cat || $cat->store_id !== $this->myStore()->id) {
                return back()->withErrors(['category_id' => 'Kategori tidak valid.'])->withInput();
            }
        }

        $currentImage = $product->image;
        if ($request->hasFile('image')) {
            if ($currentImage) {
                Storage::disk('public')->delete(str_replace('storage/', '', $currentImage));
            }
            $newPath      = $request->file('image')->store('products', 'public');
            $currentImage = 'storage/' . $newPath;
        }

        $product->update([
            'name'             => $request->name,
            'slug'             => Str::slug($request->name) . '-' . $product->id,
            'price'            => $request->price,
            'category_id'      => $request->category_id ?: null,
            'desc_id'          => $request->desc_id,
            'desc_en'          => $request->desc_en,
            'detail_id'        => $request->detail_id,
            'detail_en'        => $request->detail_en,
            'image'            => $currentImage,
            'is_active'        => $request->boolean('is_active', true),
            'stock'            => (int) $request->get('stock', 0),
            'is_featured'      => $request->boolean('is_featured', false),
            'discount_percent' => (int) $request->get('discount_percent', 0),
            'height'           => $request->height ?: null,
            'height_unit'      => $request->height ? ($request->height_unit ?? 'cm') : 'cm',
            'diameter'         => $request->diameter ?: null,
            'diameter_unit'    => $request->diameter ? ($request->diameter_unit ?? 'cm') : 'cm',
        ]);

        $this->syncVariants($product, $request->input('variants', []));

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
        if ($image->product->store_id !== $this->myStore()->id) abort(403);
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

    private function syncVariants(Product $product, array $variantsInput): void
    {
        $incomingIds = [];

        foreach ($variantsInput as $i => $v) {
            if (empty($v['price'])) continue;

            $data = [
                'product_id'    => $product->id,
                'height'        => $v['height'] ?: null,
                'height_unit'   => $v['height'] ? ($v['height_unit'] ?? 'cm') : 'cm',
                'diameter'      => $v['diameter'] ?: null,
                'diameter_unit' => $v['diameter'] ? ($v['diameter_unit'] ?? 'cm') : 'cm',
                'price'         => (int) $v['price'],
                'stock'         => (int) ($v['stock'] ?? 0),
                'sort'          => $i,
            ];

            if (!empty($v['id'])) {
                $variant = ProductVariant::where('id', $v['id'])
                    ->where('product_id', $product->id)->first();
                if ($variant) {
                    $variant->update($data);
                    $incomingIds[] = $variant->id;
                }
            } else {
                $variant       = ProductVariant::create($data);
                $incomingIds[] = $variant->id;
            }
        }

        $product->variants()->whereNotIn('id', $incomingIds)->delete();
    }
}