@extends('layouts.storefront')
@section('title', 'Search results for ' . ($query ?? 'all') . ' — Pahiran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row gap-12">
        
        {{-- Sidebar --}}
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="sticky top-24 space-y-8">
                <div>
                    <h3 class="font-display text-xl font-bold text-stone-900 mb-6">Categories</h3>
                    <nav class="space-y-1">
                        @foreach($categories as $cat)
                        <div x-data="{ open: false }">
                            <div class="flex items-center justify-between group">
                                <a href="{{ route('category.show', $cat->slug) }}" 
                                   class="flex-1 py-2 text-sm font-medium text-stone-600 hover:text-amber-700 transition">
                                    {{ $cat->name }}
                                </a>
                                @if($cat->children->count())
                                <button @click="open = !open" class="p-2 text-stone-400 hover:text-stone-600 transition">
                                    <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                @endif
                            </div>
                            @if($cat->children->count())
                            <div x-show="open" x-collapse class="pl-4 pb-2 space-y-1">
                                @foreach($cat->children as $child)
                                <a href="{{ route('category.show', $child->slug) }}" class="block py-1 text-xs text-stone-500 hover:text-amber-700 transition">
                                    {{ $child->name }}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </nav>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1">
            <div class="mb-10">
                <h1 class="font-display text-3xl md:text-4xl font-bold text-stone-900 leading-tight">
                    @if($query)
                        Search results for "{{ $query }}"
                    @else
                        Explore our collection
                    @endif
                </h1>
                <p class="text-stone-500 mt-2 text-sm">Found {{ $products->total() }} matching products</p>
            </div>

            @if($products->count())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="group relative" id="product-{{ $product->id }}">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <div class="aspect-[3/4] bg-stone-100 rounded-3xl overflow-hidden mb-4 relative">
                            @php 
                                $imageSrc = $product->featured_image 
                                    ? (Str::startsWith($product->featured_image, 'http') ? $product->featured_image : url('storage/' . $product->featured_image))
                                    : null;
                            @endphp
                            @if($imageSrc)
                                <img src="{{ $imageSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-stone-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                    </a>

                    {{-- Wishlist Toggle --}}
                    @auth
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-4 right-4 focus:outline-none">
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
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1">{{ $product->seller->shop_name }}</p>
                        <h3 class="font-medium text-stone-900 text-sm group-hover:text-amber-700 transition leading-tight">{{ $product->name }}</h3>
                        <p class="text-stone-900 font-semibold text-sm mt-1">Rs. {{ number_format($product->base_price) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $products->links() }}
            </div>

            @else
            <div class="bg-stone-100 rounded-3xl py-20 px-6 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-stone-300 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h2 class="text-2xl font-display font-bold text-stone-900 mb-2">No results found</h2>
                <p class="text-stone-500 max-w-sm mx-auto mb-8 text-sm">We couldn't find anything matching "{{ $query }}". Try checking your spelling or using more general terms.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-stone-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-stone-800 transition">
                    Back to Collection
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
