<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('seller_id', Auth::guard('seller')->id())
            ->with(['category', 'variants'])
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $slug = Str::slug($validated['name']);
        $suffix = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $suffix++;
        }

        $imagePath = null;
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $imagePath = $request->file('featured_image')->store('products', 'public');
        }

        $product = Product::create([
            'seller_id' => Auth::guard('seller')->id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'base_price' => $validated['base_price'],
            'featured_image' => $imagePath,
        ]);

        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variant) {
                $product->variants()->create($variant);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        $product->load('variants');

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $validated['featured_image'] = $request->file('featured_image')->store('products', 'public');
        } else {
            unset($validated['featured_image']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully!');
    }
}
