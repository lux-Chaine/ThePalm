<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandInterface;

class LoginCommand implements CommandInterface
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}
