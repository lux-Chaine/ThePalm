<?php

namespace App\Modules\Guests\Application\Commands;

use App\Core\Bus\CommandInterface;

class DeleteGuestCommand implements CommandInterface
{
    public function __construct(
        public readonly int $guestId
    ) {}
}
