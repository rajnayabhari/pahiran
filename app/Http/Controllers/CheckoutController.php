<?php

namespace App\Http\Controllers;

use App\Services\KhaltiPaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected KhaltiPaymentService $khaltiService,
    ) {}

    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = \App\Models\Product::with('seller')->find($item['product_id']);
            if (!$product) continue;

            $variant = isset($item['variant_id']) ? \App\Models\ProductVariant::find($item['variant_id']) : null;
            $price = $variant ? $variant->price : $product->base_price;
            $subtotal = $price * $item['quantity'];
            $total += $subtotal;

            $cartItems[] = [
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        return view('storefront.checkout', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:khalti,cod',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Place the order
        $order = $this->orderService->placeOrder(
            auth()->user(),
            array_values($cart),
            [
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->shipping_address,
                'phone' => $request->phone,
                'notes' => $request->notes,
            ],
            $request->payment_method
        );

        if ($request->payment_method === 'cod') {
            // Clear the cart
            session()->forget('cart');

            return redirect()->route('checkout.success', ['order_id' => $order->id])
                ->with('success', 'Order placed successfully! Please pay on delivery.');
        }

        // Initiate Khalti payment
        $result = $this->khaltiService->initiate($order);

        if ($result['success']) {
            // Clear the cart
            session()->forget('cart');

            return redirect($result['payment_url']);
        }

        return back()->with('error', $result['error']);
    }

    public function callback(Request $request)
    {
        $pidx = $request->query('pidx');
        $status = $request->query('status');

        if (!$pidx) {
            return redirect('/')->with('error', 'Invalid payment callback.');
        }

        if ($status === 'Completed') {
            $result = $this->khaltiService->verify($pidx);

            if ($result['success']) {
                return redirect()->route('checkout.success', ['pidx' => $pidx])
                    ->with('success', 'Payment successful! Your order has been placed.');
            }
        }

        return redirect('/')->with('error', 'Payment was not completed. Please try again.');
    }

    public function success(Request $request)
    {
        $pidx = $request->query('pidx');
        $orderId = $request->query('order_id');

        if ($pidx) {
            $payment = \App\Models\Payment::where('transaction_id', $pidx)->with('order')->first();
        } elseif ($orderId) {
            $payment = \App\Models\Payment::where('order_id', $orderId)->with('order')->first();
        } else {
            return redirect('/')->with('error', 'No order reference found.');
        }

        return view('storefront.success', compact('payment'));
    }
}
