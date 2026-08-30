<?php

namespace App\Modules\Rooms\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class GetAllRoomsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected RoomRepositoryInterface $roomRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        if ($query->checkIn && $query->checkOut) {
            return $this->roomRepository->findAvailableForDates($query->checkIn, $query->checkOut);
        }

        if ($query->status === 'available') {
            return $this->roomRepository->findAvailable();
        }

        if ($query->type) {
            return $this->roomRepository->findByType($query->type);
        }

        return $this->roomRepository->findAll();
    }
}
