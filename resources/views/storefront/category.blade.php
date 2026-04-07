@extends('layouts.storefront')
@section('title', $category->name . ' — Pahiran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-stone-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-stone-700">Home</a>
        <span class="mx-2">/</span>
        <span class="text-stone-900">{{ $category->name }}</span>
    </nav>

    <div class="flex flex-col md:flex-row gap-8">
        {{-- Sidebar --}}
        <aside class="md:w-56 shrink-0">
            <h3 class="font-semibold text-stone-900 text-sm uppercase tracking-wider mb-4">Categories</h3>
            <ul class="space-y-2">
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="text-sm {{ $cat->id === $category->id ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-900' }} transition">
                        {{ $cat->name }}
                    </a>
                    @if($cat->children->count())
                    <ul class="ml-4 mt-1 space-y-1">
                        @foreach($cat->children as $child)
                        <li>
                            <a href="{{ route('category.show', $child->slug) }}"
                               class="text-sm {{ $child->id === $category->id ? 'text-amber-700 font-medium' : 'text-stone-500 hover:text-stone-700' }} transition">
                                {{ $child->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Products Grid --}}
        <div class="flex-1">
            <div class="relative bg-stone-900 rounded-3xl overflow-hidden mb-8 aspect-[4/1] md:aspect-[5/1] flex items-center px-10">
                @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-stone-900 via-stone-900/40 to-transparent"></div>
                <div class="relative">
                    <h1 class="font-display text-3xl md:text-5xl font-bold text-white">{{ $category->name }}</h1>
                    <p class="text-stone-300 mt-2 text-sm md:text-base">{{ $products->total() }} premium products carefully curated</p>
                </div>
            </div>

            @if($products->count())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($products as $product)
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
                        <h3 class="font-medium text-stone-900 text-sm group-hover:text-amber-700 transition leading-tight">{{ $product->name }}</h3>
                        <p class="text-amber-700 font-semibold text-sm mt-1">Rs. {{ number_format($product->base_price) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-16 text-stone-400">
                <p>No products in this category yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
