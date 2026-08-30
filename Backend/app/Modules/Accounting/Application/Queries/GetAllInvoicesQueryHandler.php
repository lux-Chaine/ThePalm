<?php

namespace App\Modules\Accounting\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;

class GetAllInvoicesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        if ($query->reservationId) {
            return $this->invoiceRepository->findByReservationId($query->reservationId);
        }

        if ($query->status) {
            return $this->invoiceRepository->findByStatus($query->status);
        }

        if ($query->startDate && $query->endDate) {
            return $this->invoiceRepository->findDueBetween($query->startDate, $query->endDate);
        }

        return $this->invoiceRepository->findAll();
    }
}
