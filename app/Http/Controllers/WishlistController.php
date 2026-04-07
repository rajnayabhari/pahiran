<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistedProducts = auth()->user()->wishlist()->with('category', 'seller')->get();
        return view('storefront.wishlist', compact('wishlistedProducts'));
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();
        
        if ($user->wishlist()->where('product_id', $product->id)->exists()) {
            $user->wishlist()->detach($product->id);
            $status = 'removed';
            $message = 'Removed from wishlist.';
        } else {
            $user->wishlist()->attach($product->id);
            $status = 'added';
            $message = 'Added to wishlist.';
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
