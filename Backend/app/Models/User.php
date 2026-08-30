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
        'user_type',
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

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function isHousekeeping(): bool
    {
        return $this->role === 'housekeeping';
    }

    public function isMaintenance(): bool
    {
        return $this->role === 'maintenance';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isEmployee(): bool
    {
        return $this->user_type === 'employee';
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = [
            'admin' => ['users.manage', 'roles.manage', 'rooms.manage', 'reservations.manage', 'accounting.manage', 'reports.view', 'settings.manage'],
            'manager' => ['users.view', 'rooms.manage', 'reservations.manage', 'accounting.manage', 'reports.view'],
            'receptionist' => ['rooms.view', 'reservations.manage', 'reservations.view', 'guests.view', 'guests.manage'],
            'housekeeping' => ['rooms.view', 'rooms.update_status', 'reservations.view'],
            'maintenance' => ['rooms.view', 'rooms.update_status', 'expenses.create', 'expenses.view'],
            'accountant' => ['accounting.manage', 'invoices.manage', 'expenses.manage', 'reports.view'],
        ];

        return in_array($permission, $permissions[$this->role] ?? []);
    }

    public function getPermissions(): array
    {
        $permissions = [
            'admin' => ['users.manage', 'roles.manage', 'rooms.manage', 'reservations.manage', 'accounting.manage', 'reports.view', 'settings.manage'],
            'manager' => ['users.view', 'rooms.manage', 'reservations.manage', 'accounting.manage', 'reports.view'],
            'receptionist' => ['rooms.view', 'reservations.manage', 'reservations.view', 'guests.view', 'guests.manage'],
            'housekeeping' => ['rooms.view', 'rooms.update_status', 'reservations.view'],
            'maintenance' => ['rooms.view', 'rooms.update_status', 'expenses.create', 'expenses.view'],
            'accountant' => ['accounting.manage', 'invoices.manage', 'expenses.manage', 'reports.view'],
        ];

        return $permissions[$this->role] ?? [];
    }
}
