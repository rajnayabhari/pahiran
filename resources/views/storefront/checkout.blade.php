@extends('layouts.storefront')
@section('title', 'Checkout — Pahiran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="font-display text-3xl font-bold text-stone-900 mb-8">Checkout</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <form
        method="POST"
        action="{{ route('checkout.process') }}"
        id="checkout-form"
        x-data="{
            paymentMethod: 'esewa',
            isSubmitting: false,
            submit() {
                this.isSubmitting = true;
                this.$el.submit();
            }
        }"
        @submit.prevent="submit()"
        class="grid grid-cols-1 md:grid-cols-3 gap-8"
    >
        @csrf

        {{-- LEFT COLUMN: Shipping + Payment --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Shipping Information --}}
            <div class="bg-white rounded-xl p-6 border border-stone-200 shadow-sm">
                <h2 class="font-semibold text-stone-900 text-lg mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-900 text-white text-xs flex items-center justify-center font-bold">1</span>
                    Shipping Information
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-stone-700 mb-1">
                            Shipping Address <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="shipping_address"
                            id="shipping_address"
                            rows="3"
                            required
                            class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                            placeholder="Full address including city, district…"
                        >{{ old('shipping_address') }}</textarea>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-stone-700 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            required
                            pattern="[0-9]{10}"
                            maxlength="10"
                            class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                            placeholder="98XXXXXXXX"
                            value="{{ old('phone') }}"
                        >
                        <p class="text-xs text-stone-400 mt-1">Enter 10-digit Nepali mobile number</p>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-stone-700 mb-1">
                            Order Notes <span class="text-stone-400 font-normal">(optional)</span>
                        </label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="2"
                            maxlength="500"
                            class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                            placeholder="Any special instructions for delivery…"
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-white rounded-xl p-6 border border-stone-200 shadow-sm">
                <h2 class="font-semibold text-stone-900 text-lg mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-900 text-white text-xs flex items-center justify-center font-bold">2</span>
                    Payment Method
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- eSewa Option --}}
                    <label
                        for="payment_esewa"
                        class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 group"
                        :class="paymentMethod === 'esewa'
                            ? 'border-green-600 bg-green-50 shadow-sm'
                            : 'border-stone-200 hover:border-green-300 hover:bg-green-50/30'"
                    >
                        <input
                            type="radio"
                            name="payment_method"
                            id="payment_esewa"
                            value="esewa"
                            x-model="paymentMethod"
                            class="mt-0.5 w-4 h-4 text-green-600 focus:ring-green-500 border-stone-300"
                        >
                        <div class="ml-3 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                {{-- eSewa minimal mark --}}
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 11h16v2H4zm0-6h16v2H4zm0 12h16v2H4z"/>
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                                <span class="text-sm font-bold text-stone-900">eSewa Mobile Wallet</span>
                                <span class="text-[10px] bg-green-100 text-green-700 font-semibold px-1.5 py-0.5 rounded-full">Recommended</span>
                            </div>
                            <span class="block text-xs text-stone-500">Pay securely directly with eSewa</span>
                        </div>
                    </label>

                    {{-- Cash on Delivery Option --}}
                    <label
                        for="payment_cod"
                        class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 group"
                        :class="paymentMethod === 'cod'
                            ? 'border-amber-500 bg-amber-50 shadow-sm'
                            : 'border-stone-200 hover:border-amber-300 hover:bg-amber-50/30'"
                    >
                        <input
                            type="radio"
                            name="payment_method"
                            id="payment_cod"
                            value="cod"
                            x-model="paymentMethod"
                            class="mt-0.5 w-4 h-4 text-amber-600 focus:ring-amber-500 border-stone-300"
                        >
                        <div class="ml-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm font-bold text-stone-900">Cash on Delivery</span>
                            </div>
                            <span class="block text-xs text-stone-500">Pay when you receive your items</span>
                        </div>
                    </label>
                </div>

                {{-- eSewa info banner --}}
                <div
                    x-show="paymentMethod === 'esewa'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-4 flex items-start gap-3 bg-green-50 border border-green-100 rounded-lg px-4 py-3"
                >
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-xs text-green-800">
                        <p class="font-semibold mb-0.5">Secure eSewa Payment</p>
                        <p>You'll be redirected to eSewa's secure payment page to complete your purchase.</p>
                    </div>
                </div>

                <div
                    x-show="paymentMethod === 'cod'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-4 flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3"
                >
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-amber-700">Have exact change ready. Our delivery partner will collect payment when your order arrives.</p>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Order Summary --}}
        <div>
            <div class="bg-white rounded-xl p-6 border border-stone-200 shadow-sm sticky top-24">
                <h2 class="font-semibold text-stone-900 text-lg mb-4">Order Summary</h2>

                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-stone-600">
                            {{ Str::limit($item['product']->name, 22) }}
                            @if($item['variant'])
                                <span class="text-stone-400 text-xs">({{ $item['variant']->size ?? '' }} {{ $item['variant']->color ?? '' }})</span>
                            @endif
                            <span class="text-stone-400">× {{ $item['quantity'] }}</span>
                        </span>
                        <span class="font-medium text-stone-900 ml-2 flex-shrink-0">Rs. {{ number_format($item['subtotal']) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-stone-100 pt-3 mb-2">
                    <div class="flex justify-between items-center text-sm text-stone-500 mb-1">
                        <span>Subtotal</span>
                        <span>Rs. {{ number_format($total) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-stone-500">
                        <span>Shipping</span>
                        <span class="text-emerald-600 font-medium">Free</span>
                    </div>
                </div>

                <div class="border-t border-stone-200 pt-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-stone-900">Total</span>
                        <span class="font-bold text-2xl text-stone-900">Rs. {{ number_format($total) }}</span>
                    </div>
                </div>

                {{-- Submit button --}}
                <button
                    type="submit"
                    id="place-order-btn"
                    :disabled="isSubmitting"
                    class="w-full text-white py-4 rounded-xl font-semibold text-sm transition-all shadow-lg flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    :class="paymentMethod === 'esewa'
                        ? 'bg-green-600 hover:bg-green-700 active:bg-green-800'
                        : 'bg-stone-900 hover:bg-stone-800 active:bg-stone-950'"
                >
                    {{-- Loading spinner --}}
                    <svg
                        x-show="isSubmitting"
                        class="animate-spin w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>

                    {{-- Button text --}}
                    <span x-show="!isSubmitting">
                        <span x-show="paymentMethod === 'esewa'">Pay with eSewa</span>
                        <span x-show="paymentMethod !== 'esewa'">Place Order (COD)</span>
                    </span>
                    <span x-show="isSubmitting">Processing…</span>

                    <span x-show="!isSubmitting">— Rs. {{ number_format($total) }}</span>
                </button>

                <p class="text-xs text-stone-400 text-center mt-3" x-show="paymentMethod === 'esewa' && !isSubmitting">
                    🔒 You will be redirected to eSewa's secure checkout
                </p>
                <p class="text-xs text-stone-400 text-center mt-3" x-show="paymentMethod === 'cod' && !isSubmitting">
                    Your order will be confirmed immediately
                </p>
            </div>
        </div>

    </form>
</div>
@endsection
