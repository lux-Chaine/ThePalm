<?php

namespace App\Modules\Sales\Infrastructure;

use App\Modules\Sales\Domain\Order;
use App\Modules\Sales\Domain\OrderRepositoryInterface;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        return Order::find($id);
    }

    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return Order::where('order_number', $orderNumber)->first();
    }

    public function findByUserId(int $userId): array
    {
        return Order::where('user_id', $userId)->get()->toArray();
    }

    public function findAll(): array
    {
        return Order::all()->toArray();
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order->fresh();
    }

    public function delete(Order $order): bool
    {
        return $order->delete();
    }
}
