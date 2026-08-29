<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'reservation_id',
        'created_by',
        'amount',
        'paid_amount',
        'discount_amount',
        'tax_amount',
        'payment_status',
        'payment_method',
        'due_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    // Relationships
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Status helpers
    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    public function isPartial(): bool
    {
        return $this->payment_status === 'partial';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->payment_status === 'overdue';
    }

    public function getRemainingAmount(): float
    {
        return $this->amount - $this->paid_amount;
    }

    public function getOverdueDays(): int
    {
        if ($this->isPaid() || $this->due_date->isFuture()) {
            return 0;
        }
        return $this->due_date->diffInDays(now());
    }

    public function markAsPartial(float $amount): void
    {
        $this->paid_amount += $amount;
        $this->payment_status = $this->paid_amount >= $this->amount ? 'paid' : 'partial';
        if ($this->payment_status === 'paid') {
            $this->paid_date = now();
        }
        $this->save();
    }

    public function markAsPaid(): void
    {
        $this->paid_amount = $this->amount;
        $this->payment_status = 'paid';
        $this->paid_date = now();
        $this->save();
    }

    public function markAsOverdue(): void
    {
        if ($this->due_date->isPast() && !$this->isPaid()) {
            $this->payment_status = 'overdue';
            $this->save();
        }
    }

    // Payment status label
    public function getPaymentStatusLabel(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'غير مدفوع',
            'partial' => 'مدفوع جزئياً',
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            default => $this->payment_status,
        };
    }

    // Payment method label
    public function getPaymentMethodLabel(): string
    {
        return match($this->payment_method) {
            'cash' => 'نقداً',
            'credit_card' => 'بطاقة ائتمان',
            'bank_transfer' => 'تحويل بنكي',
            'online' => 'دفع إلكتروني',
            default => $this->payment_method ?? 'غير محدد',
        };
    }

    // Generate invoice number
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }
}
