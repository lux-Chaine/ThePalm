<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateOrderCommand implements CommandInterface
{
    public function __construct(
        public readonly int $userId,
        public readonly string $orderNumber,
        public readonly float $totalAmount,
        public readonly string $shippingAddress,
        public readonly ?string $notes = null
    ) {}
}
