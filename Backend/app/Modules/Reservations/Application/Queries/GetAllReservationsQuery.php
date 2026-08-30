<?php

namespace App\Modules\Reservations\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetAllReservationsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?int $guestId = null,
        public readonly ?int $roomId = null,
        public readonly ?int $userId = null,
        public readonly ?string $status = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?int $page = null,
        public readonly ?int $perPage = null
    ) {}
}
