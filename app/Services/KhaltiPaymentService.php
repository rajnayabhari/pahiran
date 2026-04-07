<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KhaltiPaymentService
{
    protected string $baseUrl;
    protected string $secretKey;
    protected string $publicKey;

    public function __construct(
        protected PaymentRepository $paymentRepository,
    ) {
        $this->baseUrl = config('services.khalti.base_url', 'https://a.khalti.com/api/v2/');
        $this->secretKey = config('services.khalti.secret_key', 'your_secret_key_here');
        $this->publicKey = config('services.khalti.public_key', 'your_public_key_here');
    }

    /**
     * Initiate a Khalti payment for the given order.
     *
     * @param Order $order
     * @return array ['success' => bool, 'payment_url' => string|null, 'pidx' => string|null, 'error' => string|null]
     */
    public function initiate(Order $order): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'key ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'epayment/initiate/', [
                'return_url' => route('payment.khalti.callback'),
                'website_url' => config('app.url'),
                'amount' => (int) ($order->total_amount * 100), // Khalti expects amount in paisa
                'purchase_order_id' => $order->order_number,
                'purchase_order_name' => 'Pahiran Order ' . $order->order_number,
                'customer_info' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->phone,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Update payment record with pidx
                $payment = $this->paymentRepository->findByOrder($order->id);
                if ($payment) {
                    $payment->update(['transaction_id' => $data['pidx']]);
                }

                return [
                    'success' => true,
                    'payment_url' => $data['payment_url'],
                    'pidx' => $data['pidx'],
                    'error' => null,
                ];
            }

            Log::error('Khalti initiate failed', ['response' => $response->json()]);

            return [
                'success' => false,
                'payment_url' => null,
                'pidx' => null,
                'error' => 'Payment initiation failed. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('Khalti initiate exception', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'payment_url' => null,
                'pidx' => null,
                'error' => 'Payment service unavailable. Please try again later.',
            ];
        }
    }

    /**
     * Verify a Khalti payment by pidx (called from callback route).
     *
     * @param string $pidx   The payment identifier from Khalti
     * @return array ['success' => bool, 'data' => array|null, 'error' => string|null]
     */
    public function verify(string $pidx): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'key ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'epayment/lookup/', [
                'pidx' => $pidx,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === 'Completed') {
                    // Find payment by pidx (stored as transaction_id)
                    $payment = \App\Models\Payment::where('transaction_id', $pidx)->first();

                    if ($payment) {
                        // Verify amount matches (Khalti returns in paisa)
                        $expectedAmount = (int) ($payment->amount * 100);
                        if ((int) $data['total_amount'] === $expectedAmount) {
                            $this->paymentRepository->updateStatus($payment->id, 'completed', $data);

                            // Update order status to processing
                            $payment->order->update(['status' => 'processing']);

                            return [
                                'success' => true,
                                'data' => $data,
                                'error' => null,
                            ];
                        }

                        Log::error('Khalti amount mismatch', [
                            'expected' => $expectedAmount,
                            'received' => $data['total_amount'],
                        ]);
                    }
                }

                return [
                    'success' => false,
                    'data' => $data,
                    'error' => 'Payment verification failed.',
                ];
            }

            return [
                'success' => false,
                'data' => null,
                'error' => 'Payment verification request failed.',
            ];
        } catch (\Exception $e) {
            Log::error('Khalti verify exception', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'data' => null,
                'error' => 'Payment verification service unavailable.',
            ];
        }
    }
}
