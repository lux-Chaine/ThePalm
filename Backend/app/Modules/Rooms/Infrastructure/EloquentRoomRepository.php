<?php

namespace App\Modules\Rooms\Infrastructure;

use App\Modules\Rooms\Domain\Room;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class EloquentRoomRepository implements RoomRepositoryInterface
{
    public function findById(int $id): ?Room
    {
        return Room::find($id);
    }

    public function findByRoomNumber(string $roomNumber): ?Room
    {
        return Room::where('room_number', $roomNumber)->first();
    }

    public function findByType(string $type): array
    {
        return Room::where('type', $type)->get()->toArray();
    }

    public function findAvailable(): array
    {
        return Room::where('status', 'available')->get()->toArray();
    }

    public function findAvailableForDates(string $checkIn, string $checkOut): array
    {
        // Find rooms that don't have reservations for the given dates
        $bookedRoomIds = \App\Models\Reservation::where('check_in', '<=', $checkOut)
            ->where('check_out', '>=', $checkIn)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->pluck('room_id')
            ->toArray();

        return Room::where('status', 'available')
            ->whereNotIn('id', $bookedRoomIds)
            ->get()
            ->toArray();
    }

    public function findAll(): array
    {
        return Room::all()->toArray();
    }

    public function create(array $data): Room
    {
        return Room::create($data);
    }

    public function update(Room $room, array $data): Room
    {
        $room->update($data);
        return $room->fresh();
    }

    public function delete(Room $room): bool
    {
        return $room->delete();
    }

    public function countByType(string $type): int
    {
        return Room::where('type', $type)->count();
    }

    public function countByStatus(string $status): int
    {
        return Room::where('status', $status)->count();
    }
}
