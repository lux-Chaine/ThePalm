<?php

namespace App\Models;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $user_type;
    public $email_verified_at;
    public $remember_token;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'user_type' => $this->user_type,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
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
