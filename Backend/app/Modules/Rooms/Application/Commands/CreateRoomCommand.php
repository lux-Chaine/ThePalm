<?php

namespace App\Modules\Rooms\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateRoomCommand implements CommandInterface
{
    public function __construct(
        public readonly string $roomNumber,
        public readonly string $type,
        public readonly float $pricePerNight,
        public readonly int $floor = 1,
        public readonly int $capacity = 2,
        public readonly ?string $description = null,
        public readonly ?array $amenities = null
    ) {}
}
