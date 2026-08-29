<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateProductCommand implements CommandInterface
{
    public function __construct(
        public readonly int $productId,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?int $stockQuantity = null,
        public readonly ?string $sku = null,
        public readonly ?bool $isActive = null
    ) {}
}
