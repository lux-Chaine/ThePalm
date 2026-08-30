<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Accounting\Domain\Expense;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;

class CreateExpenseCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected ExpenseRepositoryInterface $expenseRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Expense
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $data = [
                'created_by' => $command->createdBy,
                'category' => $command->category,
                'description' => $command->description,
                'amount' => $command->amount,
                'expense_date' => $command->expenseDate,
                'receipt_url' => $command->receiptUrl,
                'notes' => $command->notes,
                'status' => $command->amount >= 1000 ? 'pending' : 'approved',
            ];

            return $this->expenseRepository->create($data);
        });
    }
}
