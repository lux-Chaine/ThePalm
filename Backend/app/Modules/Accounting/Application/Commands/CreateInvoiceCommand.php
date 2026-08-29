<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateInvoiceCommand implements CommandInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly int $createdBy,
        public readonly float $amount,
        public readonly string $dueDate,
        public readonly ?float $discountAmount = null,
        public readonly ?float $taxAmount = null,
        public readonly ?string $paymentMethod = null,
        public readonly ?string $notes = null
    ) {}
}
