<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateExpenseCommand implements CommandInterface
{
    public function __construct(
        public readonly int $createdBy,
        public readonly string $category,
        public readonly string $description,
        public readonly float $amount,
        public readonly string $expenseDate,
        public readonly ?string $receiptUrl = null,
        public readonly ?string $notes = null
    ) {}
}
