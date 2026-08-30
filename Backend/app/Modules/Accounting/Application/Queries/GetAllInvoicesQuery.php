<?php

namespace App\Modules\Accounting\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetAllInvoicesQuery implements QueryInterface
{
    public function __construct(
        public readonly ?int $reservationId = null,
        public readonly ?string $status = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?int $page = null,
        public readonly ?int $perPage = null
    ) {}
}
