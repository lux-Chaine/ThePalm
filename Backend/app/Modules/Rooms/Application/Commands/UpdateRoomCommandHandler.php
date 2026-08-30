<?php

namespace App\Modules\Rooms\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Rooms\Domain\Room;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class UpdateRoomCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected RoomRepositoryInterface $roomRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Room
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $room = $this->roomRepository->findById($command->roomId);
            
            if (!$room) {
                throw new \Exception("Room not found");
            }

            $data = array_filter([
                'room_number' => $command->roomNumber,
                'type' => $command->type,
                'price_per_night' => $command->pricePerNight,
                'status' => $command->status,
                'floor' => $command->floor,
                'capacity' => $command->capacity,
                'description' => $command->description,
                'amenities' => $command->amenities,
            ], fn($value) => $value !== null);

            return $this->roomRepository->update($room, $data);
        });
    }
}
