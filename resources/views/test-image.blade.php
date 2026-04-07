@extends('layouts.storefront')
@section('title', 'Image Test — Pahiran')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Image Test</h1>
    
    @php
        $product = App\Models\Product::first();
        $imageSrc = $product->featured_image 
            ? (Str::startsWith($product->featured_image, 'http') ? $product->featured_image : asset('storage/' . $product->featured_image))
            : null;
    @endphp
    
    <div class="space-y-4">
        <p><strong>Product:</strong> {{ $product->name }}</p>
        <p><strong>Featured Image:</strong> {{ $product->featured_image }}</p>
        <p><strong>Image URL:</strong> {{ $imageSrc }}</p>
        <p><strong>File Exists:</strong> {{ file_exists(public_path('storage/' . $product->featured_image)) ? 'YES' : 'NO' }}</p>
        
        <div class="border-2 border-dashed border-gray-300 p-4">
            @if($imageSrc)
                <img src="{{ $imageSrc }}" alt="{{ $product->name }}" style="max-width: 300px; height: auto;" onerror="console.error('Image failed to load:', this.src);">
                <p class="mt-2 text-sm text-gray-600">Image above should load if everything works correctly.</p>
            @else
                <p class="text-red-600">No image found</p>
            @endif
        </div>
    </div>
</div>
@endsection
