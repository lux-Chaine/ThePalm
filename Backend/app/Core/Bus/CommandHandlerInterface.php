<?php

namespace App\Core\Bus;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): mixed;
}
