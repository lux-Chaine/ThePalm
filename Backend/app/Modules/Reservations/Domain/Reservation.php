<?php

namespace App\Modules\Reservations\Domain;

use App\Models\Reservation as EloquentReservation;

class Reservation extends EloquentReservation
{
    // Domain-specific methods for Reservations Module
    public function canBeModified(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeCancelled(): bool
    {
        return $this->canBeModified() && $this->check_in_date->isFuture();
    }

    public function isPastCheckIn(): bool
    {
        return $this->check_in_date->isPast();
    }

    public function isPastCheckOut(): bool
    {
        return $this->check_out_date->isPast();
    }

    public function getDuration(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function calculateTotalAmount(float $roomPrice): float
    {
        return $roomPrice * $this->getDuration();
    }

    public function isOverlappingWith(Reservation $other): bool
    {
        return ($this->check_in_date < $other->check_out_date && 
                $this->check_out_date > $other->check_in_date);
    }

    public function requiresDeposit(): bool
    {
        // Business rule: reservations over 3 nights require deposit
        return $this->getDuration() > 3;
    }

    public function getDepositAmount(): float
    {
        if (!$this->requiresDeposit()) {
            return 0;
        }
        
        // 20% of total amount as deposit
        return $this->total_amount * 0.20;
    }

    public function getConfirmationDeadline(): \DateTime
    {
        // Reservation must be confirmed 24 hours before check-in
        return $this->check_in_date->subHours(24);
    }

    public function isPastConfirmationDeadline(): bool
    {
        return now()->isAfter($this->getConfirmationDeadline());
    }
}
