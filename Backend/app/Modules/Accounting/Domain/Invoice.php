<?php

namespace App\Modules\Accounting\Domain;

class Invoice
{
    public ?int $id = null;
    public int $reservationId;
    public int $createdBy;
    public float $amount;
    public ?string $dueDate = null;
    public ?float $discountAmount = null;
    public ?float $taxAmount = null;
    public ?string $paymentMethod = null;
    public string $paymentStatus = 'unpaid';
    public ?float $paidAmount = 0;
    public ?string $notes = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Domain-specific methods for Accounting Module
    public function isFullyPaid(): bool
    {
        return $this->paidAmount >= $this->amount;
    }

    public function isPartiallyPaid(): bool
    {
        return $this->paidAmount > 0 && $this->paidAmount < $this->amount;
    }

    public function getPaymentProgress(): float
    {
        if ($this->amount == 0) return 0;
        return ($this->paidAmount / $this->amount) * 100;
    }

    public function calculateLateFee(float $dailyRate = 0.01): float
    {
        if ($this->isFullyPaid() || !$this->isOverdue()) {
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
        return $this->paymentStatus === 'unpaid' && !$this->isPastDueDate();
    }

    public function isPastDueDate(): bool
    {
        if (!$this->dueDate) return false;
        return strtotime($this->dueDate) < time();
    }

    public function isOverdue(): bool
    {
        return $this->paymentStatus === 'overdue' || ($this->paymentStatus !== 'paid' && $this->isPastDueDate());
    }

    public function getOverdueDays(): int
    {
        if (!$this->dueDate || !$this->isPastDueDate()) return 0;
        $dueDate = new \DateTime($this->dueDate);
        $now = new \DateTime();
        return (int) $now->diff($dueDate)->days;
    }

    public function getRemainingAmount(): float
    {
        return max(0, $this->amount - $this->paidAmount);
    }

    public function generatePaymentSchedule(): array
    {
        if ($this->amount < 1000) {
            return [
                [
                    'amount' => $this->amount,
                    'due_date' => $this->dueDate,
                    'status' => 'pending'
                ]
            ];
        }

        $amountPerPayment = $this->amount / 3;
        $schedule = [];
        
        for ($i = 0; $i < 3; $i++) {
            $dueDate = new \DateTime($this->dueDate);
            $dueDate->add(new \DateInterval('P' . ($i * 30) . 'D'));
            
            $schedule[] = [
                'amount' => $amountPerPayment,
                'due_date' => $dueDate->format('Y-m-d'),
                'status' => 'pending'
            ];
        }

        return $schedule;
    }

    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'reservation_id' => $this->reservationId,
            'amount' => $this->amount,
            'paid_amount' => $this->paidAmount,
            'remaining' => $this->getRemainingAmount(),
            'status' => $this->paymentStatus,
            'due_date' => $this->dueDate,
            'is_overdue' => $this->isOverdue(),
            'days_overdue' => $this->getOverdueDays(),
            'payment_progress' => $this->getPaymentProgress(),
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'reservation_id' => $this->reservationId,
            'created_by' => $this->createdBy,
            'amount' => $this->amount,
            'due_date' => $this->dueDate,
            'discount_amount' => $this->discountAmount,
            'tax_amount' => $this->taxAmount,
            'payment_method' => $this->paymentMethod,
            'payment_status' => $this->paymentStatus,
            'paid_amount' => $this->paidAmount,
            'notes' => $this->notes,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
