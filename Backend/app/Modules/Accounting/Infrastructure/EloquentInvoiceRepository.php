<?php

namespace App\Modules\Accounting\Infrastructure;

use App\Modules\Accounting\Domain\Invoice;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice
    {
        return Invoice::where('invoice_number', $invoiceNumber)->first();
    }

    public function findByReservationId(int $reservationId): array
    {
        return Invoice::where('reservation_id', $reservationId)->get()->toArray();
    }

    public function findByStatus(string $status): array
    {
        return Invoice::where('payment_status', $status)->get()->toArray();
    }

    public function findOverdue(): array
    {
        return Invoice::where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid')
            ->get()
            ->toArray();
    }

    public function findDueBetween(string $startDate, string $endDate): array
    {
        return Invoice::whereBetween('due_date', [$startDate, $endDate])
            ->where('payment_status', '!=', 'paid')
            ->get()
            ->toArray();
    }

    public function findAll(): array
    {
        return Invoice::all()->toArray();
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);
        return $invoice->fresh();
    }

    public function delete(Invoice $invoice): bool
    {
        return $invoice->delete();
    }

    public function getTotalRevenue(string $startDate, string $endDate): float
    {
        return Invoice::where('invoice_date', '>=', $startDate)
            ->where('invoice_date', '<=', $endDate)
            ->sum('amount');
    }

    public function getOutstandingAmount(): float
    {
        return Invoice::where('payment_status', '!=', 'paid')
            ->sum('amount') - Invoice::where('payment_status', '!=', 'paid')->sum('paid_amount');
    }

    public function getOverdueAmount(): float
    {
        return Invoice::where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid')
            ->sum('amount') - Invoice::where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid')
            ->sum('paid_amount');
    }
}
