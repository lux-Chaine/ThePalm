<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateProductCommand implements CommandInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stockQuantity,
        public readonly string $sku,
        public readonly bool $isActive = true
    ) {}
}
