<?php

namespace App\Modules\Reservations\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;

class GetReservationByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository
    ) {}

    public function handle(QueryInterface $query): ?array
    {
        $reservation = $this->reservationRepository->findById($query->id);
        return $reservation ? $reservation->toArray() : null;
    }
}
