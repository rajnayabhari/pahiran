@extends('layouts.admin')
@section('title', 'Manage Users — Pahiran')
@section('content')
<h1 class="text-2xl font-bold text-stone-900 mb-6">Manage Users</h1>
<div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500">
                <tr>
                    <th class="px-5 py-3 text-left">User</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-center">Orders</th>
                    <th class="px-5 py-3 text-center">Joined</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($users as $user)
                <tr class="hover:bg-stone-50">
                    <td class="px-5 py-3 font-medium">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-stone-600">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-center">{{ $user->orders_count ?? 0 }}</td>
                    <td class="px-5 py-3 text-center text-stone-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-3 text-xs">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-stone-600 hover:text-stone-900 hover:underline">View</a>
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs {{ $user->is_active ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }} hover:underline">
                                    {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-stone-400">No users registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
