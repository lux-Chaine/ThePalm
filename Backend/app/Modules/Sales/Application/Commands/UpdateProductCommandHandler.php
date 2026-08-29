<?php

namespace App\Modules\Sales\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Sales\Domain\Product;
use App\Modules\Sales\Domain\ProductRepositoryInterface;
use Exception;

class UpdateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Product
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $product = $this->productRepository->findById($command->productId);

            if (!$product) {
                throw new Exception("Product not found with ID: {$command->productId}");
            }

            $data = array_filter([
                'name' => $command->name,
                'description' => $command->description,
                'price' => $command->price,
                'stock_quantity' => $command->stockQuantity,
                'sku' => $command->sku,
                'is_active' => $command->isActive,
            ], fn($value) => $value !== null);

            return $this->productRepository->update($product, $data);
        });
    }
}
