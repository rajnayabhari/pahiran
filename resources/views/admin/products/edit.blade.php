@extends('layouts.admin')
@section('title', 'Edit Product — Pahiran Admin')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">Edit Product: {{ $product->name }}</h1>
@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
</div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="max-w-2xl space-y-6">
    @csrf
    @method('PUT')
    
    <div class="bg-white rounded-xl p-6 border border-stone-200 space-y-4">
        <h2 class="font-semibold text-stone-900">Product Ownership</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Seller Shop <span class="text-red-500">*</span></label>
                <select name="seller_id" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select a seller...</option>
                    @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" {{ old('seller_id', $product->seller_id) == $seller->id ? 'selected' : '' }}>
                        {{ $seller->shop_name }} ({{ $seller->name }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select a category...</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border border-stone-200 space-y-4">
        <h2 class="font-semibold text-stone-900">Product Details</h2>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Product Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" value="{{ old('name', $product->name) }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Base Price (Rs.) <span class="text-red-500">*</span></label>
                <input type="number" name="base_price" step="0.01" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" value="{{ old('base_price', $product->base_price) }}">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1">Replace Image (Optional)</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-stone-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-stone-100 file:text-stone-700">
                @if($product->featured_image)
                    <p class="text-xs text-stone-500 mt-2">Current image is uploaded. Selecting a new file will replace it.</p>
                @endif
            </div>
            <div class="col-span-3 pt-2">
                <label class="flex items-center space-x-2 text-sm text-stone-700 font-medium">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-stone-300 text-stone-900 focus:ring-stone-900" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <span>Product is Active and Visible</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Variants editing logic not typically done in main edit form depending on UX needed, but displaying a notice -->
    <div class="bg-stone-50 rounded-xl p-6 border border-stone-200">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-semibold text-stone-900">Variants ({{ $product->variants->count() }})</h2>
        </div>
        <p class="text-sm text-stone-500">Notice: Existing variants cannot be modified from this screen right now. Feature pending. The product has {{ $product->variants->count() }} active variations.</p>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-xl font-semibold text-sm text-stone-600 hover:bg-stone-100 transition">Cancel</a>
        <button type="submit" class="bg-stone-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition flex-1">Save Changes</button>
    </div>
</form>
@endsection
