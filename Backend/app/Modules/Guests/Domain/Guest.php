<?php

namespace App\Modules\Guests\Domain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'identity_number',
        'identity_type',
        'date_of_birth',
        'address',
        'city',
        'country',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function reservations(): HasMany
    {
        return $this->hasMany(\App\Modules\Reservations\Domain\Reservation::class);
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
            'identity_number' => $this->identity_number,
            'identity_type' => $this->identity_type,
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

    public function getTotalReservations(): int
    {
        return $this->reservations()->count();
    }

    public function getActiveReservations(): int
    {
        return $this->reservations()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'identity_number' => $this->identity_number,
            'identity_type' => $this->identity_type,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'notes' => $this->notes,
            'total_reservations' => $this->getTotalReservations(),
            'active_reservations' => $this->getActiveReservations(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
