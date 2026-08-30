<?php

namespace App\Modules\Rooms\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetAllRoomsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?string $type = null,
        public readonly ?string $status = null,
        public readonly ?string $checkIn = null,
        public readonly ?string $checkOut = null,
        public readonly ?int $page = null,
        public readonly ?int $perPage = null
    ) {}
}
