<?php

namespace App\Modules\Rooms\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class GetRoomByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected RoomRepositoryInterface $roomRepository
    ) {}

    public function handle(QueryInterface $query): ?array
    {
        $room = $this->roomRepository->findById($query->id);
        return $room ? $room->toArray() : null;
    }
}
