<?php

namespace App\Modules\Accounting\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;

class GetAllExpensesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ExpenseRepositoryInterface $expenseRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        if ($query->category) {
            return $this->expenseRepository->findByCategory($query->category);
        }

        if ($query->status) {
            return $this->expenseRepository->findByStatus($query->status);
        }

        if ($query->startDate && $query->endDate) {
            return $this->expenseRepository->findByDateRange($query->startDate, $query->endDate);
        }

        return $this->expenseRepository->findAll();
    }
}
