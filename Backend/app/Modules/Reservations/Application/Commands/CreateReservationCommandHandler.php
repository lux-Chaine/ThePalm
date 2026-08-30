<?php

namespace App\Modules\Reservations\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Reservations\Domain\Reservation;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;

class CreateReservationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository,
        protected RoomRepositoryInterface $roomRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Reservation
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            // Check for conflicting reservations
            $conflicts = $this->reservationRepository->findConflictingReservations(
                $command->roomId,
                $command->checkInDate,
                $command->checkOutDate
            );

            if (!empty($conflicts)) {
                throw new \Exception("Room is not available for the selected dates");
            }

            // Get room to calculate total amount
            $room = $this->roomRepository->findById($command->roomId);
            if (!$room) {
                throw new \Exception("Room not found");
            }

            // Calculate duration and total amount
            $checkIn = new \DateTime($command->checkInDate);
            $checkOut = new \DateTime($command->checkOutDate);
            $duration = $checkIn->diff($checkOut)->days;
            $totalAmount = $room->pricePerNight * $duration;

            $data = [
                'guest_id' => $command->guestId,
                'room_id' => $command->roomId,
                'user_id' => $command->userId,
                'check_in_date' => $command->checkInDate,
                'check_out_date' => $command->checkOutDate,
                'number_of_guests' => $command->numberOfGuests,
                'special_requests' => $command->specialRequests,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ];

            $reservation = $this->reservationRepository->create($data);

            // Update room status
            $this->roomRepository->update($room, ['status' => 'booked']);

            return $reservation;
        });
    }
}
