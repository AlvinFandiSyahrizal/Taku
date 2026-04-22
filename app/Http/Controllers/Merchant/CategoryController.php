<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function myStore()
    {
        return Auth::user()->store;
    }

    private function authorizeCategory(Category $category): void
    {
        // Merchant hanya boleh kelola kategori milik toko sendiri
        abort_if($category->store_id !== $this->myStore()->id, 403);
    }

    public function index()
    {
        $store = $this->myStore();

        $categories = Category::where('store_id', $store->id)
            ->whereNull('parent_id')
            ->withCount('products')
            ->with(['children' => function ($q) {
                $q->withCount('products')->orderBy('sort');
            }])
            ->orderBy('sort')
            ->get();

        return view('merchant.categories.index', compact('categories', 'store'));
    }

    public function store(Request $request)
    {
        $store = $this->myStore();

        $request->validate([
            'name'      => 'required|string|max:100',
            'icon'      => 'nullable|string|max:10',
            'sort'      => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Cek nama unik di lingkup store ini
        $exists = Category::where('store_id', $store->id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Nama kategori sudah ada di toko kamu.'])->withInput();
        }

        // Validasi parent_id harus milik store yang sama
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            abort_if(!$parent || $parent->store_id !== $store->id, 422);
        }

        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name) . '-s' . $store->id . '-' . time(),
            'icon'      => $request->icon,
            'sort'      => $request->sort ?? 0,
            'is_active' => true,
            'parent_id' => $request->parent_id ?: null,
            'store_id'  => $store->id,
        ]);

        $type = $request->parent_id ? 'Sub-kategori' : 'Kategori';
        return back()->with('success', $type . ' "' . $request->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeCategory($category);
        $store = $this->myStore();

        $request->validate([
            'name'      => 'required|string|max:100',
            'icon'      => 'nullable|string|max:10',
            'sort'      => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $newParentId = $request->parent_id ?: null;

        // Cegah circular
        if ($newParentId && $newParentId == $category->id) {
            $newParentId = null;
        }

        // Pastikan parent baru milik store yang sama
        if ($newParentId) {
            $parent = Category::find($newParentId);
            if (!$parent || $parent->store_id !== $store->id) {
                $newParentId = null;
            }
        }

        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name) . '-s' . $store->id . '-' . $category->id,
            'icon'      => $request->icon,
            'sort'      => $request->sort ?? 0,
            'parent_id' => $newParentId,
        ]);

        return back()->with('success', 'Kategori "' . $request->name . '" berhasil diupdate.');
    }

    public function destroy(Category $category)
    {
        $this->authorizeCategory($category);

        $category->children()->delete();
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function toggle(Category $category)
    {
        $this->authorizeCategory($category);

        $category->update(['is_active' => !$category->is_active]);
        return back();
    }
}