<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateExpenseStatusCommand implements CommandInterface
{
    public function __construct(
        public readonly int $expenseId,
        public readonly string $status,
        public readonly ?string $rejectionReason = null
    ) {}
}
