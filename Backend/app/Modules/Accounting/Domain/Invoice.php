<?php

namespace App\Modules\Accounting\Domain;

use App\Models\Invoice as EloquentInvoice;

class Invoice extends EloquentInvoice
{
    // Domain-specific methods for Accounting Module
    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->amount;
    }

    public function isPartiallyPaid(): bool
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->amount;
    }

    public function getPaymentProgress(): float
    {
        if ($this->amount == 0) return 0;
        return ($this->paid_amount / $this->amount) * 100;
    }

    public function calculateLateFee(float $dailyRate = 0.01): float
    {
        if ($this->isPaid() || !$this->due_date->isPast()) {
            return 0;
        }

        $daysOverdue = $this->getOverdueDays();
        return $this->getRemainingAmount() * $dailyRate * $daysOverdue;
    }

    public function getTotalWithLateFee(): float
    {
        return $this->amount + $this->calculateLateFee();
    }

    public function canBePaid(): bool
    {
        return !$this->isFullyPaid();
    }

    public function canBeCancelled(): bool
    {
        return $this->isUnpaid() && $this->due_date->isFuture();
    }

    public function generatePaymentSchedule(): array
    {
        // Generate payment schedule for large invoices
        if ($this->amount < 1000) {
            return [
                [
                    'amount' => $this->amount,
                    'due_date' => $this->due_date->format('Y-m-d'),
                    'status' => 'pending'
                ]
            ];
        }

        // Split into 3 payments for large amounts
        $amountPerPayment = $this->amount / 3;
        $schedule = [];
        
        for ($i = 0; $i < 3; $i++) {
            $schedule[] = [
                'amount' => $amountPerPayment,
                'due_date' => $this->due_date->addDays($i * 30)->format('Y-m-d'),
                'status' => 'pending'
            ];
        }

        return $schedule;
    }

    public function getSummary(): array
    {
        return [
            'invoice_number' => $this->invoice_number,
            'amount' => $this->amount,
            'paid_amount' => $this->paid_amount,
            'remaining' => $this->getRemainingAmount(),
            'status' => $this->payment_status,
            'due_date' => $this->due_date->format('Y-m-d'),
            'is_overdue' => $this->isOverdue(),
            'days_overdue' => $this->getOverdueDays(),
            'payment_progress' => $this->getPaymentProgress(),
        ];
    }
}
