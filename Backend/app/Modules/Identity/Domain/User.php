<?php

namespace App\Modules\Identity\Domain;

use App\Models\User as EloquentUser;

class User extends EloquentUser
{
    // Domain-specific methods for Identity Module
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canManageReservations(): bool
    {
        return $this->isAdmin() || $this->isReceptionist();
    }

    public function canManageAccounting(): bool
    {
        return $this->isAdmin() || $this->isAccountant();
    }

    public function getPermissions(): array
    {
        $permissions = [
            'admin' => [
                'users.create',
                'users.read',
                'users.update',
                'users.delete',
                'reservations.create',
                'reservations.read',
                'reservations.update',
                'reservations.delete',
                'accounting.create',
                'accounting.read',
                'accounting.update',
                'accounting.delete',
            ],
            'receptionist' => [
                'reservations.create',
                'reservations.read',
                'reservations.update',
                'rooms.read',
                'guests.create',
                'guests.read',
            ],
            'accountant' => [
                'accounting.create',
                'accounting.read',
                'accounting.update',
                'invoices.create',
                'invoices.read',
                'invoices.update',
                'expenses.create',
                'expenses.read',
                'expenses.update',
            ],
        ];

        return $permissions[$this->role] ?? [];
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }
}
