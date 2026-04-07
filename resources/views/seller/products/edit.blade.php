@extends('layouts.seller')
@section('title', 'Edit Product — Pahiran')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">Edit: {{ $product->name }}</h1>
@if($errors->any())<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif

<form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" class="max-w-2xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl p-6 border border-stone-200 space-y-4">
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Name</label>
            <input type="text" name="name" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" value="{{ old('name', $product->name) }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Category</label>
            <select name="category_id" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Description</label>
            <textarea name="description" rows="4" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Base Price (Rs.)</label>
                <input type="number" name="base_price" step="0.01" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm" value="{{ old('base_price', $product->base_price) }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-stone-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-stone-100 file:text-stone-700">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded">
            Active
        </label>
    </div>
    <button type="submit" class="bg-stone-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition w-full">Update Product</button>
</form>
@endsection
