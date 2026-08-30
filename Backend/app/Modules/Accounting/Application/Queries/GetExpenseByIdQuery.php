<?php

namespace App\Modules\Accounting\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetExpenseByIdQuery implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
