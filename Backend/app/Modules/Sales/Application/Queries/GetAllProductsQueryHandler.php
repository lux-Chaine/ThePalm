<?php

namespace App\Modules\Sales\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Modules\Sales\Domain\ProductRepositoryInterface;

class GetAllProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        if ($query->activeOnly === true) {
            return $this->productRepository->findActive();
        }

        return $this->productRepository->findAll();
    }
}
