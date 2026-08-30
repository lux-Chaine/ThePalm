<?php

namespace App\Modules\Rooms\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Rooms\Domain\Room;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class CreateRoomCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected RoomRepositoryInterface $roomRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Room
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $data = [
                'room_number' => $command->roomNumber,
                'type' => $command->type,
                'price_per_night' => $command->pricePerNight,
                'floor' => $command->floor,
                'capacity' => $command->capacity,
                'description' => $command->description,
                'amenities' => $command->amenities,
                'status' => 'available',
            ];

            return $this->roomRepository->create($data);
        });
    }
}
