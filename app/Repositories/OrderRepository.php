<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function findById(int $id): ?Order
    {
        return Order::with(['items.product', 'items.variant', 'items.seller', 'payment'])->find($id);
    }

    public function getByUser(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->with(['items.product', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getBySeller(int $sellerId): Collection
    {
        return Order::whereHas('items', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
            ->with(['items' => function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId)->with('product');
            }, 'user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function updateStatus(int $id, string $status): bool
    {
        return Order::where('id', $id)->update(['status' => $status]);
    }

    public function getAll(): Collection
    {
        return Order::with(['user', 'items.seller', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
