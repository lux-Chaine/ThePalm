<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateInvoiceStatusCommand implements CommandInterface
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly string $paymentStatus,
        public readonly ?float $paymentAmount = null,
        public readonly ?string $paymentMethod = null
    ) {}
}
