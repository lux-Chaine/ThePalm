<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateOrderStatusCommand implements CommandInterface
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $status
    ) {}
}
