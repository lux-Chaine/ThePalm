<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        public readonly int $userId,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?string $role = null,
        public readonly ?string $userType = null
    ) {}
}
