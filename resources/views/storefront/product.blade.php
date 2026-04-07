@extends('layouts.storefront')
@section('title', $product->name . ' — Pahiran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
    selectedSize: '',
    selectedColor: '',
    selectedVariant: null,
    quantity: 1,
    variants: {{ $product->variants->toJson() }},
    get currentPrice() {
        if (this.selectedVariant) return this.selectedVariant.price;
        return {{ $product->base_price }};
    },
    get inStock() {
        if (this.selectedVariant) return this.selectedVariant.stock > 0;
        return true;
    },
    selectVariant() {
        this.selectedVariant = this.variants.find(v =>
            (v.size === this.selectedSize || !this.selectedSize) &&
            (v.color === this.selectedColor || !this.selectedColor)
        ) || null;
    }
}">

    {{-- Breadcrumb --}}
    <nav class="text-xs font-medium uppercase tracking-wider text-stone-400 mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('home') }}" class="hover:text-stone-900 transition">Home</a></li>
            <li class="flex items-center space-x-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @if($product->category)
                    <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-stone-900 transition">{{ $product->category->name }}</a>
                @endif
            </li>
            <li class="flex items-center space-x-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-stone-900">{{ $product->name }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20">
        {{-- Product Image Gallery (Desktop Left) --}}
        <div class="lg:col-span-7">
            <div class="sticky top-24 space-y-4">
                <div class="aspect-[3/4] bg-stone-100 rounded-3xl overflow-hidden relative group">
                    @php 
                        $imageSrc = $product->featured_image 
                            ? (Str::startsWith($product->featured_image, 'http') ? $product->featured_image : url('storage/' . $product->featured_image))
                            : null;
                    @endphp

                    @if($imageSrc)
                        <img src="{{ $imageSrc }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-stone-300">
                             <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    
                    {{-- Badges --}}
                    <div class="absolute top-6 left-6 flex flex-col gap-2">
                        <span class="bg-white/90 backdrop-blur-md text-stone-900 text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm">{{ $product->category->name ?? 'Collection' }}</span>
                        @if(!$product->is_active)
                            <span class="bg-red-500 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Info (Desktop Right) --}}
        <div class="lg:col-span-5">
            <div class="pb-8 border-b border-stone-200">
                <a href="#" class="text-amber-700 text-xs font-bold uppercase tracking-[0.2em] mb-4 block hover:text-amber-800 transition">
                    {{ $product->seller->shop_name }}
                </a>
                <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 leading-tight mb-4">
                    {{ $product->name }}
                </h1>
                <div class="flex items-baseline space-x-4">
                    <p class="text-3xl font-light text-stone-900">
                        Rs. <span x-text="parseFloat(currentPrice).toLocaleString()">{{ number_format($product->base_price) }}</span>
                    </p>
                    @if(isset($product->old_price))
                        <p class="text-lg text-stone-400 line-through">Rs. {{ number_format($product->old_price) }}</p>
                    @endif
                </div>
            </div>

            <div class="py-8 space-y-8">
                {{-- Description --}}
                <div class="prose prose-stone prose-sm max-w-none text-stone-600 leading-relaxed">
                    <p>{{ $product->description }}</p>
                </div>

                {{-- Variant Selection --}}
                <div class="space-y-6">
                    {{-- Size Selector --}}
                    @php $sizes = $product->variants->pluck('size')->unique()->filter(); @endphp
                    @if($sizes->count())
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Select Size</label>
                            <button class="text-[10px] font-bold uppercase tracking-widest text-amber-700 hover:underline">Size Guide</button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                            <button
                                @click="selectedSize = '{{ $size }}'; selectVariant()"
                                :class="selectedSize === '{{ $size }}' ? 'bg-stone-900 text-white border-stone-900 scale-105 shadow-md' : 'bg-white text-stone-700 border-stone-200 hover:border-stone-900'"
                                class="w-12 h-12 rounded-xl border flex items-center justify-center text-sm font-semibold transition-all duration-300">
                                {{ $size }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Color Selector --}}
                    @php $colors = $product->variants->pluck('color')->unique()->filter(); @endphp
                    @if($colors->count())
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-3">Select Color</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($colors as $color)
                            <button
                                @click="selectedColor = '{{ $color }}'; selectVariant()"
                                class="group flex flex-col items-center space-y-2">
                                <div :class="selectedColor === '{{ $color }}' ? 'ring-2 ring-stone-900 ring-offset-2 scale-110' : 'hover:scale-105'"
                                     class="w-8 h-8 rounded-full border border-stone-200 transition-all duration-300 shadow-sm overflow-hidden bg-stone-100 flex items-center justify-center">
                                    <span class="text-[8px] font-bold text-stone-500">{{ substr($color, 0, 1) }}</span>
                                </div>
                                <span class="text-[9px] font-medium text-stone-500 uppercase tracking-tighter">{{ $color }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border border-stone-200 rounded-xl px-4 py-2 bg-stone-50">
                            <button @click="quantity = Math.max(1, quantity - 1)" class="p-1 hover:text-amber-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <input type="text" x-model="quantity" readonly class="w-12 text-center bg-transparent border-none focus:ring-0 font-semibold text-stone-900">
                            <button @click="quantity++" class="p-1 hover:text-amber-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                        <div class="flex-1 flex gap-3">
                            <form method="POST" action="{{ route('cart.add') }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">
                                <input type="hidden" name="quantity" :value="quantity">
                                <button type="submit" 
                                        :disabled="!inStock"
                                        class="w-full bg-stone-900 text-white py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-stone-800 transition-all duration-300 shadow-xl shadow-stone-900/10 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="inStock">Add to Cart</span>
                                    <span x-show="!inStock">Out of Stock</span>
                                </button>
                            </form>
                            @auth
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                @csrf
                                @php $inWishlist = auth()->user()->wishlist->contains($product->id); @endphp
                                <button type="submit" 
                                        class="h-full px-5 rounded-2xl border flex items-center justify-center transition-all duration-300 {{ $inWishlist ? 'bg-red-50 border-red-100 text-red-500' : 'bg-white border-stone-200 text-stone-400 hover:border-stone-900 hover:text-stone-900' }}">
                                    <svg class="w-6 h-6 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </form>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Trust Signals --}}
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-stone-100">
                    <div class="text-center group">
                        <div class="w-10 h-10 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-amber-50 transition-colors">
                            <svg class="w-5 h-5 text-stone-400 group-hover:text-amber-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-stone-500">Fast Delivery</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-10 h-10 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-amber-50 transition-colors">
                            <svg class="w-5 h-5 text-stone-400 group-hover:text-amber-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-stone-500">Handmade</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-10 h-10 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-amber-50 transition-colors">
                            <svg class="w-5 h-5 text-stone-400 group-hover:text-amber-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-stone-500">Secure Pay</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Tabs/Tabs Mockup --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 py-16 border-t border-stone-200">
        <div>
            <h3 class="font-display text-2xl font-bold text-stone-900 mb-6">Product Details</h3>
            <div class="space-y-4 text-sm text-stone-600">
                <p>Authentic craftsmanship meets modern aesthetics. This piece is curated for those who value quality and tradition.</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Premium blended materials</li>
                    <li>Traditional patterns</li>
                    <li>Ethically sourced</li>
                    <li>Comfortable fit</li>
                </ul>
            </div>
        </div>
        <div>
            <h3 class="font-display text-2xl font-bold text-stone-900 mb-6">Delivery & Returns</h3>
            <div class="space-y-4 text-sm text-stone-600">
                <p>We deliver across Nepal. Estimated delivery time: 2-5 business days.</p>
                <div class="bg-stone-50 p-4 rounded-2xl">
                    <p class="font-semibold text-stone-900 mb-1">Easy Returns</p>
                    <p>7-day return policy for unused items in original packaging.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count())
    <section class="mt-12 py-16 border-t border-stone-200">
        <div class="flex items-center justify-between mb-10">
            <h2 class="font-display text-3xl font-bold text-stone-900">You May Also Like</h2>
            <a href="{{ route('category.show', $product->category->slug) }}" class="text-xs font-bold uppercase tracking-widest text-amber-700 hover:text-amber-800 transition">View All →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach($relatedProducts as $related)
            <div class="group relative">
                <a href="{{ route('product.show', $related->slug) }}">
                    <div class="aspect-[3/4] bg-stone-100 rounded-2xl overflow-hidden mb-4 relative">
                        @php 
                            $relatedImg = $related->featured_image 
                                ? (Str::startsWith($related->featured_image, 'http') ? $related->featured_image : url('storage/' . $related->featured_image))
                                : null;
                        @endphp
                        @if($relatedImg)
                            <img src="{{ $relatedImg }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-stone-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                </a>
                
                @auth
                <form action="{{ route('wishlist.toggle', $related->id) }}" method="POST" class="absolute top-4 right-4">
                    @csrf
                    @php $inRelatedWishlist = auth()->user()->wishlist->contains($related->id); @endphp
                    <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-sm hover:bg-white transition {{ $inRelatedWishlist ? 'text-red-500' : 'text-stone-400 hover:text-red-500' }}">
                        <svg class="w-4 h-4 {{ $inRelatedWishlist ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </form>
                @endauth
                
                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1">{{ $related->seller->shop_name }}</p>
                <h3 class="font-medium text-stone-900 text-sm mb-1 group-hover:text-amber-700 transition">{{ $related->name }}</h3>
                <p class="text-stone-900 font-semibold text-sm">Rs. {{ number_format($related->base_price) }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

{{-- Sticky Bottom Bar for Mobile --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-stone-200 p-4 z-40 transition-transform duration-300" 
     x-data="{ show: false }" 
     x-init="window.addEventListener('scroll', () => show = window.scrollY > 500)"
     :class="show ? 'translate-y-0' : 'translate-y-full'">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400 truncate w-32">{{ $product->name }}</p>
            <p class="font-bold text-stone-900">Rs. <span x-text="parseFloat(currentPrice).toLocaleString()"></span></p>
        </div>
        <button @click="document.getElementById('add-to-cart-form').scrollIntoView({behavior: 'smooth'})" class="flex-1 bg-stone-900 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg">
            Add to Cart
        </button>
    </div>
</div>

@endsection
