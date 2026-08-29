<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'category',
        'description',
        'amount',
        'expense_date',
        'status',
        'receipt_url',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    // Relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Status helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(): void
    {
        $this->status = 'approved';
        $this->save();
    }

    public function reject(): void
    {
        $this->status = 'rejected';
        $this->save();
    }

    // Category label
    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'maintenance' => 'صيانة',
            'supplies' => 'مستلزمات',
            'salaries' => 'رواتب',
            'utilities' => 'مرافق',
            'marketing' => 'تسويق',
            'insurance' => 'تأمين',
            'cleaning' => 'تنظيف',
            'food_beverage' => 'طعام ومشروبات',
            'laundry' => 'غسيل',
            'other' => 'أخرى',
            default => $this->category,
        };
    }

    // Status label
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            default => $this->status,
        };
    }

    // Scope for monthly expenses
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month);
    }

    // Scope for category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for approved expenses
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
