<?php

namespace App\Modules\Settings\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Modules\Settings\Domain\SettingRepositoryInterface;
use App\Modules\Settings\Infrastructure\EloquentSettingRepository;

class UpdateSettingCommandHandler implements CommandHandlerInterface
{
    protected $settingRepository;

    public function __construct()
    {
        $this->settingRepository = new EloquentSettingRepository();
    }

    public function handle(CommandInterface $command): bool
    {
        $setting = $this->settingRepository->findByKey($command->key);
        
        if (!$setting) {
            return false;
        }

        $setting->value = $command->value;
        $this->settingRepository->save($setting);
        
        return true;
    }
}
