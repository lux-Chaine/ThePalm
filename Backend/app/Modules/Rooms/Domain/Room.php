<?php

namespace App\Modules\Rooms\Domain;

class Room
{
    public ?int $id = null;
    public string $roomNumber;
    public string $type;
    public float $pricePerNight;
    public string $status = 'available';
    public int $floor = 1;
    public int $capacity = 2;
    public ?string $description = null;
    public ?array $amenities = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
    public ?int $totalReservations = 0;
    public ?int $activeReservations = 0;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    // Domain-specific methods for Rooms Module
    public function getAvailabilityStatus(): string
    {
        if ($this->status === 'maintenance') {
            return 'maintenance';
        }

        if ($this->status === 'cleaning') {
            return 'cleaning';
        }

        if ($this->status === 'booked') {
            return 'booked';
        }

        return 'available';
    }

    public function calculateTotalPrice(int $nights): float
    {
        return $this->pricePerNight * $nights;
    }

    public function canBeBooked(): bool
    {
        return $this->status === 'available';
    }

    public function requiresMaintenance(): bool
    {
        return false;
    }

    public function getOccupancyRate(): float
    {
        $totalReservations = $this->totalReservations ?? 0;
        $activeReservations = $this->activeReservations ?? 0;

        return $totalReservations > 0 ? ($activeReservations / $totalReservations) * 100 : 0;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'room_number' => $this->roomNumber,
            'type' => $this->type,
            'price_per_night' => $this->pricePerNight,
            'status' => $this->status,
            'floor' => $this->floor,
            'capacity' => $this->capacity,
            'description' => $this->description,
            'amenities' => $this->amenities,
            'total_reservations' => $this->totalReservations ?? 0,
            'active_reservations' => $this->activeReservations ?? 0,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
