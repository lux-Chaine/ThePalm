<?php

namespace App\Modules\Reservations\Domain;

class Reservation
{
    public ?int $id = null;
    public int $guestId;
    public int $roomId;
    public int $userId;
    public string $checkInDate;
    public string $checkOutDate;
    public int $numberOfGuests = 1;
    public ?string $specialRequests = null;
    public string $status = 'pending';
    public ?string $cancellationReason = null;
    public ?float $totalAmount = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Domain-specific methods for Reservations Module
    public function canBeModified(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeCancelled(): bool
    {
        return $this->canBeModified() && !$this->isPastCheckIn();
    }

    public function isPastCheckIn(): bool
    {
        return strtotime($this->checkInDate) < time();
    }

    public function isPastCheckOut(): bool
    {
        return strtotime($this->checkOutDate) < time();
    }

    public function getDuration(): int
    {
        $checkIn = new \DateTime($this->checkInDate);
        $checkOut = new \DateTime($this->checkOutDate);
        return (int) $checkIn->diff($checkOut)->days;
    }

    public function calculateTotalAmount(float $roomPrice): float
    {
        return $roomPrice * $this->getDuration();
    }

    public function isOverlappingWith(Reservation $other): bool
    {
        return (strtotime($this->checkInDate) < strtotime($other->checkOutDate) && 
                strtotime($this->checkOutDate) > strtotime($other->checkInDate));
    }

    public function requiresDeposit(): bool
    {
        return $this->getDuration() > 3;
    }

    public function getDepositAmount(): float
    {
        if (!$this->requiresDeposit() || !$this->totalAmount) {
            return 0;
        }
        
        return $this->totalAmount * 0.20;
    }

    public function getConfirmationDeadline(): \DateTime
    {
        $checkIn = new \DateTime($this->checkInDate);
        return $checkIn->sub(new \DateInterval('PT24H'));
    }

    public function isPastConfirmationDeadline(): bool
    {
        return new \DateTime() > $this->getConfirmationDeadline();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'guest_id' => $this->guestId,
            'room_id' => $this->roomId,
            'user_id' => $this->userId,
            'check_in_date' => $this->checkInDate,
            'check_out_date' => $this->checkOutDate,
            'number_of_guests' => $this->numberOfGuests,
            'special_requests' => $this->specialRequests,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellationReason,
            'total_amount' => $this->totalAmount,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
