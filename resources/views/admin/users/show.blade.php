@extends('layouts.admin')
@section('title', 'User Details — Pahiran')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-stone-900">User: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="text-sm text-stone-500 hover:text-stone-900">← Back to Users</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- User Info --}}
    <div class="md:col-span-1 border border-stone-200 bg-white rounded-xl p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-stone-500 mb-4">Account Details</h2>
        <div class="space-y-3 text-sm">
            <div><span class="text-stone-400 block text-xs">Name</span>{{ $user->name }}</div>
            <div><span class="text-stone-400 block text-xs">Email</span>{{ $user->email }}</div>
            <div><span class="text-stone-400 block text-xs">Status</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                </span>
            </div>
            <div><span class="text-stone-400 block text-xs">Joined</span>{{ $user->created_at->format('M d, Y') }}</div>
        </div>

        <div class="mt-6 pt-6 border-t border-stone-100 space-y-2">
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                @csrf @method('PATCH')
                <button type="submit" class="w-full text-center px-4 py-2 rounded-lg text-sm font-medium {{ $user->is_active ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }} transition">
                    {{ $user->is_active ? 'Suspend User' : 'Activate User' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Order History --}}
    <div class="md:col-span-2 border border-stone-200 bg-white rounded-xl p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-stone-500 mb-4">Order History</h2>
        
        @if($user->orders->count())
            <div class="space-y-4">
                @foreach($user->orders as $order)
                <div class="border border-stone-100 rounded-lg p-4 bg-stone-50 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium mb-1">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-stone-900 hover:underline">{{ $order->order_number }}</a>
                        </div>
                        <div class="text-xs text-stone-500">{{ $order->items->count() }} item(s) • {{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-stone-900 mb-1">Rs. {{ number_format($order->total_amount, 2) }}</div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider bg-stone-200 text-stone-700">{{ $order->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-stone-400 text-sm">This user hasn't placed any orders yet.</div>
        @endif
    </div>
</div>

@endsection
