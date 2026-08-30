<?php

namespace App\Modules\Settings\Application\Commands;

use App\Core\Bus\CommandInterface;

class UpdateSettingCommand implements CommandInterface
{
    public function __construct(
        public readonly string $key,
        public readonly string $value
    ) {}
}
