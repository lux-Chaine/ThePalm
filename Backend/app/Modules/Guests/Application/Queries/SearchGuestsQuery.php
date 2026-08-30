<?php

namespace App\Modules\Guests\Application\Queries;

use App\Core\Bus\QueryInterface;

class SearchGuestsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $identityNumber = null,
        public readonly ?string $city = null,
        public readonly ?string $country = null
    ) {}
}
