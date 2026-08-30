<?php

namespace App\Modules\Accounting\Domain;

class Expense
{
    public ?int $id = null;
    public int $createdBy;
    public string $category;
    public string $description;
    public float $amount;
    public ?string $expenseDate = null;
    public string $status = 'pending';
    public ?string $receiptUrl = null;
    public ?string $notes = null;
    public ?string $rejectionReason = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Domain-specific methods for Accounting Module
    public function isRecurring(): bool
    {
        return in_array($this->category, ['salaries', 'utilities', 'insurance']);
    }

    public function isCapitalExpense(): bool
    {
        return $this->amount >= 10000;
    }

    public function requiresApproval(): bool
    {
        return $this->amount >= 1000;
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending' && $this->requiresApproval();
    }

    public function getTaxDeductibleAmount(): float
    {
        $deductibleCategories = ['maintenance', 'supplies', 'utilities', 'insurance'];
        
        if (in_array($this->category, $deductibleCategories)) {
            return $this->amount;
        }

        if ($this->category === 'marketing') {
            return $this->amount * 0.50;
        }

        return 0;
    }

    public function getCategoryPriority(): int
    {
        return match($this->category) {
            'salaries' => 1,
            'utilities' => 2,
            'insurance' => 3,
            'maintenance' => 4,
            'supplies' => 5,
            'cleaning' => 6,
            'food_beverage' => 7,
            'marketing' => 8,
            'laundry' => 9,
            'other' => 10,
            default => 10,
        };
    }

    public function getBudgetImpact(): array
    {
        return [
            'amount' => $this->amount,
            'category' => $this->category,
            'priority' => $this->getCategoryPriority(),
            'requires_approval' => $this->requiresApproval(),
            'tax_deductible' => $this->getTaxDeductibleAmount(),
            'is_recurring' => $this->isRecurring(),
        ];
    }

    public function canBeReimbursed(): bool
    {
        return in_array($this->category, ['supplies', 'maintenance', 'cleaning']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created_by' => $this->createdBy,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'expense_date' => $this->expenseDate,
            'status' => $this->status,
            'receipt_url' => $this->receiptUrl,
            'notes' => $this->notes,
            'rejection_reason' => $this->rejectionReason,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
