@extends('layouts.storefront')
@section('title', 'Pahiran — Premium Fashion')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-stone-900 text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-stone-900 via-stone-800 to-amber-900/30"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-36">
        <div class="max-w-2xl">
            <p class="text-amber-400 text-sm font-medium uppercase tracking-widest mb-4">New Collection</p>
            <h1 class="font-display text-5xl md:text-7xl font-bold leading-tight mb-6">
                Redefine Your <span class="text-amber-400">Style</span>
            </h1>
            <p class="text-stone-300 text-lg leading-relaxed mb-8">
                Discover curated fashion from Nepal's best independent sellers. Unique pieces, ethically crafted.
            </p>
            <a href="#featured" class="inline-flex items-center bg-white text-stone-900 px-8 py-3 rounded-lg font-semibold text-sm hover:bg-stone-100 transition shadow-lg">
                Shop Now
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Categories Section --}}
@if($categories->count())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="font-display text-3xl font-bold text-stone-900">Shop by Category</h2>
        <p class="text-stone-500 mt-2">Find exactly what you're looking for</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('category.show', $category->slug) }}" class="group relative bg-stone-200 rounded-2xl overflow-hidden aspect-square flex items-end p-6 hover:shadow-xl transition-all duration-300">
            @if($category->image)
                <img src="{{ Str::startsWith($category->image, 'http') ? $category->image : url('storage/' . $category->image) }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-stone-900/80 via-stone-900/20 to-transparent"></div>
            <div class="relative">
                <h3 class="text-white font-semibold text-lg">{{ $category->name }}</h3>
                <p class="text-stone-300 text-sm mt-1 opacity-0 group-hover:opacity-100 transition">Shop now →</p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- Featured Products --}}
<section id="featured" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="font-display text-3xl font-bold text-stone-900">Featured Products</h2>
        <p class="text-stone-500 mt-2">Hand-picked styles just for you</p>
    </div>

    @if($featuredProducts->count())
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="group relative" id="product-{{ $product->id }}">
            <a href="{{ route('product.show', $product->slug) }}">
                <div class="aspect-[3/4] bg-stone-200 rounded-2xl overflow-hidden mb-3 relative">
                    @if($product->featured_image)
                        <img src="{{ Str::startsWith($product->featured_image, 'http') ? $product->featured_image : url('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-stone-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/90 backdrop-blur-sm text-xs font-medium text-stone-700 px-2 py-1 rounded-full">{{ $product->category->name ?? '' }}</span>
                    </div>
                </div>
            </a>
            
            {{-- Wishlist Toggle --}}
            @auth
            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3">
                @csrf
                @php $inWishlist = auth()->user()->wishlist->contains($product->id); @endphp
                <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-sm hover:bg-white transition {{ $inWishlist ? 'text-red-500' : 'text-stone-400 hover:text-red-500' }}">
                    <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </form>
            @endauth

            <div class="relative">
                <p class="text-xs text-stone-400 mb-1">{{ $product->seller->shop_name }}</p>
                <h3 class="font-medium text-stone-900 text-sm leading-tight group-hover:text-amber-700 transition">{{ $product->name }}</h3>
                <p class="text-amber-700 font-semibold text-sm mt-1">Rs. {{ number_format($product->base_price) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 text-stone-400">
        <p>No products available yet. Check back soon!</p>
    </div>
    @endif
</section>

{{-- CTA Section --}}
<section class="bg-stone-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">Start Selling on Pahiran</h2>
        <p class="text-stone-400 max-w-lg mx-auto mb-8">Join our community of independent sellers. Reach thousands of fashion-forward customers across Nepal.</p>
        <a href="{{ route('seller.register') }}" class="inline-flex items-center bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold text-sm hover:bg-amber-700 transition">
            Become a Seller
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>

@endsection
