<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Accounting\Domain\Invoice;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;

class CreateInvoiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Invoice
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $data = [
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'reservation_id' => $command->reservationId,
                'created_by' => $command->createdBy,
                'amount' => $command->amount,
                'paid_amount' => 0,
                'discount_amount' => $command->discountAmount ?? 0,
                'tax_amount' => $command->taxAmount ?? 0,
                'payment_status' => 'unpaid',
                'payment_method' => $command->paymentMethod,
                'due_date' => $command->dueDate,
                'notes' => $command->notes,
            ];

            return $this->invoiceRepository->create($data);
        });
    }
}
