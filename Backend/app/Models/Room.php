<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_number',
        'type',
        'price_per_night',
        'status',
        'floor',
        'capacity',
        'description',
        'amenities',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'amenities' => 'array',
    ];

    // Relationships
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // Status helpers
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }

    public function isUnderMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    public function isCleaning(): bool
    {
        return $this->status === 'cleaning';
    }

    public function markAsAvailable(): void
    {
        $this->status = 'available';
        $this->save();
    }

    public function markAsBooked(): void
    {
        $this->status = 'booked';
        $this->save();
    }

    public function markAsMaintenance(): void
    {
        $this->status = 'maintenance';
        $this->save();
    }

    public function markAsCleaning(): void
    {
        $this->status = 'cleaning';
        $this->save();
    }

    // Availability check for specific dates
    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        return !$this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'checked_out')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->exists();
    }

    // Get room type label
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'Single' => 'غرفة فردية',
            'Double' => 'غرفة مزدوجة',
            'Suite' => 'جناح',
            'Deluxe' => 'ديلوكس',
            'Presidential' => 'رئاسي',
            default => $this->type,
        };
    }

    // Get status label
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'available' => 'متاحة',
            'booked' => 'محجوزة',
            'maintenance' => 'تحت الصيانة',
            'cleaning' => 'تنظيف',
            default => $this->status,
        };
    }
}
