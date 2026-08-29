<?php

namespace App\Modules\Sales\Domain;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    public function findByOrderNumber(string $orderNumber): ?Order;
    public function findByUserId(int $userId): array;
    public function findAll(): array;
    public function create(array $data): Order;
    public function update(Order $order, array $data): Order;
    public function delete(Order $order): bool;
}
