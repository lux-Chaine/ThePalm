<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->hasMany(Reservation::class);
    }

    // Helper methods
    public function getFullName(): string
    {
        return $this->name;
    }

    public function getContactInfo(): string
    {
        $contact = $this->phone;
        if ($this->email) {
            $contact .= ' | ' . $this->email;
        }
        return $contact;
    }

    public function hasActiveReservation(): bool
    {
        return $this->reservations()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    public function getTotalSpent(): float
    {
        return $this->reservations()
            ->where('status', 'checked_out')
            ->sum('total_amount');
    }

    public function getReservationsCount(): int
    {
        return $this->reservations()->count();
    }

    // Identity type label
    public function getIdentityTypeLabel(): string
    {
        return match($this->identity_type) {
            'national_id' => 'رقم قومي',
            'passport' => 'جواز سفر',
            'driving_license' => 'رخصة قيادة',
            default => $this->identity_type,
        };
    }
}
