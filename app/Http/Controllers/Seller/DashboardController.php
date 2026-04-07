<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        $totalProducts = Product::where('seller_id', $seller->id)->count();
        $totalOrders = OrderItem::where('seller_id', $seller->id)->distinct('order_id')->count('order_id');
        $totalRevenue = OrderItem::where('seller_id', $seller->id)
            ->selectRaw('SUM(unit_price * quantity) as revenue')
            ->value('revenue') ?? 0;
        $totalCommission = OrderItem::where('seller_id', $seller->id)
            ->sum('commission');

        $recentOrders = Order::whereHas('items', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with('user')->latest()->take(5)->get();

        return view('seller.dashboard', compact(
            'seller', 'totalProducts', 'totalOrders', 'totalRevenue', 'totalCommission', 'recentOrders'
        ));
    }
}
