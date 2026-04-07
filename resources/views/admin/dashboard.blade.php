@extends('layouts.admin')
@section('title', 'Admin Dashboard — Pahiran')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">Platform Dashboard</h1>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl p-5 border border-stone-200"><p class="text-sm text-stone-500 mb-1">Customers</p><p class="text-2xl font-bold text-stone-900">{{ $totalUsers }}</p></div>
    <div class="bg-white rounded-xl p-5 border border-stone-200"><p class="text-sm text-stone-500 mb-1">Sellers</p><p class="text-2xl font-bold text-stone-900">{{ $totalSellers }}</p></div>
    <div class="bg-white rounded-xl p-5 border border-stone-200"><p class="text-sm text-stone-500 mb-1">Products</p><p class="text-2xl font-bold text-stone-900">{{ $totalProducts }}</p></div>
    <div class="bg-white rounded-xl p-5 border border-stone-200"><p class="text-sm text-stone-500 mb-1">Orders</p><p class="text-2xl font-bold text-stone-900">{{ $totalOrders }}</p></div>
    <div class="bg-white rounded-xl p-5 border border-stone-200"><p class="text-sm text-stone-500 mb-1">Total Revenue</p><p class="text-2xl font-bold text-emerald-600">Rs. {{ number_format($totalRevenue) }}</p></div>
    <div class="bg-white rounded-xl p-5 border border-stone-200"><p class="text-sm text-stone-500 mb-1">Commission Earned</p><p class="text-2xl font-bold text-amber-600">Rs. {{ number_format($totalCommission) }}</p></div>
</div>

<div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-stone-200"><h2 class="font-semibold text-stone-900">Recent Orders</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500"><tr><th class="px-5 py-3 text-left">Order</th><th class="px-5 py-3 text-left">Customer</th><th class="px-5 py-3 text-left">Status</th><th class="px-5 py-3 text-right">Total</th><th class="px-5 py-3 text-right">Commission</th></tr></thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-amber-600 hover:underline">{{ $order->order_number }}</a></td>
                    <td class="px-5 py-3 text-stone-600">{{ $order->user->name }}</td>
                    <td class="px-5 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($order->status) }}</span></td>
                    <td class="px-5 py-3 text-right">Rs. {{ number_format($order->total_amount) }}</td>
                    <td class="px-5 py-3 text-right text-amber-600">Rs. {{ number_format($order->commission_amount) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-stone-400">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
