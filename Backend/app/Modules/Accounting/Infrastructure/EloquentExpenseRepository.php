<?php

namespace App\Modules\Accounting\Infrastructure;

use App\Modules\Accounting\Domain\Expense;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;
use PDO;

class EloquentExpenseRepository implements ExpenseRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(
            'mysql:host=localhost;dbname=palm_hotel;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function findById(int $id): ?Expense
    {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToExpense($data);
    }

    public function findByCategory(string $category): array
    {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE category = ?");
        $stmt->execute([$category]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToExpense'], $data);
    }

    public function findByStatus(string $status): array
    {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE status = ?");
        $stmt->execute([$status]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToExpense'], $data);
    }

    public function findByDateRange(string $startDate, string $endDate): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM expenses WHERE expense_date BETWEEN ? AND ?"
        );
        $stmt->execute([$startDate, $endDate]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToExpense'], $data);
    }

    public function findPendingApproval(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM expenses WHERE status = 'pending' AND amount >= 1000"
        );
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToExpense'], $data);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM expenses");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToExpense'], $data);
    }

    public function create(array $data): Expense
    {
        $stmt = $this->db->prepare(
            "INSERT INTO expenses (created_by, category, description, amount, expense_date, status, receipt_url, notes, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        
        $stmt->execute([
            $data['created_by'],
            $data['category'],
            $data['description'],
            $data['amount'],
            $data['expense_date'] ?? null,
            $data['status'] ?? 'pending',
            $data['receipt_url'] ?? null,
            $data['notes'] ?? null
        ]);

        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(Expense $expense, array $data): Expense
    {
        $stmt = $this->db->prepare(
            "UPDATE expenses SET created_by = ?, category = ?, description = ?, amount = ?, 
             expense_date = ?, status = ?, receipt_url = ?, notes = ?, rejection_reason = ?, updated_at = NOW() 
             WHERE id = ?"
        );
        
        $stmt->execute([
            $data['created_by'] ?? $expense->createdBy,
            $data['category'] ?? $expense->category,
            $data['description'] ?? $expense->description,
            $data['amount'] ?? $expense->amount,
            $data['expense_date'] ?? $expense->expenseDate,
            $data['status'] ?? $expense->status,
            $data['receipt_url'] ?? $expense->receiptUrl,
            $data['notes'] ?? $expense->notes,
            $data['rejection_reason'] ?? $expense->rejectionReason,
            $expense->id
        ]);

        return $this->findById($expense->id);
    }

    public function delete(Expense $expense): bool
    {
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ?");
        return $stmt->execute([$expense->id]);
    }

    public function getTotalExpenses(string $startDate, string $endDate): float
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) FROM expenses WHERE expense_date >= ? AND expense_date <= ?"
        );
        $stmt->execute([$startDate, $endDate]);
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function getTotalByCategory(string $category, string $startDate, string $endDate): float
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) FROM expenses WHERE category = ? AND expense_date >= ? AND expense_date <= ?"
        );
        $stmt->execute([$category, $startDate, $endDate]);
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function getExpensesByMonth(int $year, int $month): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM expenses WHERE YEAR(expense_date) = ? AND MONTH(expense_date) = ?"
        );
        $stmt->execute([$year, $month]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToExpense'], $data);
    }

    private function mapToExpense(array $data): Expense
    {
        return new Expense([
            'id' => (int) $data['id'],
            'createdBy' => (int) $data['created_by'],
            'category' => $data['category'],
            'description' => $data['description'],
            'amount' => (float) $data['amount'],
            'expenseDate' => $data['expense_date'],
            'status' => $data['status'],
            'receiptUrl' => $data['receipt_url'],
            'notes' => $data['notes'],
            'rejectionReason' => $data['rejection_reason'],
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at'],
        ]);
    }
}
