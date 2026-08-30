<?php

namespace App\Modules\Guests\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetAllGuestsQuery implements QueryInterface
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $perPage = null
    ) {}
}
