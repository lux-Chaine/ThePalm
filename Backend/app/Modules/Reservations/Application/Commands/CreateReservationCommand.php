<?php

namespace App\Modules\Reservations\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateReservationCommand implements CommandInterface
{
    public function __construct(
        public readonly int $guestId,
        public readonly int $roomId,
        public readonly int $userId,
        public readonly string $checkInDate,
        public readonly string $checkOutDate,
        public readonly int $numberOfGuests = 1,
        public readonly ?string $specialRequests = null
    ) {}
}
