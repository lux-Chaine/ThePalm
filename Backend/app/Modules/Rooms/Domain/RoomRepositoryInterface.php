<?php

namespace App\Modules\Rooms\Domain;

interface RoomRepositoryInterface
{
    public function findById(int $id): ?Room;
    public function findByRoomNumber(string $roomNumber): ?Room;
    public function findByType(string $type): array;
    public function findAvailable(): array;
    public function findAvailableForDates(string $checkIn, string $checkOut): array;
    public function findAll(): array;
    public function create(array $data): Room;
    public function update(Room $room, array $data): Room;
    public function delete(Room $room): bool;
    public function countByType(string $type): int;
    public function countByStatus(string $status): int;
}
