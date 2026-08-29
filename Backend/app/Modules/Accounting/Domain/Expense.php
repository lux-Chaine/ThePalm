<?php

namespace App\Modules\Accounting\Domain;

use App\Models\Expense as EloquentExpense;

class Expense extends EloquentExpense
{
    // Domain-specific methods for Accounting Module
    public function isRecurring(): bool
    {
        // Check if this is a recurring expense (salaries, utilities, etc.)
        return in_array($this->category, ['salaries', 'utilities', 'insurance']);
    }

    public function isCapitalExpense(): bool
    {
        // Check if this is a capital expenditure (large investments)
        return $this->amount >= 10000;
    }

    public function requiresApproval(): bool
    {
        // Expenses over 1000 require approval
        return $this->amount >= 1000;
    }

    public function canBeApproved(): bool
    {
        return $this->isPending() && $this->requiresApproval();
    }

    public function getTaxDeductibleAmount(): float
    {
        // Calculate tax-deductible portion based on category
        $deductibleCategories = ['maintenance', 'supplies', 'utilities', 'insurance'];
        
        if (in_array($this->category, $deductibleCategories)) {
            return $this->amount; // Fully deductible
        }

        if ($this->category === 'marketing') {
            return $this->amount * 0.50; // 50% deductible
        }

        return 0; // Not deductible
    }

    public function getCategoryPriority(): int
    {
        // Priority for payment: higher priority = should be paid first
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
        // Check if expense can be reimbursed to employee
        return in_array($this->category, ['supplies', 'maintenance', 'cleaning']);
    }
}
