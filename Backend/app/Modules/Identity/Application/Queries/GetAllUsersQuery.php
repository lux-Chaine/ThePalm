<?php

namespace App\Modules\Identity\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetAllUsersQuery implements QueryInterface
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $perPage = null
    ) {}
}
