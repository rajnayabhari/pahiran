@extends('layouts.storefront')
@section('title', 'Seller Login — Pahiran')
@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="text-center mb-8">
        <p class="text-amber-700 text-sm font-medium uppercase tracking-widest mb-2">Seller Portal</p>
        <h1 class="font-display text-3xl font-bold text-stone-900">Seller Login</h1>
    </div>
    @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
    <form method="POST" action="{{ route('seller.login.submit') }}" class="bg-white rounded-xl p-6 border border-stone-200 space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-stone-700 mb-1">Email</label>
            <input type="email" name="email" id="email" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent" value="{{ old('email') }}">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-stone-700 mb-1">Password</label>
            <input type="password" name="password" id="password" required class="w-full border border-stone-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        <label class="flex items-center gap-2 text-sm text-stone-600"><input type="checkbox" name="remember" class="rounded"> Remember me</label>
        <button type="submit" class="w-full bg-amber-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-amber-700 transition">Login as Seller</button>
        <p class="text-center text-sm text-stone-500">New seller? <a href="{{ route('seller.register') }}" class="text-amber-700 font-medium hover:underline">Register here</a></p>
    </form>
</div>
@endsection
