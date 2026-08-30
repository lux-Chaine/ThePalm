<?php

namespace App\Modules\Reservations\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Reservations\Domain\Reservation;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class UpdateReservationStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository,
        protected RoomRepositoryInterface $roomRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Reservation
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $reservation = $this->reservationRepository->findById($command->reservationId);
            
            if (!$reservation) {
                throw new \Exception("Reservation not found");
            }

            $data = ['status' => $command->status];

            if ($command->cancellationReason) {
                $data['cancellation_reason'] = $command->cancellationReason;
            }

            $updatedReservation = $this->reservationRepository->update($reservation, $data);

            // Update room status based on reservation status
            if ($command->status === 'cancelled' || $command->status === 'completed') {
                $room = $this->roomRepository->findById($reservation->roomId);
                if ($room) {
                    $this->roomRepository->update($room, ['status' => 'available']);
                }
            }

            return $updatedReservation;
        });
    }
}
