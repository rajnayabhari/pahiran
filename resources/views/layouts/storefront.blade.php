<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pahiran — Premium Fashion Ecommerce. Discover curated fashion from independent sellers.">
    <title>@yield('title', 'Pahiran — Fashion Ecommerce')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,500,600,700" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-stone-50 text-stone-900 min-h-screen flex flex-col" x-data="{ mobileMenu: false, cartCount: {{ count(session('cart', [])) }} }">

    {{-- Navigation --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-stone-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('logo.jpeg') }}" alt="Pahiran" class="h-8 w-auto">
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center space-x-6 flex-1 ml-10">
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-widest text-stone-600 hover:text-stone-900 transition">Home</a>
                        @php $navCategories = \App\Models\Category::where('is_active', true)->orderBy('name')->get(); @endphp
                        @foreach($navCategories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" class="text-xs font-bold uppercase tracking-widest text-stone-600 hover:text-stone-900 transition">{{ $cat->name }}</a>
                        @endforeach
                    </div>

                    {{-- Search Bar --}}
                    <div class="flex-1 max-w-sm ml-8">
                        <form action="{{ route('search') }}" method="GET" class="relative group">
                            <input type="text" name="q" placeholder="Search for products..." 
                                   value="{{ request('q') }}"
                                   class="w-full bg-stone-100 border-none rounded-full py-2 pl-10 pr-4 text-xs font-medium placeholder-stone-400 focus:ring-2 focus:ring-amber-700/20 transition-all duration-300">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 group-focus-within:text-amber-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center space-x-4">
                    {{-- Wishlist --}}
                    @auth
                    <a href="{{ route('wishlist.index') }}" class="p-2 text-stone-600 hover:text-stone-900 transition relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        @php $wishlistCount = auth()->user()->wishlist()->count(); @endphp
                        @if($wishlistCount > 0)
                        <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                    @endauth

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-stone-600 hover:text-stone-900 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-1 -right-1 bg-amber-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium"></span>
                    </a>

                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-stone-600">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-stone-500 hover:text-stone-700 transition">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium bg-stone-900 text-white px-4 py-2 rounded-lg hover:bg-stone-800 transition">Register</a>
                    @endauth

                    {{-- Mobile Menu Toggle --}}
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-t border-stone-200">
            <div class="px-4 py-4 space-y-4">
                {{-- Mobile Search --}}
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Search..." 
                           value="{{ request('q') }}"
                           class="w-full bg-stone-50 border-stone-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>

                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="block text-sm font-bold uppercase tracking-widest text-stone-600 hover:text-stone-900 py-2">Home</a>
                    @foreach($navCategories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="block text-sm font-bold uppercase tracking-widest text-stone-600 hover:text-stone-900 py-2">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-stone-900 text-stone-400 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Pahiran" class="h-6 w-auto mb-3">
                    <p class="text-sm leading-relaxed max-w-md">Curated fashion from independent sellers. Discover unique styles that express your individuality.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Cart</a></li>
                        <li><a href="{{ route('seller.login') }}" class="hover:text-white transition">Sell on Pahiran</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-3">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li>hello@pahiran.com</li>
                        <li>Kathmandu, Nepal</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-stone-800 mt-8 pt-6 text-center text-xs">
                &copy; {{ date('Y') }} Pahiran. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
