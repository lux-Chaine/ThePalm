<?php

namespace App\Modules\Accounting\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;

class GetExpenseByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ExpenseRepositoryInterface $expenseRepository
    ) {}

    public function handle(QueryInterface $query): ?array
    {
        $expense = $this->expenseRepository->findById($query->id);
        return $expense ? $expense->toArray() : null;
    }
}
