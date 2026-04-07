@extends('layouts.admin')
@section('title', 'Manage Products — Pahiran')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Manage Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-stone-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-stone-800 transition">+ Add Product</a>
</div>

<div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500">
                <tr>
                    <th class="px-5 py-3 text-left">Product</th>
                    <th class="px-5 py-3 text-left">Seller</th>
                    <th class="px-5 py-3 text-left">Category</th>
                    <th class="px-5 py-3 text-right">Price</th>
                    <th class="px-5 py-3 text-center">Variants</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($products as $product)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-5 py-3 text-stone-600">{{ $product->seller->shop_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-stone-600">{{ $product->category->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-right">Rs. {{ number_format($product->base_price) }}</td>
                    <td class="px-5 py-3 text-center">{{ $product->variants->count() }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-200 text-stone-600' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-amber-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Delete this product permanently?')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-stone-400">
                        No products found. <a href="{{ route('admin.products.create') }}" class="text-amber-600 hover:underline">Create a product</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
