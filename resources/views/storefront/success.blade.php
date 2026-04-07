@extends('layouts.storefront')
@section('title', 'Order Confirmed — Pahiran')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <div class="bg-emerald-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>

    <h1 class="font-display text-3xl font-bold text-stone-900 mb-3">Order Confirmed!</h1>
    <p class="text-stone-500 mb-8">Thank you for shopping with Pahiran.</p>

    @if($payment && $payment->order)
    <div class="bg-white rounded-xl p-6 border border-stone-200 text-left mb-8">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-stone-500">Order Number</span>
                <p class="font-semibold text-stone-900">{{ $payment->order->order_number }}</p>
            </div>
            <div>
                <span class="text-stone-500">Order Total</span>
                <p class="font-semibold text-stone-900">Rs. {{ number_format($payment->amount) }}</p>
            </div>
            <div>
                <span class="text-stone-500">Payment Method</span>
                <p class="font-semibold text-stone-900 uppercase">{{ $payment->payment_method }}</p>
            </div>
            <div>
                <span class="text-stone-500">Payment Status</span>
                <p class="font-semibold text-emerald-600 capitalize">{{ $payment->status }}</p>
            </div>
            @if($payment->transaction_id)
            <div>
                <span class="text-stone-500">Transaction ID</span>
                <p class="font-semibold text-stone-900 text-xs">{{ $payment->transaction_id }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <a href="{{ route('home') }}" class="inline-flex items-center bg-stone-900 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-stone-800 transition">
        Continue Shopping
    </a>
</div>
@endsection
