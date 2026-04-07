<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function findByOrder(int $orderId): ?Payment
    {
        return Payment::where('order_id', $orderId)->first();
    }

    public function updateStatus(int $id, string $status, ?array $response = null): bool
    {
        $data = ['status' => $status];

        if ($status === 'completed') {
            $data['paid_at'] = now();
        }

        if ($response) {
            $data['gateway_response'] = $response;
        }

        return Payment::where('id', $id)->update($data);
    }
}
