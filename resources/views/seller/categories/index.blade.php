@extends('layouts.seller')
@section('title', 'Categories — Pahiran')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-stone-900">Categories</h1>
    <a href="{{ route('seller.categories.create') }}" class="bg-stone-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-stone-800 transition">+ Add Category</a>
</div>

<div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500">
                <tr>
                    <th class="px-5 py-3 text-left">Category</th>
                    <th class="px-5 py-3 text-left">Description</th>
                    <th class="px-5 py-3 text-center">Products</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($categories as $category)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3 font-medium">{{ $category->name }}</td>
                    <td class="px-5 py-3 text-stone-600">{{ Str::limit($category->description, 50) ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">{{ $category->products_count }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-200 text-stone-600' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right space-x-2">
                        <a href="{{ route('seller.categories.edit', $category) }}" class="text-amber-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('seller.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button type="submit" class="text-red-500 hover:underline text-xs">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-stone-400">No categories yet. <a href="{{ route('seller.categories.create') }}" class="text-amber-600 hover:underline">Create your first category</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
