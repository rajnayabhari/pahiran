<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('seller')->check()) {
            return redirect()->route('seller.login')->with('error', 'Please login as a seller to access this page.');
        }

        if (!auth()->guard('seller')->user()->is_active) {
            auth()->guard('seller')->logout();
            return redirect()->route('seller.login')->with('error', 'Your seller account has been deactivated.');
        }

        return $next($request);
    }
}
