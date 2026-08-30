<?php

namespace App\Modules\Guests\Domain;

class Guest
{
    public ?int $id = null;
    public string $name;
    public ?string $email = null;
    public string $phone;
    public ?string $identityNumber = null;
    public string $identityType = 'national_id';
    public ?string $dateOfBirth = null;
    public ?string $address = null;
    public ?string $city = null;
    public string $country = 'Egypt';
    public ?string $notes = null;
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

    // Domain methods
    public function getFullName(): string
    {
        return $this->name;
    }

    public function getContactInfo(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    public function getIdentityInfo(): array
    {
        return [
            'identity_number' => $this->identityNumber,
            'identity_type' => $this->identityType,
        ];
    }

    public function getLocation(): array
    {
        return [
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'identity_number' => $this->identityNumber,
            'identity_type' => $this->identityType,
            'date_of_birth' => $this->dateOfBirth,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'notes' => $this->notes,
            'total_reservations' => $this->totalReservations ?? 0,
            'active_reservations' => $this->activeReservations ?? 0,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
