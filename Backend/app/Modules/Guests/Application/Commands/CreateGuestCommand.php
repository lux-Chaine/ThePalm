<?php

namespace App\Modules\Guests\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateGuestCommand implements CommandInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly string $identityNumber,
        public readonly string $identityType = 'national_id',
        public readonly ?string $email = null,
        public readonly ?string $dateOfBirth = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $country = 'Egypt',
        public readonly ?string $notes = null
    ) {}
}
