<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandInterface;

class ResetPasswordCommand implements CommandInterface
{
    public function __construct(
        public readonly int $userId,
        public readonly string $newPassword
    ) {}
}
