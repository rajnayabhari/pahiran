@extends('layouts.seller')
@section('title', 'Order ' . $order->order_number . ' — Pahiran')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Order {{ $order->order_number }}</h1>
    <a href="{{ route('seller.orders.index') }}" class="text-sm text-stone-500 hover:text-stone-700">← Back to Orders</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl border border-stone-200 p-6">
        <h2 class="font-semibold text-stone-900 mb-4">Items</h2>
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center py-2 border-b border-stone-100">
                <div>
                    <p class="font-medium text-sm">{{ $item->product->name }}</p>
                    @if($item->variant)<p class="text-xs text-stone-500">{{ $item->variant->size }} / {{ $item->variant->color }}</p>@endif
                </div>
                <div class="text-right text-sm">
                    <p>{{ $item->quantity }} × Rs. {{ number_format($item->unit_price) }}</p>
                    <p class="text-xs text-stone-400">Commission: Rs. {{ number_format($item->commission) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-stone-200 p-6">
            <h2 class="font-semibold text-stone-900 mb-3">Customer</h2>
            <p class="text-sm text-stone-600">{{ $order->user->name }}</p>
            <p class="text-sm text-stone-500">{{ $order->phone }}</p>
            <p class="text-sm text-stone-500 mt-2">{{ $order->shipping_address }}</p>
        </div>

        <div class="bg-white rounded-xl border border-stone-200 p-6">
            <h2 class="font-semibold text-stone-900 mb-3">Update Status</h2>
            <form method="POST" action="{{ route('seller.orders.status', $order) }}">
                @csrf @method('PATCH')
                <select name="status" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm mb-3">
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
                <button type="submit" class="w-full bg-stone-900 text-white py-2 rounded-lg text-sm font-medium hover:bg-stone-800 transition">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
