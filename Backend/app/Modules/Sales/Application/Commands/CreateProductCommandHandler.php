<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Sales\Domain\Product;
use App\Modules\Sales\Domain\ProductRepositoryInterface;

class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Product
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $data = [
                'name' => $command->name,
                'description' => $command->description,
                'price' => $command->price,
                'stock_quantity' => $command->stockQuantity,
                'sku' => $command->sku,
                'is_active' => $command->isActive,
            ];

            return $this->productRepository->create($data);
        });
    }
}
