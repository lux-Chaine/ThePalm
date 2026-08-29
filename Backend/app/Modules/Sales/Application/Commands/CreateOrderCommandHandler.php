<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Sales\Domain\Order;
use App\Modules\Sales\Domain\OrderRepositoryInterface;
use Exception;

class CreateOrderCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Order
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $data = [
                'user_id' => $command->userId,
                'order_number' => $command->orderNumber,
                'total_amount' => $command->totalAmount,
                'status' => 'pending',
                'shipping_address' => $command->shippingAddress,
                'notes' => $command->notes,
            ];

            return $this->orderRepository->create($data);
        });
    }
}
