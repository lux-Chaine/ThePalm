<?php

namespace App\Modules\Accounting\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;

class GetInvoiceByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository
    ) {}

    public function handle(QueryInterface $query): ?array
    {
        $invoice = $this->invoiceRepository->findById($query->id);
        return $invoice ? $invoice->toArray() : null;
    }
}
