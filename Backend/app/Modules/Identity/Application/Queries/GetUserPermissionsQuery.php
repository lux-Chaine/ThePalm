<?php

namespace App\Modules\Identity\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetUserPermissionsQuery implements QueryInterface
{
    public function __construct(
        public readonly int $userId
    ) {}
}
