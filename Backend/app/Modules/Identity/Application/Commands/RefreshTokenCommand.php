<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandInterface;

class RefreshTokenCommand implements CommandInterface
{
    public function __construct(
        public readonly string $refreshToken
    ) {}
}
