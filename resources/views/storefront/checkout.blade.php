@extends('layouts.storefront')
@section('title', 'Checkout — Pahiran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="font-display text-3xl font-bold text-stone-900 mb-8">Checkout</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 md:grid-cols-3 gap-8" id="checkout-form" x-data="{ paymentMethod: 'khalti' }">
        @csrf

        {{-- Shipping Info --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h2 class="font-semibold text-stone-900 text-lg mb-4">Shipping Information</h2>

                <div class="space-y-4">
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-stone-700 mb-1">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" placeholder="Full address including city, district...">{{ old('shipping_address') }}</textarea>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-stone-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" placeholder="98XXXXXXXX" value="{{ old('phone') }}">
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-stone-700 mb-1">Order Notes <span class="text-stone-400">(optional)</span></label>
                        <textarea name="notes" id="notes" rows="2" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h2 class="font-semibold text-stone-900 text-lg mb-4">Payment Method</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition" :class="paymentMethod === 'khalti' ? 'border-purple-600 bg-purple-50' : 'border-stone-200 hover:border-stone-300'">
                        <input type="radio" name="payment_method" value="khalti" x-model="paymentMethod" class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-stone-900">Khalti</span>
                            <span class="block text-xs text-stone-500">Pay via Khalti SDK/Wallet</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition" :class="paymentMethod === 'cod' ? 'border-amber-600 bg-amber-50' : 'border-stone-200 hover:border-stone-300'">
                        <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-stone-900">Cash on Delivery</span>
                            <span class="block text-xs text-stone-500">Pay when you receive items</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div>
            <div class="bg-white rounded-xl p-6 border border-stone-200 sticky top-24">
                <h2 class="font-semibold text-stone-900 text-lg mb-4">Order Summary</h2>

                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-600">{{ Str::limit($item['product']->name, 25) }} × {{ $item['quantity'] }}</span>
                        <span class="font-medium text-stone-900">Rs. {{ number_format($item['subtotal']) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-stone-200 pt-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-stone-900">Total</span>
                        <span class="font-bold text-xl text-stone-900">Rs. {{ number_format($total) }}</span>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full text-white py-4 rounded-xl font-semibold text-sm transition shadow-lg flex items-center justify-center gap-2"
                    :class="paymentMethod === 'khalti' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-stone-900 hover:bg-stone-800'">
                    <span x-text="paymentMethod === 'khalti' ? 'Pay with Khalti' : 'Place Order (COD)'"></span>
                    — Rs. {{ number_format($total) }}
                </button>

                <p class="text-xs text-stone-400 text-center mt-3" x-show="paymentMethod === 'khalti'">You will be redirected to Khalti to complete payment</p>
                <p class="text-xs text-stone-400 text-center mt-3" x-show="paymentMethod === 'cod'">Your order will be processed immediately</p>
            </div>
        </div>
    </form>
</div>
@endsection
