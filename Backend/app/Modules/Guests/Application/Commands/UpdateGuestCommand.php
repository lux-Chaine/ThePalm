<?php

namespace App\Modules\Guests\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateGuestCommand implements CommandInterface
{
    public function __construct(
        public readonly int $guestId,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $identityNumber = null,
        public readonly ?string $identityType = null,
        public readonly ?string $dateOfBirth = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $country = null,
        public readonly ?string $notes = null
    ) {}
}
