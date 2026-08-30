<?php

namespace App\Modules\Guests\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetGuestByIdQuery implements QueryInterface
{
    public function __construct(
        public readonly int $guestId
    ) {}
}
