<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard — Pahiran')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-stone-100 min-h-screen" x-data="{ sidebarOpen: true }">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-stone-900 text-white min-h-screen p-6 shrink-0 hidden md:block">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center mb-8">
                    <img src="{{ asset('logo.jpeg') }}" alt="Pahiran Seller" class="h-8 w-auto">
                    <span class="text-amber-400 text-sm font-normal ml-2">Seller</span>
                </a>
            <nav class="space-y-1">
                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('seller.dashboard') ? 'bg-white/10 text-white' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('seller.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('seller.categories.*') ? 'bg-white/10 text-white' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Categories
                </a>
                <a href="{{ route('seller.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('seller.products.*') ? 'bg-white/10 text-white' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Products
                </a>
                <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('seller.orders.*') ? 'bg-white/10 text-white' : 'text-stone-400 hover:text-white hover:bg-white/5' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Orders
                </a>
            </nav>
            <div class="mt-auto pt-8 border-t border-stone-800 mt-8">
                <p class="text-xs text-stone-500 mb-2">{{ auth()->guard('seller')->user()->shop_name }}</p>
                <form method="POST" action="{{ route('seller.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-stone-400 hover:text-white transition">Logout</button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 p-6 md:p-8">
            @if(session('success'))<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm mb-6">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-6">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
    </div>
</body>
</html>
