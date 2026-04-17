<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\EsewaPaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected EsewaPaymentService $esewaService,
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
                'price'   => $price,
                'subtotal' => $subtotal,
            ];
        }

        return view('storefront.checkout', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone'            => 'required|string|regex:/^[0-9]{10}$/',
            'notes'            => 'nullable|string|max:500',
            'payment_method'   => 'required|in:esewa,cod',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Create the order (cart items are validated, stock decremented inside OrderService)
        $order = $this->orderService->placeOrder(
            auth()->user(),
            array_values($cart),
            [
                'shipping_address' => $request->shipping_address,
                'billing_address'  => $request->shipping_address,
                'phone'            => $request->phone,
                'notes'            => $request->notes,
            ],
            $request->payment_method
        );

        // ── Cash on Delivery ──────────────────────────────────────────────────
        if ($request->payment_method === 'cod') {
            session()->forget('cart');

            return redirect()->route('checkout.success', ['order_id' => $order->id])
                ->with('success', 'Order placed! You will pay on delivery.');
        }

        // ── eSewa (ePay v2) ──────────────────────────────────────────────────
        $esewaForm = $this->esewaService->getPaymentFormParams($order);

        // Clear cart since we are now handling off to eSewa page
        session()->forget('cart');

        return view('storefront.payment-redirect', compact('esewaForm'));
    }

    /**
     * eSewa redirects here when payment succeeds.
     * Contains base64 encoded 'data' in query string.
     */
    public function esewaSuccess(Request $request)
    {
        $data = $request->query('data');
        if (!$data) {
            return redirect('/')->with('error', 'Invalid payment response from eSewa.');
        }

        $result = $this->esewaService->verifyCallback($data);

        if ($result['success']) {
            return redirect()->route('checkout.success', ['pidx' => $result['transaction_uuid']])
                ->with('success', 'eSewa Payment verified! Your order is confirmed.');
        }

        // We don't have the order/payment context extracted if verify failed before decoding,
        // but verifyCallback handles updating to 'failed' if needed conceptually.
        // Actually verifyCallback doesn't mark failed automatically to prevent tampering causing failure.
        return redirect('/')->with('error', $result['error'] ?? 'Payment verification failed. Please contact support.');
    }

    /**
     * eSewa redirects here when the user cancels the payment.
     */
    public function esewaFailure(Request $request)
    {
        Log::info('eSewa payment cancelled by user');
        return redirect()->route('cart.index')->with('error', 'Payment was cancelled. You can try checking out again.');
    }

    public function success(Request $request)
    {
        $pidx    = $request->query('pidx');
        $orderId = $request->query('order_id');

        if ($pidx) {
            $payment = Payment::where('transaction_id', $pidx)->with('order')->first();
        } elseif ($orderId) {
            $payment = Payment::where('order_id', $orderId)->with('order')->first();
        } else {
            return redirect('/')->with('error', 'No order reference found.');
        }

        if (! $payment) {
            return redirect('/')->with('error', 'Order not found.');
        }

        return view('storefront.success', compact('payment'));
    }
}
