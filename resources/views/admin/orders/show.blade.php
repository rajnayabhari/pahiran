@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number . ' — Admin')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Order {{ $order->order_number }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-stone-500 hover:text-stone-700">← Back</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl border border-stone-200 p-6">
        <h2 class="font-semibold text-stone-900 mb-4">Order Items</h2>
        <table class="w-full text-sm">
            <thead class="text-stone-500"><tr><th class="text-left py-2">Product</th><th class="text-left py-2">Seller</th><th class="text-right py-2">Qty</th><th class="text-right py-2">Price</th><th class="text-right py-2">Commission</th></tr></thead>
            <tbody class="divide-y divide-stone-100">
                @foreach($order->items as $item)
                <tr>
                    <td class="py-2 font-medium">{{ $item->product->name }}@if($item->variant) <span class="text-xs text-stone-500">({{ $item->variant->size }}/{{ $item->variant->color }})</span>@endif</td>
                    <td class="py-2 text-stone-600">{{ $item->seller->shop_name }}</td>
                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                    <td class="py-2 text-right">Rs. {{ number_format($item->unit_price * $item->quantity) }}</td>
                    <td class="py-2 text-right text-amber-600">Rs. {{ number_format($item->commission) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-stone-200">
                <tr><td colspan="3" class="py-3 font-semibold">Total</td><td class="py-3 text-right font-bold">Rs. {{ number_format($order->total_amount) }}</td><td class="py-3 text-right font-bold text-amber-600">Rs. {{ number_format($order->commission_amount) }}</td></tr>
            </tfoot>
        </table>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-stone-200 p-6">
            <h2 class="font-semibold text-stone-900 mb-3">Customer</h2>
            <p class="text-sm">{{ $order->user->name }}</p>
            <p class="text-sm text-stone-500">{{ $order->user->email }}</p>
            <p class="text-sm text-stone-500">{{ $order->phone }}</p>
            <p class="text-sm text-stone-500 mt-2">{{ $order->shipping_address }}</p>
        </div>
        @if($order->payment)
        <div class="bg-white rounded-xl border border-stone-200 p-6">
            <h2 class="font-semibold text-stone-900 mb-3">Payment</h2>
            <p class="text-sm"><span class="text-stone-500">Method:</span> {{ ucfirst($order->payment->payment_method) }}</p>
            <p class="text-sm"><span class="text-stone-500">Status:</span> <span class="{{ $order->payment->status === 'completed' ? 'text-emerald-600' : 'text-amber-600' }} font-medium">{{ ucfirst($order->payment->status) }}</span></p>
            @if($order->payment->transaction_id)<p class="text-xs text-stone-400 mt-1">{{ $order->payment->transaction_id }}</p>@endif
        </div>
        @endif
        <div class="bg-white rounded-xl border border-stone-200 p-6">
            <h2 class="font-semibold text-stone-900 mb-3">Update Status</h2>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">@csrf @method('PATCH')
                <select name="status" class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm mb-3">
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-stone-900 text-white py-2 rounded-lg text-sm font-medium hover:bg-stone-800 transition">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
