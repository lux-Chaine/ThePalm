<?php

namespace App\Modules\Sales\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetProductByIdQuery implements QueryInterface
{
    public function __construct(
        public readonly int $productId
    ) {}
}
