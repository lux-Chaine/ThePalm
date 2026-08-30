<?php

namespace App\Modules\Reservations\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;

class GetAllReservationsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        if ($query->guestId) {
            return $this->reservationRepository->findByGuestId($query->guestId);
        }

        if ($query->roomId) {
            return $this->reservationRepository->findByRoomId($query->roomId);
        }

        if ($query->userId) {
            return $this->reservationRepository->findByUserId($query->userId);
        }

        if ($query->status) {
            return $this->reservationRepository->findByStatus($query->status);
        }

        if ($query->startDate && $query->endDate) {
            return $this->reservationRepository->findForDateRange($query->startDate, $query->endDate);
        }

        return $this->reservationRepository->findAll();
    }
}
