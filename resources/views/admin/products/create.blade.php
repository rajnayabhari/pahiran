@extends('layouts.admin')
@section('title', 'Add Product — Pahiran Admin')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">Create New Product</h1>
@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
</div>
@endif

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-6" x-data="{ variants: [{ size: '', color: '', sku: '', price: '', stock: 0 }] }">
    @csrf
    <div class="bg-white rounded-xl p-6 border border-stone-200 space-y-4">
        <h2 class="font-semibold text-stone-900">Product Ownership</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Seller Shop <span class="text-red-500">*</span></label>
                <select name="seller_id" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select a seller...</option>
                    @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
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
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
            <input type="text" name="name" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" value="{{ old('name') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Base Price (Rs.) <span class="text-red-500">*</span></label>
                <input type="number" name="base_price" step="0.01" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" value="{{ old('base_price') }}">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-stone-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-stone-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-stone-100 file:text-stone-700">
            </div>
            <div class="col-span-3 pt-2">
                <label class="flex items-center space-x-2 text-sm text-stone-700 font-medium">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-stone-300 text-stone-900 focus:ring-stone-900" checked>
                    <span>Product is Active and Visible</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border border-stone-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-stone-900">Variants (e.g. Size, Color)</h2>
            <button type="button" @click="variants.push({ size: '', color: '', sku: '', price: '', stock: 0 })" class="text-sm font-medium text-stone-900 hover:underline">+ Add Variant</button>
        </div>
        <template x-for="(variant, index) in variants" :key="index">
            <div class="grid grid-cols-5 gap-3 mb-3 items-end">
                <div>
                    <label class="block text-xs text-stone-500 mb-1">Size</label>
                    <input type="text" :name="'variants['+index+'][size]'" x-model="variant.size" class="w-full border border-stone-300 rounded-lg px-2 py-1.5 text-sm" placeholder="M, L, XL">
                </div>
                <div>
                    <label class="block text-xs text-stone-500 mb-1">Color</label>
                    <input type="text" :name="'variants['+index+'][color]'" x-model="variant.color" class="w-full border border-stone-300 rounded-lg px-2 py-1.5 text-sm" placeholder="Red, Blue">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">SKU *</label>
                    <input type="text" :name="'variants['+index+'][sku]'" x-model="variant.sku" required class="w-full border border-stone-300 rounded-lg px-2 py-1.5 text-sm" placeholder="Unique ID">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Add Price *</label>
                    <input type="number" :name="'variants['+index+'][price]'" x-model="variant.price" step="0.01" required class="w-full border border-stone-300 rounded-lg px-2 py-1.5 text-sm" placeholder="Rs.">
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Stock *</label>
                        <input type="number" :name="'variants['+index+'][stock]'" x-model="variant.stock" required class="w-full border border-stone-300 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <button type="button" @click="variants.splice(index, 1)" x-show="variants.length > 1" class="text-red-400 hover:text-red-600 pb-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-xl font-semibold text-sm text-stone-600 hover:bg-stone-100 transition">Cancel</a>
        <button type="submit" class="bg-stone-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition flex-1">Create Product</button>
    </div>
</form>
@endsection
