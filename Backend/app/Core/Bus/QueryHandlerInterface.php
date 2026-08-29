<?php

namespace App\Core\Bus;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
