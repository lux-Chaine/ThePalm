<?php

namespace App\Modules\Sales\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Modules\Sales\Domain\Product;
use App\Modules\Sales\Domain\ProductRepositoryInterface;
use Exception;

class GetProductByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function handle(QueryInterface $query): Product
    {
        $product = $this->productRepository->findById($query->productId);

        if (!$product) {
            throw new Exception("Product not found with ID: {$query->productId}");
        }

        return $product;
    }
}
