<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalSellers = Seller::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalCommission = Order::where('status', '!=', 'cancelled')->sum('commission_amount');

        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSellers', 'totalProducts', 'totalOrders',
            'totalRevenue', 'totalCommission', 'recentOrders'
        ));
    }
}
