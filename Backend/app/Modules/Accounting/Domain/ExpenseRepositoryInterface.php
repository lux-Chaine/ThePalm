<?php

namespace App\Modules\Accounting\Domain;

interface ExpenseRepositoryInterface
{
    public function findById(int $id): ?Expense;
    public function findByCategory(string $category): array;
    public function findByStatus(string $status): array;
    public function findByDateRange(string $startDate, string $endDate): array;
    public function findPendingApproval(): array;
    public function findAll(): array;
    public function create(array $data): Expense;
    public function update(Expense $expense, array $data): Expense;
    public function delete(Expense $expense): bool;
    public function getTotalExpenses(string $startDate, string $endDate): float;
    public function getTotalByCategory(string $category, string $startDate, string $endDate): float;
    public function getExpensesByMonth(int $year, int $month): array;
}
