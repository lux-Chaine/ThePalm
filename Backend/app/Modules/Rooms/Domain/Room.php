<?php

namespace App\Modules\Rooms\Domain;

use App\Models\Room as EloquentRoom;

class Room extends EloquentRoom
{
    // Domain-specific methods for Rooms Module
    public function getAvailabilityStatus(): string
    {
        if ($this->isUnderMaintenance()) {
            return 'maintenance';
        }

        if ($this->isCleaning()) {
            return 'cleaning';
        }

        if ($this->isBooked()) {
            return 'booked';
        }

        return 'available';
    }

    public function calculateTotalPrice(int $nights): float
    {
        return $this->price_per_night * $nights;
    }

    public function canBeBooked(): bool
    {
        return $this->isAvailable();
    }

    public function requiresMaintenance(): bool
    {
        // Logic to determine if room needs maintenance
        return false; // Implementation depends on business rules
    }

    public function getOccupancyRate(): float
    {
        $totalReservations = $this->reservations()->count();
        $activeReservations = $this->reservations()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();

        return $totalReservations > 0 ? ($activeReservations / $totalReservations) * 100 : 0;
    }
}
