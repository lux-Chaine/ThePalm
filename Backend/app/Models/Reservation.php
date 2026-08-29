<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guest_id',
        'room_id',
        'user_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'total_amount',
        'deposit_amount',
        'status',
        'special_requests',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Status helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getNumberOfNights(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function getRemainingAmount(): float
    {
        return $this->total_amount - $this->deposit_amount;
    }

    public function markAsConfirmed(): void
    {
        $this->status = 'confirmed';
        $this->room->markAsBooked();
        $this->save();
    }

    public function markAsCheckedIn(): void
    {
        $this->status = 'checked_in';
        $this->save();
    }

    public function markAsCheckedOut(): void
    {
        $this->status = 'checked_out';
        $this->room->markAsAvailable();
        $this->save();
    }

    public function cancel(string $reason = null): void
    {
        $this->status = 'cancelled';
        $this->cancellation_reason = $reason;
        $this->cancelled_at = now();
        $this->room->markAsAvailable();
        $this->save();
    }

    // Validation helpers
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeCheckedIn(): bool
    {
        return $this->status === 'confirmed' && $this->check_in_date->isToday() || $this->check_in_date->isPast();
    }

    public function canBeCheckedOut(): bool
    {
        return $this->status === 'checked_in' && ($this->check_out_date->isToday() || $this->check_out_date->isPast());
    }

    // Status label
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'checked_in' => 'تم تسجيل الدخول',
            'checked_out' => 'تم تسجيل الخروج',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }
}
