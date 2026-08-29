<?php

namespace App\Modules\Reservations\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateReservationStatusCommand implements CommandInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly string $status,
        public readonly ?string $cancellationReason = null
    ) {}
}
