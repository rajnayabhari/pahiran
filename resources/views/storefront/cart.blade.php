@extends('layouts.storefront')
@section('title', 'Cart — Pahiran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="font-display text-3xl font-bold text-stone-900 mb-8">Shopping Cart</h1>

    @if(count($cartItems) > 0)
    <div class="space-y-4 mb-8">
        @foreach($cartItems as $item)
        <div class="bg-white rounded-xl p-4 sm:p-6 flex items-center gap-4 border border-stone-200" id="cart-item-{{ $item['key'] }}">
            {{-- Image --}}
            <div class="w-20 h-24 bg-stone-200 rounded-lg overflow-hidden shrink-0">
                @if($item['product']->featured_image)
                    <img src="{{ url('storage/' . $item['product']->featured_image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-stone-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex-1 min-w-0">
                <h3 class="font-medium text-stone-900 text-sm">{{ $item['product']->name }}</h3>
                <p class="text-xs text-stone-500 mt-1">{{ $item['product']->seller->shop_name }}</p>
                @if($item['variant'])
                    <p class="text-xs text-stone-500">
                        {{ $item['variant']->size ? 'Size: ' . $item['variant']->size : '' }}
                        {{ $item['variant']->color ? '/ Color: ' . $item['variant']->color : '' }}
                    </p>
                @endif
                <p class="text-amber-700 font-semibold text-sm mt-1">Rs. {{ number_format($item['price']) }}</p>
            </div>

            {{-- Quantity --}}
            <form method="POST" action="{{ route('cart.update', $item['key']) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border border-stone-300 rounded-lg py-1 text-sm">
                <button type="submit" class="text-xs text-stone-500 hover:text-stone-700 underline">Update</button>
            </form>

            {{-- Subtotal --}}
            <p class="font-semibold text-stone-900 text-sm shrink-0 hidden sm:block">Rs. {{ number_format($item['subtotal']) }}</p>

            {{-- Remove --}}
            <form method="POST" action="{{ route('cart.remove', $item['key']) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-stone-400 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    {{-- Cart Summary --}}
    <div class="bg-white rounded-xl p-6 border border-stone-200">
        <div class="flex justify-between items-center mb-4">
            <span class="text-stone-600">Subtotal</span>
            <span class="font-bold text-lg text-stone-900">Rs. {{ number_format($total) }}</span>
        </div>
        <p class="text-xs text-stone-500 mb-6">Shipping calculated at checkout</p>
        <a href="{{ route('checkout.index') }}" class="block w-full bg-stone-900 text-white text-center py-4 rounded-xl font-semibold text-sm hover:bg-stone-800 transition" id="proceed-to-checkout">
            Proceed to Checkout
        </a>
        <a href="{{ route('home') }}" class="block text-center text-sm text-stone-500 hover:text-stone-700 mt-3 transition">Continue Shopping</a>
    </div>
    @else
    <div class="text-center py-20">
        <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <p class="text-stone-500 mb-4">Your cart is empty</p>
        <a href="{{ route('home') }}" class="inline-flex items-center bg-stone-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
