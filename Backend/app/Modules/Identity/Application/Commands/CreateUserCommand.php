<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandInterface;

class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role = 'receptionist'
    ) {}
}
