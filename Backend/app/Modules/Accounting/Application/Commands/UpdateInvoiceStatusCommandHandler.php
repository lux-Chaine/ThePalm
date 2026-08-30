<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Accounting\Domain\Invoice;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;

class UpdateInvoiceStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Invoice
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $invoice = $this->invoiceRepository->findById($command->invoiceId);
            
            if (!$invoice) {
                throw new \Exception("Invoice not found");
            }

            $data = ['payment_status' => $command->paymentStatus];

            if ($command->paymentAmount) {
                $newPaidAmount = $invoice->paid_amount + $command->paymentAmount;
                $data['paid_amount'] = $newPaidAmount;

                if ($newPaidAmount >= $invoice->amount) {
                    $data['payment_status'] = 'paid';
                } elseif ($newPaidAmount > 0) {
                    $data['payment_status'] = 'partial';
                }
            }

            if ($command->paymentMethod) {
                $data['payment_method'] = $command->paymentMethod;
            }

            return $this->invoiceRepository->update($invoice, $data);
        });
    }
}
