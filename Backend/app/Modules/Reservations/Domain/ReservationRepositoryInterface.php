<?php

namespace App\Modules\Reservations\Domain;

interface ReservationRepositoryInterface
{
    public function findById(int $id): ?Reservation;
    public function findByGuestId(int $guestId): array;
    public function findByRoomId(int $roomId): array;
    public function findByUserId(int $userId): array;
    public function findByStatus(string $status): array;
    public function findActive(): array;
    public function findForDateRange(string $startDate, string $endDate): array;
    public function findConflictingReservations(int $roomId, string $checkIn, string $checkOut): array;
    public function findAll(): array;
    public function create(array $data): Reservation;
    public function update(Reservation $reservation, array $data): Reservation;
    public function delete(Reservation $reservation): bool;
    public function countByStatus(string $status): int;
    public function getRevenueForPeriod(string $startDate, string $endDate): float;
}
