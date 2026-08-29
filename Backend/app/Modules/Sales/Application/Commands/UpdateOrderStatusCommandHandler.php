<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Sales\Domain\Order;
use App\Modules\Sales\Domain\OrderRepositoryInterface;
use Exception;

class UpdateOrderStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Order
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $order = $this->orderRepository->findById($command->orderId);

            if (!$order) {
                throw new Exception("Order not found with ID: {$command->orderId}");
            }

            $validStatuses = ['pending', 'completed', 'cancelled'];
            if (!in_array($command->status, $validStatuses)) {
                throw new Exception("Invalid status. Must be one of: " . implode(', ', $validStatuses));
            }

            return $this->orderRepository->update($order, ['status' => $command->status]);
        });
    }
}
