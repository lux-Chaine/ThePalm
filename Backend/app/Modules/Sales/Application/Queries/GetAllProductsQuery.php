<?php

namespace App\Modules\Sales\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetAllProductsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?bool $activeOnly = null
    ) {}
}
