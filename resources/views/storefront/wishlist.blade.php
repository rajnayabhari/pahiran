@extends('layouts.storefront')
@section('title', 'My Wishlist — Pahiran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10">
        <h1 class="font-display text-4xl font-bold text-stone-900">My Wishlist</h1>
        <p class="text-stone-500 mt-2">Saved items you're keeping an eye on.</p>
    </div>

    @if($wishlistedProducts->count())
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($wishlistedProducts as $product)
        <div class="group relative" id="product-{{ $product->id }}">
            <a href="{{ route('product.show', $product->slug) }}">
                <div class="aspect-[3/4] bg-stone-200 rounded-2xl overflow-hidden mb-3 relative">
                    @if($product->featured_image)
                        <img src="{{ url('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-stone-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
            </a>
            
            {{-- Wishlist Toggle --}}
            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3">
                @csrf
                <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-sm hover:bg-white transition text-red-500">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
            </form>

            <div class="relative">
                <p class="text-xs text-stone-400 mb-1">{{ $product->seller->shop_name }}</p>
                <h3 class="font-medium text-stone-900 text-sm leading-tight group-hover:text-amber-700 transition">{{ $product->name }}</h3>
                <p class="text-amber-700 font-semibold text-sm mt-1">Rs. {{ number_format($product->base_price) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-24 bg-stone-50 rounded-3xl border border-dashed border-stone-200">
        <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
            <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <h2 class="text-xl font-semibold text-stone-900 mb-2">Your wishlist is empty</h2>
        <p class="text-stone-500 mb-8">Save items you love to find them easily later.</p>
        <a href="{{ route('home') }}" class="inline-flex items-center bg-stone-900 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
