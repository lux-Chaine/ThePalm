<?php

namespace App\Modules\Settings\Application\Queries;

use App\Core\Bus\QueryInterface;

class GetSettingByKeyQuery implements QueryInterface
{
    public function __construct(
        public readonly string $key
    ) {}
}
