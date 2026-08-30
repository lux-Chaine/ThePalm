<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        // Single Rooms (5 rooms)
        for ($i = 1; $i <= 5; $i++) {
            $rooms[] = [
                'room_number' => sprintf('1%02d', $i),
                'type' => 'single',
                'price' => 400,
                'capacity' => 1,
                'status' => 'available',
                'floor' => 1,
                'description' => 'Comfortable single room with city view',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Double Rooms (10 rooms)
        for ($i = 1; $i <= 10; $i++) {
            $rooms[] = [
                'room_number' => sprintf('2%02d', $i),
                'type' => 'double',
                'price' => 600,
                'capacity' => 2,
                'status' => 'available',
                'floor' => $i <= 5 ? 2 : 3,
                'description' => 'Spacious double room with modern amenities',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Suite Rooms (5 rooms)
        for ($i = 1; $i <= 5; $i++) {
            $rooms[] = [
                'room_number' => sprintf('3%02d', $i),
                'type' => 'suite',
                'price' => 1200,
                'capacity' => 4,
                'status' => 'available',
                'floor' => 4,
                'description' => 'Luxury suite with separate living area',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('rooms')->insert($rooms);
    }
}
