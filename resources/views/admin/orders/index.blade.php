@extends('layouts.admin')
@section('title', 'All Orders — Pahiran Admin')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">All Orders</h1>
<div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500"><tr><th class="px-5 py-3 text-left">Order</th><th class="px-5 py-3 text-left">Customer</th><th class="px-5 py-3 text-left">Sellers</th><th class="px-5 py-3 text-left">Status</th><th class="px-5 py-3 text-right">Total</th><th class="px-5 py-3 text-right">Commission</th><th class="px-5 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($orders as $order)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3 font-medium">{{ $order->order_number }}</td>
                    <td class="px-5 py-3 text-stone-600">{{ $order->user->name }}</td>
                    <td class="px-5 py-3 text-stone-500 text-xs">{{ $order->items->pluck('seller.shop_name')->unique()->implode(', ') }}</td>
                    <td class="px-5 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($order->status) }}</span></td>
                    <td class="px-5 py-3 text-right">Rs. {{ number_format($order->total_amount) }}</td>
                    <td class="px-5 py-3 text-right text-amber-600">Rs. {{ number_format($order->commission_amount) }}</td>
                    <td class="px-5 py-3 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-amber-600 hover:underline text-xs">View</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-stone-400">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
