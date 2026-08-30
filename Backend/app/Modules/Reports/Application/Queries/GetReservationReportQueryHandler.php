<?php

namespace App\Modules\Reports\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;

class GetReservationReportQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        $reservations = $this->reservationRepository->all();

        // Filter by date range
        $filteredReservations = $reservations->filter(function ($reservation) use ($query) {
            return $reservation->created_at >= $query->startDate && $reservation->created_at <= $query->endDate;
        });

        // Filter by status if specified
        if ($query->status) {
            $filteredReservations = $filteredReservations->where('status', $query->status);
        }

        // Calculate statistics
        $totalReservations = $filteredReservations->count();
        $confirmed = $filteredReservations->where('status', 'confirmed')->count();
        $checkedIn = $filteredReservations->where('status', 'checked_in')->count();
        $checkedOut = $filteredReservations->where('status', 'checked_out')->count();
        $cancelled = $filteredReservations->where('status', 'cancelled')->count();

        return [
            'period' => [
                'start_date' => $query->startDate,
                'end_date' => $query->endDate
            ],
            'total_reservations' => $totalReservations,
            'by_status' => [
                'confirmed' => $confirmed,
                'checked_in' => $checkedIn,
                'checked_out' => $checkedOut,
                'cancelled' => $cancelled
            ],
            'reservations' => $filteredReservations->toArray()
        ];
    }
}
