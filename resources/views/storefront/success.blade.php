@extends('layouts.storefront')
@section('title', 'Order Confirmed — Pahiran')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    {{-- Success Icon --}}
    <div class="relative inline-flex mb-6">
        <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center animate-[bounce_1s_ease-in-out]">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    </div>

    <h1 class="font-display text-3xl font-bold text-stone-900 mb-2">Order Confirmed!</h1>
    <p class="text-stone-500 mb-8">Thank you for shopping with Pahiran.</p>

    @if($payment && $payment->order)
    <div class="bg-white rounded-xl p-6 border border-stone-200 text-left mb-8 shadow-sm">

        {{-- Payment Method Banner --}}
        @if($payment->payment_method === 'esewa')
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-stone-100">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 11h16v2H4zm0-6h16v2H4zm0 12h16v2H4z"/>
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                </svg>
                <span class="text-sm font-bold text-green-700">Paid with eSewa</span>
                @if($payment->status === 'completed')
                    <span class="ml-auto text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">✓ Verified</span>
                @else
                    <span class="ml-auto text-xs bg-amber-100 text-amber-700 font-semibold px-2.5 py-1 rounded-full capitalize">{{ $payment->status }}</span>
                @endif
            </div>
        @else
            <div class="flex items-center gap-2 mb-5 pb-4 border-b border-stone-100">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-sm font-bold text-amber-700">Cash on Delivery</span>
                <span class="ml-auto text-xs bg-amber-100 text-amber-700 font-semibold px-2.5 py-1 rounded-full">Pay on arrival</span>
            </div>
        @endif

        {{-- Order Details Grid --}}
        <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <span class="text-stone-400 text-xs uppercase tracking-wider">Order Number</span>
                <p class="font-bold text-stone-900 mt-0.5">{{ $payment->order->order_number }}</p>
            </div>
            <div>
                <span class="text-stone-400 text-xs uppercase tracking-wider">Amount</span>
                <p class="font-bold text-stone-900 mt-0.5">Rs. {{ number_format($payment->amount) }}</p>
            </div>
            <div>
                <span class="text-stone-400 text-xs uppercase tracking-wider">Order Status</span>
                <p class="font-bold text-stone-900 capitalize mt-0.5">{{ $payment->order->status }}</p>
            </div>
            <div>
                <span class="text-stone-400 text-xs uppercase tracking-wider">Payment Status</span>
                <p class="font-bold capitalize mt-0.5
                    {{ $payment->status === 'completed' ? 'text-emerald-600' : '' }}
                    {{ $payment->status === 'pending' ? 'text-amber-600' : '' }}
                    {{ $payment->status === 'failed' ? 'text-red-600' : '' }}
                ">{{ $payment->status }}</p>
            </div>
            @if($payment->transaction_id)
            <div class="col-span-2">
                <span class="text-stone-400 text-xs uppercase tracking-wider">eSewa Transaction ID</span>
                <p class="font-mono font-semibold text-stone-700 text-xs mt-0.5 break-all">{{ $payment->transaction_id }}</p>
            </div>
            @endif
            @if($payment->paid_at)
            <div>
                <span class="text-stone-400 text-xs uppercase tracking-wider">Paid At</span>
                <p class="font-semibold text-stone-700 text-xs mt-0.5">{{ $payment->paid_at->format('d M Y, h:i A') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- What happens next --}}
    <div class="bg-stone-50 rounded-xl p-5 border border-stone-100 text-left mb-8">
        <h3 class="text-sm font-semibold text-stone-900 mb-3">What happens next?</h3>
        <ol class="text-xs text-stone-600 space-y-2 list-decimal list-inside">
            @if($payment->payment_method === 'esewa' && $payment->status === 'completed')
                <li>Your payment has been confirmed.</li>
                <li>The seller will process and pack your order.</li>
                <li>You'll receive tracking information once shipped.</li>
            @elseif($payment->payment_method === 'cod')
                <li>Your order has been received by the seller.</li>
                <li>The seller will pack and dispatch your order.</li>
                <li>Have cash ready — you'll pay on delivery.</li>
            @else
                <li>Your payment is being processed.</li>
                <li>You will be notified once payment is confirmed.</li>
                <li>The seller will then process your order.</li>
            @endif
        </ol>
    </div>
    @endif

    <a href="{{ route('home') }}" class="inline-flex items-center bg-stone-900 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition shadow-sm gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Continue Shopping
    </a>
</div>
@endsection
