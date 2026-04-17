<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.images', 'product.store'])
            ->latest()->get();

        return view('pages.wishlist', compact('wishlists'));
    }

public function toggle(Product $product)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $exists = $user->wishlist()->where('product_id', $product->id)->exists();

    if ($exists) {
        $user->wishlist()->detach($product->id);
        $status = 'removed';
    } else {
        $user->wishlist()->attach($product->id);
        $status = 'added';
    }

    return response()->json([
        'status' => $status,
        'count' => $user->wishlist()->count()
    ]);
}
    
}
