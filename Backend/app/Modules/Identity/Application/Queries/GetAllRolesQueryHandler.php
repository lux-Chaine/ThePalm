<?php

namespace App\Modules\Identity\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;

class GetAllRolesQueryHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): array
    {
        $roles = [
            'admin' => [
                'name' => 'Administrator',
                'description' => 'Full access to all system features',
                'permissions' => [
                    'users.manage',
                    'roles.manage',
                    'rooms.manage',
                    'reservations.manage',
                    'accounting.manage',
                    'reports.view',
                    'settings.manage'
                ]
            ],
            'manager' => [
                'name' => 'Manager',
                'description' => 'Manage operations and view reports',
                'permissions' => [
                    'users.view',
                    'rooms.manage',
                    'reservations.manage',
                    'accounting.manage',
                    'reports.view'
                ]
            ],
            'receptionist' => [
                'name' => 'Receptionist',
                'description' => 'Manage reservations and guests',
                'permissions' => [
                    'rooms.view',
                    'reservations.manage',
                    'reservations.view',
                    'guests.view',
                    'guests.manage'
                ]
            ],
            'housekeeping' => [
                'name' => 'Housekeeping',
                'description' => 'Manage room cleaning status',
                'permissions' => [
                    'rooms.view',
                    'rooms.update_status',
                    'reservations.view'
                ]
            ],
            'maintenance' => [
                'name' => 'Maintenance',
                'description' => 'Manage room maintenance and expenses',
                'permissions' => [
                    'rooms.view',
                    'rooms.update_status',
                    'expenses.create',
                    'expenses.view'
                ]
            ],
            'accountant' => [
                'name' => 'Accountant',
                'description' => 'Manage accounting and financial reports',
                'permissions' => [
                    'accounting.manage',
                    'invoices.manage',
                    'expenses.manage',
                    'reports.view'
                ]
            ]
        ];

        return $roles;
    }
}
