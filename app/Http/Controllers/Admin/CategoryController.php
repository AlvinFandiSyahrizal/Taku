<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Admin hanya kelola kategori global (store_id null)
        $categories = Category::whereNull('store_id')
            ->whereNull('parent_id')
            ->withCount('products')
            ->with(['children' => function ($q) {
                $q->withCount('products')->orderBy('sort');
            }])
            ->orderBy('sort')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'icon'      => 'nullable|string|max:10',
            'sort'      => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Cek nama unik di lingkup global (store_id null)
        $exists = Category::whereNull('store_id')
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Nama kategori sudah ada.'])->withInput();
        }

        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'icon'      => $request->icon,
            'sort'      => $request->sort ?? 0,
            'is_active' => true,
            'parent_id' => $request->parent_id ?: null,
            'store_id'  => null, // selalu global untuk admin
        ]);

        $type = $request->parent_id ? 'Sub-kategori' : 'Kategori';
        return back()->with('success', $type . ' "' . $request->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        // Pastikan admin hanya edit kategori global
        abort_if($category->store_id !== null, 403);

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

        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'icon'      => $request->icon,
            'sort'      => $request->sort ?? 0,
            'parent_id' => $newParentId,
        ]);

        return back()->with('success', 'Kategori "' . $request->name . '" berhasil diupdate.');
    }

    public function destroy(Category $category)
    {
        abort_if($category->store_id !== null, 403);

        $category->children()->delete();
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function toggle(Category $category)
    {
        abort_if($category->store_id !== null, 403);

        $category->update(['is_active' => !$category->is_active]);
        return back();
    }
}
