<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected PaymentRepository $paymentRepository,
    ) {}

    /**
     * Place a new order from cart items.
     *
     * @param User   $user         The customer placing the order
     * @param array  $cartItems    Array of ['product_id', 'variant_id', 'quantity']
     * @param array  $shippingData ['shipping_address', 'billing_address', 'phone', 'notes']
     * @param string $paymentMethod The selected payment method (e.g., 'esewa', 'cod')
     * @return Order
     */
    public function placeOrder(User $user, array $cartItems, array $shippingData, string $paymentMethod = 'esewa'): Order
    {
        return DB::transaction(function () use ($user, $cartItems, $shippingData, $paymentMethod) {
            $totalAmount = 0;
            $totalCommission = 0;
            $orderItemsData = [];

            foreach ($cartItems as $item) {
                $product = Product::with('seller')->findOrFail($item['product_id']);
                $variant = isset($item['variant_id']) ? ProductVariant::findOrFail($item['variant_id']) : null;

                $unitPrice = $variant ? $variant->price : $product->base_price;
                $quantity = $item['quantity'];
                $lineTotal = $unitPrice * $quantity;

                // Calculate commission based on seller's commission rate (default 10%)
                $commissionRate = $product->seller->commission_rate / 100;
                $commission = round($lineTotal * $commissionRate, 2);

                $totalAmount += $lineTotal;
                $totalCommission += $commission;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'seller_id' => $product->seller_id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'commission' => $commission,
                ];

                // Decrease stock if variant exists
                if ($variant) {
                    $variant->decrement('stock', $quantity);
                }
            }

            // Create the order
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'order_number' => 'PAH-' . strtoupper(Str::random(8)),
                'total_amount' => $totalAmount,
                'commission_amount' => $totalCommission,
                'status' => 'pending',
                'shipping_address' => $shippingData['shipping_address'],
                'billing_address' => $shippingData['billing_address'] ?? $shippingData['shipping_address'],
                'phone' => $shippingData['phone'],
                'notes' => $shippingData['notes'] ?? null,
            ]);

            // Create order items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Create pending payment record
            $this->paymentRepository->create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'status' => $paymentMethod === 'cod' ? 'pending' : 'pending',
                'payment_method' => $paymentMethod,
            ]);

            return $order->load('items', 'payment');
        });
    }

    /**
     * Get orders for a specific seller (only items belonging to them).
     */
    public function getSellerOrders(int $sellerId)
    {
        return $this->orderRepository->getBySeller($sellerId);
    }

    /**
     * Get orders for a specific user.
     */
    public function getUserOrders(int $userId)
    {
        return $this->orderRepository->getByUser($userId);
    }
}
