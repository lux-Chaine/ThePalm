<?php

namespace App\Modules\Accounting\Domain;

interface InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice;
    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice;
    public function findByReservationId(int $reservationId): array;
    public function findByStatus(string $status): array;
    public function findOverdue(): array;
    public function findDueBetween(string $startDate, string $endDate): array;
    public function findAll(): array;
    public function create(array $data): Invoice;
    public function update(Invoice $invoice, array $data): Invoice;
    public function delete(Invoice $invoice): bool;
    public function getTotalRevenue(string $startDate, string $endDate): float;
    public function getOutstandingAmount(): float;
    public function getOverdueAmount(): float;
}
