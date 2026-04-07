@extends('layouts.admin')
@section('title', 'Manage Sellers — Pahiran')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">Manage Sellers</h1>
<div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500"><tr><th class="px-5 py-3 text-left">Seller</th><th class="px-5 py-3 text-left">Shop</th><th class="px-5 py-3 text-center">Products</th><th class="px-5 py-3 text-center">Commission</th><th class="px-5 py-3 text-center">Status</th><th class="px-5 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($sellers as $seller)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3"><div><p class="font-medium">{{ $seller->name }}</p><p class="text-xs text-stone-500">{{ $seller->email }}</p></div></td>
                    <td class="px-5 py-3 text-stone-600">{{ $seller->shop_name }}</td>
                    <td class="px-5 py-3 text-center">{{ $seller->products_count }}</td>
                    <td class="px-5 py-3 text-center">
                        <form method="POST" action="{{ route('admin.sellers.commission', $seller) }}" class="flex items-center justify-center gap-1">@csrf @method('PATCH')
                            <input type="number" name="commission_rate" value="{{ $seller->commission_rate }}" step="0.01" min="0" max="100" class="w-16 border border-stone-300 rounded px-2 py-1 text-xs text-center">
                            <button type="submit" class="text-xs text-amber-600 hover:underline">Save</button>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $seller->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $seller->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('admin.sellers.toggle', $seller) }}" class="inline">@csrf @method('PATCH')
                            <button type="submit" class="text-xs {{ $seller->is_active ? 'text-red-500' : 'text-emerald-600' }} hover:underline">{{ $seller->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.sellers.destroy', $seller) }}" class="inline ml-2" onsubmit="return confirm('Are you sure you want to delete this seller? This action cannot be undone.')">@csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-stone-400">No sellers registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $sellers->links() }}</div>
@endsection
