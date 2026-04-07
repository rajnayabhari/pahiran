<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}

    public function index()
    {
        $orders = $this->orderService->getSellerOrders(Auth::guard('seller')->id());

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure this order has items belonging to this seller
        $sellerId = Auth::guard('seller')->id();
        $hasItems = $order->items()->where('seller_id', $sellerId)->exists();

        if (!$hasItems) {
            abort(403);
        }

        $order->load(['user', 'items' => function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId)->with(['product', 'variant']);
        }, 'payment']);

        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered',
        ]);

        $sellerId = Auth::guard('seller')->id();
        if (!$order->items()->where('seller_id', $sellerId)->exists()) {
            abort(403);
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated!');
    }
}
