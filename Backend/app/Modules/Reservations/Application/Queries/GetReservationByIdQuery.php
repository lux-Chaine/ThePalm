<?php

namespace App\Modules\Reservations\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetReservationByIdQuery implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
