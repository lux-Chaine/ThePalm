<?php

namespace App\Modules\Reports\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;

class GetOccupancyReportQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected RoomRepositoryInterface $roomRepository,
        protected ReservationRepositoryInterface $reservationRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        $rooms = $this->roomRepository->all();
        $reservations = $this->reservationRepository->all();

        // Filter reservations by date range
        $filteredReservations = $reservations->filter(function ($reservation) use ($query) {
            return $reservation->check_in >= $query->startDate && $reservation->check_in <= $query->endDate;
        });

        $totalRooms = $rooms->count();
        $occupiedRooms = $filteredReservations->whereIn('status', ['confirmed', 'checked_in'])->count();
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Calculate by room type
        $roomsByType = $rooms->groupBy('type')->map(function ($roomsOfType) use ($filteredReservations) {
            $typeTotal = $roomsOfType->count();
            $typeOccupied = $filteredReservations->whereIn('room_id', $roomsOfType->pluck('id'))
                ->whereIn('status', ['confirmed', 'checked_in'])->count();
            
            return [
                'total' => $typeTotal,
                'occupied' => $typeOccupied,
                'occupancy_rate' => $typeTotal > 0 ? ($typeOccupied / $typeTotal) * 100 : 0
            ];
        });

        return [
            'period' => [
                'start_date' => $query->startDate,
                'end_date' => $query->endDate
            ],
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'available_rooms' => $totalRooms - $occupiedRooms,
            'occupancy_rate' => round($occupancyRate, 2),
            'by_room_type' => $roomsByType->toArray()
        ];
    }
}
