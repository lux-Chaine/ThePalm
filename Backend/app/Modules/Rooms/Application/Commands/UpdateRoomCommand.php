<?php

namespace App\Modules\Rooms\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateRoomCommand implements CommandInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly ?string $roomNumber = null,
        public readonly ?string $type = null,
        public readonly ?float $pricePerNight = null,
        public readonly ?string $status = null,
        public readonly ?int $floor = null,
        public readonly ?int $capacity = null,
        public readonly ?string $description = null,
        public readonly ?array $amenities = null
    ) {}
}
