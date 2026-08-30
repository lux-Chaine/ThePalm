<?php

namespace App\Modules\Reports\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetFinancialReportQuery implements QueryInterface
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly ?string $type = null // income, expense, profit
    ) {}
}
