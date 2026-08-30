<?php

namespace App\Modules\Accounting\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Accounting\Domain\Expense;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;

class UpdateExpenseStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected ExpenseRepositoryInterface $expenseRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Expense
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $expense = $this->expenseRepository->findById($command->expenseId);
            
            if (!$expense) {
                throw new \Exception("Expense not found");
            }

            $data = ['status' => $command->status];

            if ($command->rejectionReason) {
                $data['rejection_reason'] = $command->rejectionReason;
            }

            return $this->expenseRepository->update($expense, $data);
        });
    }
}
