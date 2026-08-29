<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandInterface;

class DeleteUserCommand implements CommandInterface
{
    public function __construct(
        public readonly int $userId
    ) {}
}
