<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:100',
            'subtitle'    => 'nullable|string|max:200',
            'image'       => 'required|image|max:4096',
            'link'        => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'sort'        => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'image'       => 'storage/' . $path,
            'link'        => $request->link,
            'button_text' => $request->button_text,
            'auto_slide'  => $request->has('auto_slide'), 
            'sort'        => $request->sort ?? Banner::max('sort') + 1,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Banner berhasil ditambahkan.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title'       => 'nullable|string|max:100',
            'subtitle'    => 'nullable|string|max:200',
            'image'       => 'nullable|image|max:4096',
            'link'        => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'sort'        => 'nullable|integer',
        ]);

        $data = $request->only('title', 'subtitle', 'link', 'button_text', 'sort');
        $data['auto_slide'] = $request->has('auto_slide'); 

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
            }
            $data['image'] = 'storage/' . $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        return back()->with('success', 'Banner diupdate.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
        }
        $banner->delete();
        return back()->with('success', 'Banner dihapus.');
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back();
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $sort => $id) {
            Banner::where('id', $id)->update(['sort' => $sort]);
        }
        return response()->json(['ok' => true]);
    }
}