<?php

namespace App\Modules\Accounting\Infrastructure;

use App\Modules\Accounting\Domain\Expense;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;

class EloquentExpenseRepository implements ExpenseRepositoryInterface
{
    public function findById(int $id): ?Expense
    {
        return Expense::find($id);
    }

    public function findByCategory(string $category): array
    {
        return Expense::where('category', $category)->get()->toArray();
    }

    public function findByStatus(string $status): array
    {
        return Expense::where('status', $status)->get()->toArray();
    }

    public function findByDateRange(string $startDate, string $endDate): array
    {
        return Expense::whereBetween('expense_date', [$startDate, $endDate])->get()->toArray();
    }

    public function findPendingApproval(): array
    {
        return Expense::where('status', 'pending')
            ->where('amount', '>=', 1000)
            ->get()
            ->toArray();
    }

    public function findAll(): array
    {
        return Expense::all()->toArray();
    }

    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense->fresh();
    }

    public function delete(Expense $expense): bool
    {
        return $expense->delete();
    }

    public function getTotalExpenses(string $startDate, string $endDate): float
    {
        return Expense::where('expense_date', '>=', $startDate)
            ->where('expense_date', '<=', $endDate)
            ->sum('amount');
    }

    public function getTotalByCategory(string $category, string $startDate, string $endDate): float
    {
        return Expense::where('category', $category)
            ->where('expense_date', '>=', $startDate)
            ->where('expense_date', '<=', $endDate)
            ->sum('amount');
    }

    public function getExpensesByMonth(int $year, int $month): array
    {
        return Expense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->get()
            ->toArray();
    }
}
