<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Log;

class EsewaPaymentService
{
    protected string $baseUrl;
    protected string $secretKey;
    protected string $merchantCode;

    public function __construct(
        protected PaymentRepository $paymentRepository,
    ) {
        $this->baseUrl      = config('services.esewa.base_url', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');
        $this->secretKey    = config('services.esewa.secret_key', '8gBm/:&EnhH.1/q');
        $this->merchantCode = config('services.esewa.merchant_code', 'EPAYTEST');
    }

    /**
     * Generate form parameters and signature required to redirect to eSewa.
     *
     * @param Order $order
     * @return array
     */
    public function getPaymentFormParams(Order $order): array
    {
        // eSewa requires amount in formatting without commas. So we use number_format without thousands separator.
        $amount = number_format($order->total_amount, 2, '.', '');
        
        $transactionUuid = $order->order_number . '-' . time();
        $productCode = $this->merchantCode;

        // Message format for HMAC SHA256: total_amount=xx,transaction_uuid=xx,product_code=xx
        $message = "total_amount=$amount,transaction_uuid=$transactionUuid,product_code=$productCode";

        // Generate HMAC SHA256 signature and encode in base64
        $hash = hash_hmac('sha256', $message, $this->secretKey, true);
        $signature = base64_encode($hash);

        // We update the payment transaction ID to the transactionUuid so we can match it later
        $payment = $this->paymentRepository->findByOrder($order->id);
        if ($payment) {
            $payment->update(['transaction_id' => $transactionUuid]);
        }

        return [
            'amount' => $amount,
            'tax_amount' => '0',
            'total_amount' => $amount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $productCode,
            'product_delivery_charge' => '0',
            'product_service_charge' => '0',
            'success_url' => route('payment.esewa.success'),
            'failure_url' => route('payment.esewa.failure'),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $signature,
            'action_url' => $this->baseUrl,
        ];
    }

    /**
     * Verify the base64 encoded response from eSewa.
     * eSewa returns a base64 encoded JSON string in the 'data' query parameter.
     *
     * @param string $base64Data
     * @return array
     */
    public function verifyCallback(string $base64Data): array
    {
        try {
            $jsonResponse = base64_decode($base64Data);
            $data = json_decode($jsonResponse, true);

            if (!$data) {
                Log::error('eSewa verify: invalid base64 or json data', ['data' => $base64Data]);
                return ['success' => false, 'error' => 'Invalid payment callback format.'];
            }

            Log::info('eSewa callback data', $data);

            if (($data['status'] ?? '') === 'COMPLETE') {
                $transactionUuid = $data['transaction_uuid'];
                
                // Regenerate the signature to verify its authenticity
                $message = "transaction_code={$data['transaction_code']},status={$data['status']},total_amount={$data['total_amount']},transaction_uuid=$transactionUuid,product_code={$data['product_code']},signed_field_names={$data['signed_field_names']}";
                $hash = hash_hmac('sha256', $message, $this->secretKey, true);
                $expectedSignature = base64_encode($hash);

                if ($expectedSignature !== $data['signature']) {
                    Log::error('eSewa verify: signature mismatch', [
                        'expected' => $expectedSignature,
                        'received' => $data['signature'],
                        'data' => $data,
                    ]);
                    return ['success' => false, 'error' => 'Payment verification failed (signature mismatch).'];
                }

                $payment = \App\Models\Payment::where('transaction_id', $transactionUuid)->first();

                if (!$payment) {
                    Log::error('eSewa verify: payment record not found', ['transaction_uuid' => $transactionUuid]);
                    return ['success' => false, 'error' => 'Payment record not found. Please contact support.'];
                }

                if ($payment->status === 'completed') {
                    Log::info('eSewa verify: payment already completed', ['transaction_uuid' => $transactionUuid]);
                    return ['success' => true, 'order_id' => $payment->order_id, 'transaction_uuid' => $transactionUuid];
                }

                // Verify amount
                // Total amount from esewa has commas e.g. "1,000.00" so we need to remove commas to compare
                $receivedAmount = (float) str_replace(',', '', $data['total_amount']);
                $expectedAmount = (float) $payment->amount;

                if (abs($receivedAmount - $expectedAmount) > 0.1) {
                    Log::error('eSewa verify: amount mismatch', [
                        'transaction_uuid'     => $transactionUuid,
                        'expected' => $expectedAmount,
                        'received' => $receivedAmount,
                    ]);
                    return ['success' => false, 'error' => 'Payment amount mismatch. Please contact support.'];
                }

                // Mark payment completed and update order
                $this->paymentRepository->updateStatus($payment->id, 'completed', $data);
                $payment->order->update(['status' => 'processing']);

                Log::info('eSewa payment completed', [
                    'transaction_uuid' => $transactionUuid,
                    'order_id' => $payment->order_id,
                ]);

                return ['success' => true, 'order_id' => $payment->order_id, 'transaction_uuid' => $transactionUuid];
            }

            return ['success' => false, 'error' => 'Payment was not marked as completed. Status: ' . ($data['status'] ?? 'unknown')];

        } catch (\Exception $e) {
            Log::error('eSewa verify exception', ['message' => $e->getMessage(), 'data' => $base64Data]);
            return ['success' => false, 'error' => 'Payment verification service unavailable.'];
        }
    }
}
