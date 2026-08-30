<?php

namespace App\Modules\Reservations\Infrastructure;

use App\Modules\Reservations\Domain\Reservation;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;

class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function findById(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function findByGuestId(int $guestId): array
    {
        return Reservation::where('guest_id', $guestId)->get()->toArray();
    }

    public function findByRoomId(int $roomId): array
    {
        return Reservation::where('room_id', $roomId)->get()->toArray();
    }

    public function findByUserId(int $userId): array
    {
        return Reservation::where('user_id', $userId)->get()->toArray();
    }

    public function findByStatus(string $status): array
    {
        return Reservation::where('status', $status)->get()->toArray();
    }

    public function findActive(): array
    {
        return Reservation::whereIn('status', ['confirmed', 'checked_in'])->get()->toArray();
    }

    public function findForDateRange(string $startDate, string $endDate): array
    {
        return Reservation::where('check_in', '>=', $startDate)
            ->where('check_out', '<=', $endDate)
            ->get()
            ->toArray();
    }

    public function findConflictingReservations(int $roomId, string $checkIn, string $checkOut): array
    {
        return Reservation::where('room_id', $roomId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->get()
            ->toArray();
    }

    public function findAll(): array
    {
        return Reservation::all()->toArray();
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation->fresh();
    }

    public function delete(Reservation $reservation): bool
    {
        return $reservation->delete();
    }

    public function countByStatus(string $status): int
    {
        return Reservation::where('status', $status)->count();
    }

    public function getRevenueForPeriod(string $startDate, string $endDate): float
    {
        return Reservation::where('check_in', '>=', $startDate)
            ->where('check_out', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
    }
}
