<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function createdInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = [
            'admin' => ['users.manage', 'rooms.manage', 'reservations.manage', 'accounting.manage'],
            'receptionist' => ['rooms.view', 'reservations.manage', 'reservations.view'],
            'accountant' => ['accounting.manage', 'invoices.manage', 'expenses.manage'],
        ];

        return in_array($permission, $permissions[$this->role] ?? []);
    }
}
