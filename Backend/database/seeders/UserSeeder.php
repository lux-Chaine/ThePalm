<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@palm.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'user_type' => 'staff',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@palm.com',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'user_type' => 'staff',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Receptionist User',
                'email' => 'receptionist@palm.com',
                'password' => Hash::make('receptionist123'),
                'role' => 'receptionist',
                'user_type' => 'staff',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
